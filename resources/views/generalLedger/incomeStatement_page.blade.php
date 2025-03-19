@extends('layouts/app')
@section('content')
@section('title', 'Laporan Laba Rugi')
<style>
    body {
        font-family: sans-serif;
    }

    table {
        width: 80%;
        border-collapse: collapse;
        margin: 20px auto;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }

    .total {
        font-weight: bold;
    }
</style>
<h2>Laporan Laba Rugi</h2>
<div class="mt-4">
    <div class="text-end mb-3">
        <a href="{{ route('export_income_statement') }}" class="btn btn-primary">
            Export as Excel
        </a>
    </div>
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th>Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Pendapatan - Piutang Usaha (1100)</td>
                <td>{{ number_format($piutangUsaha, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Harga Pokok Penjualan - Persediaan (1200)</td>
                <td>{{ number_format($persediaan, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="fw-bolder">Laba Kotor</td>
                <td>{{ number_format($labaKotor, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Beban Operasional - Diskon Pembelian (5200)</td>
                <td>{{ number_format($diskonPembelian, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Beban Operasional - Penyusutan Aset Tetap (1500)</td>
                <td>{{ number_format($asetTetap, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="fw-bolder">Laba Bersih</td>
                <td>{{ number_format($labaBersih, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection