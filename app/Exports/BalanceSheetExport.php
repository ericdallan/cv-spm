<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BalanceSheetExport implements FromCollection, WithHeadings
{
    protected $kas;
    protected $piutangUsaha;
    protected $utangJangkaPanjang;
    protected $labaDitahan;

    public function __construct($kas, $piutangUsaha, $utangJangkaPanjang, $labaDitahan)
    {
        $this->kas = $kas;
        $this->piutangUsaha = $piutangUsaha;
        $this->utangJangkaPanjang = $utangJangkaPanjang;
        $this->labaDitahan = $labaDitahan;
    }

    public function collection()
    {
        return new Collection([
            [
                'Aset',
                'Jumlah (Rp)',
                'Kewajiban',
                'Jumlah (Rp)',
                'Ekuitas',
                'Jumlah (Rp)',
            ],
            [
                'Kas (1000)',
                number_format($this->kas, 2),
                'Utang Jangka Panjang (2500)',
                number_format($this->utangJangkaPanjang, 2),
                'Laba Ditahan',
                number_format($this->labaDitahan, 2),
            ],
            [
                'Piutang Usaha (1100)',
                number_format($this->piutangUsaha, 2),
                '',
                '',
                '',
                '',
            ],
        ]);
    }

    public function headings(): array
    {
        return [
            '',
            '',
            '',
            '',
            '',
            '',
        ];
    }
}