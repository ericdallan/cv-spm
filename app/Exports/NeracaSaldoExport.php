<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class NeracaSaldoExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $neracaSaldo = DB::table('voucher_details')->get();
        return $neracaSaldo->map(function ($item) {
            return [
                'account_code' => $item->account_code,
                'account_name' => $item->account_name,
                'debit' => $item->debit,
                'credit' => $item->credit,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Kode Akun',
            'Nama Akun',
            'Debit (Rp)',
            'Kredit (Rp)',
        ];
    }
}