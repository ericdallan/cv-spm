<?php

namespace App\Http\Controllers;

use App\Models\voucherDetails;
use App\Models\ChartOfAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Carbon\Carbon;

class generalLedgerController extends Controller
{
    public function generalledger_page(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $voucherDetails = VoucherDetails::with('voucher')
            ->whereHas('voucher', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('voucher_date', [$startDate, $endDate]); // Filter by voucher_date
            })
            ->orderBy('account_code')
            ->get();

        return view('generalLedger.generalLedger_page', compact('voucherDetails', 'year', 'month'));
    }
    public function trialBalance_page(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();

        $voucherDetails = VoucherDetails::with('voucher')
            ->whereHas('voucher', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('voucher_date', [$startDate, $endDate]);
            })
            ->get();

        $accountBalances = [];
        foreach ($voucherDetails as $detail) {
            if (!isset($accountBalances[$detail->account_code])) {
                $accountBalances[$detail->account_code] = 0;
            }
            $accountBalances[$detail->account_code] += $detail->debit - $detail->credit;
        }

        $accountNames = ChartOfAccount::pluck('account_name', 'account_code')->toArray();

        ksort($accountBalances);
        ksort($accountNames);

        return view('generalLedger.trialBalance_page', compact('accountBalances', 'accountNames', 'year', 'month'));
    }

    public function incomeStatement_page()
    {
        // Ambil data dari voucher_details
        $piutangUsahaData = DB::table('voucher_details')
            ->where('account_code', '1100') // Menggunakan account_code
            ->get();

        $persediaanData = DB::table('voucher_details')
            ->where('account_code', '1200') // Menggunakan account_code
            ->get();

        $diskonPembelianData = DB::table('voucher_details')
            ->where('account_code', '5200') // Menggunakan account_code
            ->get();

        $asetTetapData = DB::table('voucher_details')
            ->where('account_code', '1500') // Menggunakan account_code
            ->get();

        // Hitung total jika data tersedia, jika tidak, set ke 0
        $piutangUsaha = $piutangUsahaData->isNotEmpty() ? $piutangUsahaData->sum('debit') - $piutangUsahaData->sum('credit') : 0;
        $persediaan = $persediaanData->isNotEmpty() ? $persediaanData->sum('debit') - $persediaanData->sum('credit') : 0;
        $diskonPembelian = $diskonPembelianData->isNotEmpty() ? $diskonPembelianData->sum('debit') - $diskonPembelianData->sum('credit') : 0;
        $asetTetap = $asetTetapData->isNotEmpty() ? $asetTetapData->sum('debit') - $asetTetapData->sum('credit') : 0;

        // Hitung laba rugi
        $labaKotor = $piutangUsaha - $persediaan;
        $labaBersih = $labaKotor - $diskonPembelian - $asetTetap;

        // Kirim data ke view
        return view('generalLedger.incomeStatement_page', [
            'piutangUsaha' => $piutangUsaha,
            'persediaan' => $persediaan,
            'diskonPembelian' => $diskonPembelian,
            'asetTetap' => $asetTetap,
            'labaKotor' => $labaKotor,
            'labaBersih' => $labaBersih,
        ]);
    }
    public function balanceSheet_page()
    {
        // Ambil data dari voucher_details
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

        // Hitung total jika data tersedia, jika tidak, set ke 0
        $kas = $kasData->isNotEmpty() ? $kasData->sum('debit') - $kasData->sum('credit') : 0;
        $piutangUsaha = $piutangUsahaData->isNotEmpty() ? $piutangUsahaData->sum('debit') - $piutangUsahaData->sum('credit') : 0;
        $utangJangkaPanjang = $utangJangkaPanjangData->isNotEmpty() ? $utangJangkaPanjangData->sum('credit') - $utangJangkaPanjangData->sum('debit') : 0; // Kewajiban
        $diskonPembelian = $diskonPembelianData->isNotEmpty() ? $diskonPembelianData->sum('credit') - $diskonPembelianData->sum('debit') : 0; // Kontra Pendapatan

        // Hitung laba ditahan (contoh sederhana)
        $labaDitahan = $diskonPembelian; // Sesuaikan dengan logika perhitungan laba ditahan yang sebenarnya

        // Kirim data ke view
        return view('generalLedger.balanceSheet_page', [
            'kas' => $kas,
            'piutangUsaha' => $piutangUsaha,
            'utangJangkaPanjang' => $utangJangkaPanjang,
            'labaDitahan' => $labaDitahan,
        ]);
    }
}
