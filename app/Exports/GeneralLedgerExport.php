<?php

namespace App\Exports;

use App\Models\VoucherDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class GeneralLedgerExport implements FromCollection, WithHeadings
{
    protected $voucherDetails;

    public function __construct($voucherDetails)
    {
        $this->voucherDetails = $voucherDetails;
    }

    public function collection()
    {
        $data = [];
        $groupedDetails = $this->voucherDetails->groupBy('account_code');

        foreach ($groupedDetails as $accountCode => $details) {
            $accountName = $details->first()->account_name ?? 'Tidak Ada Nama Akun';
            $saldo = 0;

            foreach ($details as $detail) {
                $saldo += $detail->debit - $detail->credit;
                $data[] = [
                    Carbon::parse($detail->voucher->voucher_date)->isoFormat('dddd, DD MMMM YYYY'),
                    $detail->voucher->description,
                    $detail->voucher->voucher_number,
                    number_format($detail->debit, 2),
                    number_format($detail->credit, 2),
                    number_format($saldo, 2),
                    $accountName,
                    $accountCode,
                ];
            }
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Keterangan',
            'Ref',
            'Debit (Rp)',
            'Kredit (Rp)',
            'Saldo (Rp)',
            'Nama Akun',
            'Kode Akun',
        ];
    }
}