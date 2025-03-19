@extends('layouts.app')

@section('content')
@section('title', 'Chart of Account')

<h2>Edit Akun {{ $account->account_name }}</h2>

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
    <form id="accountForm" action="{{ route('account_update', $account->account_code) }}" method="POST">
    @csrf
    @method('PUT') 
        <div class="mb-3">
            <label for="account_type" class="form-label">Tipe Akun</label>
            <select class="form-select" id="account_type" name="account_type" disabled>
                <option value="{{ $account->account_type }}">{{ $account->account_type }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="account_section" class="form-label">Bagian Akun</label>
            <select class="form-select" id="account_section" name="account_section" disabled>
                <option value="{{ $account->account_section }}">{{ $account->account_section }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="account_subsection" class="form-label">Anak Bagian Akun</label>
            <select class="form-select" id="account_subsection" name="account_subsection" disabled>
                <option value="{{ $account->account_subsection }}">{{ $account->account_subsection }}</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="account_name" class="form-label">Nama Akun</label>
            <input type="text" class="form-control" id="account_name" name="account_name" value="{{ $account->account_name }}" required>
        </div>

        <div class="modal-footer">
            <a href="{{ route('account_page') }}" class="btn btn-secondary">Tutup</a>
            <button type="submit" class="btn btn-primary" id="saveAccountBtn">Simpan Akun</button>
        </div>
    </form>
</div>
@endsection