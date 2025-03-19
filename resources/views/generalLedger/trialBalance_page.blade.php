@extends('layouts/app')

@section('content')
@section('title', 'Neraca Saldo')

<h2>Neraca Saldo</h2>

<form action="{{ route('trialBalance_page') }}" method="GET" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <select name="month" class="form-select">
                @for ($i = 1; $i <= 12; $i++) <option value="{{ $i }}" {{ $month == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                    @endfor
            </select>
        </div>
        <div class="col-md-3">
            <select name="year" class="form-select">
                @for ($i = date('Y') - 5; $i <= date('Y') + 5; $i++) <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('export_neraca_saldo', ['month' => $month, 'year' => $year]) }}" class="btn btn-secondary">Export as Excel</a>
        </div>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-light">
            <tr class="text-center">
                <th>Kode Akun</th>
                <th>Nama Akun</th>
                <th>Debit (Rp)</th>
                <th>Kredit (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php
            $totalDebit = 0;
            $totalCredit = 0;
            @endphp
            @foreach($accountBalances as $accountCode => $balance)
            <tr class="text-center">
                <td>{{ $accountCode }}</td>
                <td>{{ $accountNames[$accountCode] ?? 'Tidak Ada Nama Akun' }}</td>
                <td>
                    @if ($balance > 0)
                    {{ number_format($balance, 2) }}
                    @php $totalDebit += $balance; @endphp
                    @else
                    0.00
                    @endif
                </td>
                <td>
                    @if ($balance < 0) {{ number_format(abs($balance), 2) }} @php $totalCredit +=abs($balance); @endphp @else 0.00 @endif </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="text-center">
                <td colspan="2"><strong>Total</strong></td>
                <td><strong>{{ number_format($totalDebit, 2) }}</strong></td>
                <td><strong>{{ number_format($totalCredit, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
</div>

@endsection