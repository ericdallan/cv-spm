@extends('layouts.app')

@section('title', 'Voucher Akuntansi')

@section('content')
<div class="container">
    <h2 class="text-center">{{ $headingText }} Details</h2>
    <a href="{{ route('voucher_pdf', $voucher->id) }}" class="btn btn-primary" target="_blank">Print Out PDF</a>

    <div class="row mb-3">
        <label for="voucherNumber" class="col-sm-3 col-form-label">Voucher Number:</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="voucherNumber" value="{{ $voucher->voucher_number }}" readonly>
        </div>
    </div>

    <div class="row mb-3">
        <label for="companyName" class="col-sm-3 col-form-label">Company Name:</label>
        <div class="col-sm-9">
            @if ($company)
            <input type="text" class="form-control" id="companyName" value="{{ $company->company_name }}" readonly>
            @else
            <input type="text" class="form-control" id="companyName" value="Nama Perusahaan Kosong" readonly>
            <small class="text-danger">Data nama perusahaan tidak ditemukan.</small>
            @endif
        </div>
    </div>
    <div class="row mb-3">
        <label for="voucherType" class="col-sm-3 col-form-label">Voucher Type:</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="voucherType" value="{{ $voucher->voucher_type }}" readonly>
        </div>
        <label for="voucherDate" class="col-sm-2 col-form-label">Date:</label>
        <div class="col-sm-4">
            <input type="text" class="form-control" id="voucherDate" value="{{ $voucher->created_at->format('Y-m-d H:i:s') }}" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <label for="preparedBy" class="col-sm-3 col-form-label">Prepared By:</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="preparedBy" value="{{ $voucher->prepared_by }}" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <label for="approvedBy" class="col-sm-3 col-form-label">Approved By (Optional):</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="approvedBy" value="{{ $voucher->approved_by }}" readonly>
        </div>
    </div>
    <div class="row mb-3">
        <label for="description" class="col-sm-3 col-form-label">Deskripsi:</label>
        <div class="col-sm-9">
            <input type="text" class="form-control" id="description" value="{{ $voucher->description }}" readonly>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" id="voucherDetailsTable">
            <thead>
                <tr class="text-center">
                    <th>Account Code</th>
                    <th>Account Name</th>
                    <th>Debit</th>
                    <th>Credit</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach($voucherDetails as $detail)
                <tr>
                    <td>{{ $detail->account_code }}</td>
                    <td>{{ $detail->account_name }}</td>
                    <td>{{ number_format($detail->debit, 2) }}</td>
                    <td>{{ number_format($detail->credit, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="row mb-3">
        <label for="totalDebit" class="col-sm-3 col-form-label">Total Debit:</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="totalDebit" value="{{ number_format($voucher->total_debit, 2) }}" readonly>
        </div>
        <label for="totalCredit" class="col-sm-3 col-form-label">Total Credit:</label>
        <div class="col-sm-3">
            <input type="text" class="form-control" id="totalCredit" value="{{ number_format($voucher->total_credit, 2) }}" readonly>
        </div>
    </div>
    <table class="table table-bordered" style="margin-left: auto; width: 70%;">
        <tr class="text-center">
            <th>Created By</th>
            <th>Checked By</th>
            <th>Reviewed By</th>
            <th>Approved By</th>
        </tr>
        <tr>
            <td style="width: 25%; height: 100px;"></td>
            <td style="width: 25%; height: 100px;"></td>
            <td style="width: 25%; height: 100px;"></td>
            <td style="width: 25%; height: 100px;"></td>
        </tr>
    </table>
</div>
@endsection