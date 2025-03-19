@extends('layouts/app')

@section('content')
@section('title', 'Voucher Akuntansi')
<h2>Daftar Voucher</h2>

@if (session('success'))
<div id="success-message" class="alert alert-success alert-dismissible fade show" role="alert" style="cursor: pointer;">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if ($errors->any())
<div id="error-message" class="alert alert-danger alert-dismissible fade show" role="alert" style="cursor: pointer;">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="mt-4">
    <div class="text-end mb-3">
        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#voucherModal">
            Tambah Voucher
        </button>
        @extends('voucher/voucher_form')
    </div>

    @if (count($voucher) > 0)
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover text-center">
            <thead class="table-light">
                <tr>
                    <th>Voucher Number</th>
                    <th>Voucher Type</th>
                    <th>Voucher Date</th>
                    <th>Prepared By</th>
                    <th>Approved By</th>
                    <th>Total Debit</th>
                    <th>Total Credit</th>
                    <th colspan="3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($voucher as $voucher_item)
                <tr>
                    <td>{{ $voucher_item->voucher_number }}</td>
                    <td>{{ $voucher_item->voucher_type }}</td>
                    <td>{{ $voucher_item->voucher_date->format('Y-m-d') }}</td>
                    <td>{{ $voucher_item->prepared_by }}</td>
                    <td>{{ $voucher_item->approved_by }}</td>
                    <td>{{ number_format($voucher_item->total_debit, 2) }}</td>
                    <td>{{ number_format($voucher_item->total_credit, 2) }}</td>
                    <td><a href="{{ route('voucher_detail', $voucher_item->id) }}" class="btn btn-primary btn-sm">Detail</a></td>
                    <td><a href="{{ route('voucher_edit', $voucher_item->id) }}" class="btn btn-warning btn-sm">Edit</a></td>
                    <td>
                        <form action="{{ route('voucher.delete', $voucher_item->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus voucher ini?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p>No vouchers found.</p>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            // Bootstrap alert already has close button and dismiss functionality
        }
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            // Bootstrap alert already has close button and dismiss functionality
        }
    });
</script>
@endsection