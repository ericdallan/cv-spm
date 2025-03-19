@extends('layouts/app')
@section('content')
@section('title', 'Neraca')
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
<h1>Neraca Keuangan</h1>

<div class="mt-4">
    <div class="text-end mb-3">
        <a href="{{ route('export_BalanceSheet') }}" class="btn btn-primary">
            Export as Excel
        </a>
    </div>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Aset</th>
                <th>Jumlah (Rp)</th>
                <th>Kewajiban</th>
                <th>Jumlah (Rp)</th>
                <th>Ekuitas</th>
                <th>Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Kas (1000)</td>
                <td>{{ number_format($kas, 2, ',', '.') }}</td>
                <td>Utang Jangka Panjang (2500)</td>
                <td>{{ number_format($utangJangkaPanjang, 2, ',', '.') }}</td>
                <td>Laba Ditahan</td>
                <td>{{ number_format($labaDitahan, 2, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Piutang Usaha (1100)</td>
                <td>{{ number_format($piutangUsaha, 2, ',', '.') }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>

@endsection