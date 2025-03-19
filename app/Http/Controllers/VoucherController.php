<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\voucherDetails;
use App\Models\ChartOfAccount;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class VoucherController extends Controller
{
    public function voucher_page()
    {
        $voucher = DB::table('vouchers')->get();
        foreach ($voucher as $item) {
            $item->voucher_date = Carbon::parse($item->voucher_date);
        }

        $accounts = ChartOfAccount::orderBy('account_type')
            ->orderBy('account_section')
            ->orderBy('account_subsection')
            ->orderBy('account_code') // Initial lexicographical order
            ->get()
            ->sortBy(function ($account) {
                $parts = explode('.', $account->account_code);
                if (count($parts) > 0) {
                    return intval(end($parts)); // Sort numerically by the last segment
                }
                return null;
            })
            ->sortBy(function ($account) {
                $parts = explode('.', $account->account_code);
                // Create a sort key based on the preceding parts (excluding the last)
                return implode('.', array_slice($parts, 0, -1));
            });

        return view('voucher.voucher_page', compact('voucher', 'accounts'));
    }
    public function voucher_form(Request $request)
    {
        try {
            DB::beginTransaction(); // Mulai transaksi

            // Generate nomor voucher
            $voucherNumber = $this->generateVoucherNumber($request->voucher_type);

            $voucher = Voucher::create([
                'voucher_number' => $voucherNumber,
                'voucher_type' => $request->voucher_type,
                'voucher_date' => $request->voucher_date,
                'voucher_day' => $request->voucher_day,
                'prepared_by' => $request->prepared_by,
                'approved_by' => $request->approved_by,
                'description' => $request->description,
                'total_debit' => $request->total_debit,
                'total_credit' => $request->total_credit,
            ]);

            if ($request->has('details')) {
                foreach ($request->details as $detail) {
                    VoucherDetails::create([
                        'voucher_id' => $voucher->id,
                        'account_code' => $detail['account_code'],
                        'account_name' => $detail['account_name'],
                        'debit' => $detail['debit'] ?? 0,
                        'credit' => $detail['credit'] ?? 0,
                    ]);
                }
            }

            DB::commit(); // Commit transaksi

            return redirect()->back()->with('success', 'Voucher berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaksi jika terjadi kesalahan

            Log::error('Error creating voucher: ' . $e->getMessage());

            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat membuat voucher.'])->withInput();
        }
    }

    public function voucher_edit($id)
    {
        $company = Company::select('company_name')->first();
        $voucher = Voucher::findOrFail($id);
        $voucherDetails = VoucherDetails::where('voucher_id', $id)->get();
        $accounts = ChartOfAccount::orderBy('account_type')
        ->orderBy('account_section')
        ->orderBy('account_subsection')
        ->orderBy('account_code') // Initial lexicographical order
        ->get()
        ->sortBy(function ($account) {
            $parts = explode('.', $account->account_code);
            if (count($parts) > 0) {
                return intval(end($parts)); // Sort numerically by the last segment
            }
            return null;
        })
        ->sortBy(function ($account) {
            $parts = explode('.', $account->account_code);
            // Create a sort key based on the preceding parts (excluding the last)
            return implode('.', array_slice($parts, 0, -1));
        });

        // Determine the heading text based on voucher type
        $headingText = $this->getVoucherHeading($voucher->voucher_type);

        return view('voucher.voucher_edit', compact('voucher', 'headingText', 'voucherDetails', 'accounts','company'));
    }

    public function voucher_update(Request $request, $id)
    {
        $request->validate([
            'voucher_type' => 'required',
            'voucher_date' => 'required|date',
            'prepared_by' => 'required',
            'details.*.account_code' => 'required',
            'details.*.account_name' => 'required',
            'details.*.debit' => 'required|numeric|min:0',
            'details.*.credit' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $voucher = Voucher::findOrFail($id);
            $voucher->voucher_type = $request->voucher_type;
            $voucher->voucher_date = Carbon::parse($request->voucher_date);
            $voucher->prepared_by = $request->prepared_by;
            $voucher->approved_by = $request->approved_by;
            $voucher->description = $request->description;

            $totalDebit = 0;
            $totalCredit = 0;
            foreach ($request->details as $detail) {
                $totalDebit += $detail['debit'];
                $totalCredit += $detail['credit'];
            }
            $voucher->total_debit = $totalDebit;
            $voucher->total_credit = $totalCredit;
            $voucher->save();

            // Hapus detail voucher yang ada
            voucherDetails::where('voucher_id', $id)->delete();

            // Tambahkan detail voucher yang baru
            foreach ($request->details as $detail) {
                $voucherDetail = new voucherDetails();
                $voucherDetail->voucher_id = $voucher->id;
                $voucherDetail->account_code = $detail['account_code'];
                $voucherDetail->account_name = $detail['account_name'];
                $voucherDetail->debit = $detail['debit'];
                $voucherDetail->credit = $detail['credit'];
                $voucherDetail->save();
            }

            DB::commit();
            return redirect()->route('voucher_page')->with('success', 'Voucher berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating voucher: ' . $e->getMessage()); // Tambahkan baris ini
            return redirect()->back()->withErrors(['message' => 'Terjadi kesalahan saat memperbarui voucher.']);
        }
    }

    public function voucher_delete($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->delete();

        return redirect()->route('voucher_page')->with('success', 'Voucher berhasil dihapus.');
    }

    private function generateVoucherNumber($voucherType)
    {
        $lastVoucher = Voucher::where('voucher_type', $voucherType)
            ->orderBy('voucher_number', 'desc')
            ->first();

        if ($lastVoucher) {
            $lastNumber = intval(substr($lastVoucher->voucher_number, 3));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $voucherType . '-' . str_pad($newNumber, 8, '0', STR_PAD_LEFT);
    }

    public function voucher_detail($id)
    {
        $company = Company::select('company_name')->first();
        $voucher = Voucher::findOrFail($id);
        $voucherDetails = VoucherDetails::where('voucher_id', $id)->get();

        // Determine the heading text based on voucher type
        $headingText = $this->getVoucherHeading($voucher->voucher_type);

        return view('voucher.voucher_detail', compact('voucher', 'headingText', 'voucherDetails','company'));
    }
    private function getVoucherHeading($voucherType)
    {
        switch ($voucherType) {
            case 'JV':
                return 'Journal Voucher';
            case 'MP':
                return 'Material Purchase';
            case 'MG':
                return 'Memorial General';
            case 'CG':
                return 'Cash/Bank Transfer';
            default:
                return 'Voucher';
        }
    }
    public function generatePdf($id)
    {
        $voucher = Voucher::findOrFail($id);
        $details = voucherDetails::where('voucher_id', $id)->get();
        $company = Company::select('company_name')->first();

        $headingText = $this->getVoucherHeading($voucher->voucher_type);
        $pdf = Pdf::loadView('voucher.voucher_pdf', compact('voucher', 'details', 'headingText','company'));

        return $pdf->download('voucher-' . $voucher->voucher_number . '.pdf');
    }
}
