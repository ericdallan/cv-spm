@extends('layouts/app')

@section('content')
@section('title', 'Buku Besar')

<h2>Buku Besar</h2>

<form action="{{ route('generalledger_page') }}" method="GET" class="mb-3">
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
            <a href="{{ route('generalledger_print', ['month' => $month, 'year' => $year]) }}" class="btn btn-secondary">Export Excel</a>
        </div>
    </div>
</form>

<div class="table-responsive">
    @php
    $groupedDetails = $voucherDetails->groupBy('account_code');
    @endphp

    @foreach($groupedDetails as $accountCode => $details)
    @php
    $accountName = $details->first()->account_name ?? 'Tidak Ada Nama Akun';
    @endphp

    <h3 class="mt-4">Akun {{ $accountName }} ({{ $accountCode }})</h3>
    <table class="table table-striped table-bordered table-hover">
        <thead class="table-light">
            <tr class="text-center">
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Ref</th>
                <th>Debit (Rp)</th>
                <th>Kredit (Rp)</th>
                <th>Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php
            $saldo = 0;
            @endphp
            @foreach($details as $detail)
            @php
            $saldo += $detail->debit - $detail->credit;
            @endphp
            <tr class="text-center">
                <td>{{ \Carbon\Carbon::parse($detail->voucher->voucher_date)->isoFormat('dddd, DD MMMM YYYY') }}</td>
                <td>{{ $detail->voucher->description }}</td>
                <td>
                    <a href="{{ route('voucher_detail', $detail->voucher->id) }}" class="text-decoration-none">
                        {{ $detail->voucher->voucher_number }}
                    </a>
                </td>
                <td>{{ number_format($detail->debit, 2) }}</td>
                <td>{{ number_format($detail->credit, 2) }}</td>
                <td>{{ number_format($saldo, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endforeach
</div>

@endsection