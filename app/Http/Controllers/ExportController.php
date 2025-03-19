<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\voucherDetails;
use App\Exports\BalanceSheetExport;
use App\Exports\IncomeStatementExport;
use App\Exports\NeracaSaldoExport;
use App\Exports\GeneralLedgerExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function generalledger_print(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $voucherDetails = VoucherDetails::with('voucher')
            ->whereHas('voucher', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('voucher_date', [$startDate, $endDate]);
            })
            ->orderBy('account_code')
            ->get();

        return Excel::download(new GeneralLedgerExport($voucherDetails), 'general_ledger_' . $month . '_' . $year . '.xlsx');
    }
    public function exportIncomeStatement()
    {
        // Ambil data Laporan Laba Rugi dari sumber yang sesuai
        $piutangUsahaData = DB::table('voucher_details')
            ->where('account_code', '1100')
            ->get();

        $persediaanData = DB::table('voucher_details')
            ->where('account_code', '1200')
            ->get();

        $diskonPembelianData = DB::table('voucher_details')
            ->where('account_code', '5200')
            ->get();

        $asetTetapData = DB::table('voucher_details')
            ->where('account_code', '1500')
            ->get();

        // Hitung total jika data tersedia, jika tidak, set ke 0
        $piutangUsaha = $piutangUsahaData->isNotEmpty() ? $piutangUsahaData->sum('debit') - $piutangUsahaData->sum('credit') : 0;
        $persediaan = $persediaanData->isNotEmpty() ? $persediaanData->sum('debit') - $persediaanData->sum('credit') : 0;
        $diskonPembelian = $diskonPembelianData->isNotEmpty() ? $diskonPembelianData->sum('debit') - $diskonPembelianData->sum('credit') : 0;
        $asetTetap = $asetTetapData->isNotEmpty() ? $asetTetapData->sum('debit') - $asetTetapData->sum('credit') : 0;

        // Hitung laba rugi
        $labaKotor = $piutangUsaha - $persediaan;
        $labaBersih = $labaKotor - $diskonPembelian - $asetTetap;

        $data = collect([
            [
                'Keterangan' => 'Pendapatan - Piutang Usaha (1100)',
                'Jumlah (Rp)' => $piutangUsaha,
            ],
            [
                'Keterangan' => 'Harga Pokok Penjualan - Persediaan (1200)',
                'Jumlah (Rp)' => $persediaan,
            ],
            [
                'Keterangan' => 'Laba Kotor',
                'Jumlah (Rp)' => $labaKotor,
            ],
            [
                'Keterangan' => 'Beban Operasional - Diskon Pembelian (5200)',
                'Jumlah (Rp)' => $diskonPembelian,
            ],
            [
                'Keterangan' => 'Beban Operasional - Penyusutan Aset Tetap (1500)',
                'Jumlah (Rp)' => $asetTetap,
            ],
            [
                'Keterangan' => 'Laba Bersih',
                'Jumlah (Rp)' => $labaBersih,
            ],
        ]);

        return Excel::download(new IncomeStatementExport($data), 'income_statement.xlsx');
    }
    public function exportNeracaSaldo()
    {
        return Excel::download(new NeracaSaldoExport, 'neraca_saldo.xlsx');
    }
    public function exportBalanceSheet()
    {
        // Ambil data neraca saldo dari database (atau dari mana pun data berasal)
        $kasData = DB::table('voucher_details')
            ->where('account_code', '1000')
            ->get();
        $piutangUsahaData = DB::table('voucher_details')
            ->where('account_code', '1100')
            ->get();
        $utangJangkaPanjangData = DB::table('voucher_details')
            ->where('account_code', '2500')
            ->get();
        $diskonPembelianData = DB::table('voucher_details')
            ->where('account_code', '5200')
            ->get();

        $kas = $kasData->isNotEmpty() ? $kasData->sum('debit') - $kasData->sum('credit') : 0;
        $piutangUsaha = $piutangUsahaData->isNotEmpty() ? $piutangUsahaData->sum('debit') - $piutangUsahaData->sum('credit') : 0;
        $utangJangkaPanjang = $utangJangkaPanjangData->isNotEmpty() ? $utangJangkaPanjangData->sum('credit') - $utangJangkaPanjangData->sum('debit') : 0;
        $diskonPembelian = $diskonPembelianData->isNotEmpty() ? $diskonPembelianData->sum('credit') - $diskonPembelianData->sum('debit') : 0;

        $labaDitahan = $diskonPembelian;

        return Excel::download(new BalanceSheetExport($kas, $piutangUsaha, $utangJangkaPanjang, $labaDitahan), 'balance_sheet.xlsx');
    }
}
