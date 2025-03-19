@extends('layouts.app')

@section('content')
@section('title', 'Chart of Account')

<h2>Chart of Account</h2>

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

<div class="text-end mb-3">
    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#accountModal">
        Tambah Akun
    </button>
    @extends('AccountCode.accountCode_form')
</div>
<div class="mt-4">
    <table class="table table-striped table-bordered">
        <thead class="thead-light text-center align-middle">
            <tr>
                <th>Account Type</th>
                <th>Account Section</th>
                <th>Account Subsection</th>
                <th>Account Name</th>
                <th>Account Code</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php
            $sortedHierarkiAkun = collect($hierarkiAkun)
            ->map(function ($accountSections) {
            return collect($accountSections)
            ->map(function ($accountSubsections) {
            if (is_array($accountSubsections)) {
            return collect($accountSubsections)
            ->map(function ($accountNames) {
            if (is_array($accountNames)) {
            return collect($accountNames)->sortBy(function ($account) {
            if (is_array($account) && isset($account[1])) {
            $parts = explode('.', $account[1]);
            return end($parts) * 1;
            }
            return null;
            })->toArray();
            }
            return $accountNames;
            })->toArray();
            }
            return $accountSubsections;
            })->toArray();
            })->toArray();
            @endphp

            @foreach ($sortedHierarkiAkun as $accountType => $accountSections)
            @php
            $formattedAccountType = str_replace('_', ' ', $accountType);
            $firstSection = true;
            @endphp
            @foreach ($accountSections as $accountSection => $accountSubsections)
            @php
            $firstSubsection = true;
            @endphp
            @if (is_array($accountSubsections))
            @foreach ($accountSubsections as $accountSubsection => $accountNames)
            @foreach ($accountNames as $accountName)
            <tr>
                @if ($firstSection)
                <td class="thead-light text-center align-middle" style="font-weight: bold; color: black; text-transform: uppercase;">{{ $formattedAccountType }}</td>
                @php
                $firstSection = false;
                @endphp
                @else
                <td></td>
                @endif
                @if ($firstSubsection)
                <td style="font-weight: bold;">{{ $accountSection }}</td>
                @php
                $firstSubsection = false;
                @endphp
                @else
                <td></td>
                @endif
                @if (is_array($accountNames))
                @if (is_array($accountName))
                @if ($loop->first)
                <td style="color: red;">{{ $accountSubsection }}</td>
                @else
                <td></td>
                @endif
                <td class="pl-4">{{ $accountName[0] }}</td>
                <td>{{ $accountName[1] }}</td>
                @else
                @if ($loop->first)
                <td style="color: red;">{{ $accountSubsection }}</td>
                @else
                <td></td>
                @endif
                <td class="pl-4">{{ $accountName }}</td>
                <td></td>
                @endif
                @else
                <td class="pl-4">{{ $accountName }}</td>
                <td></td>
                @endif
                <td>
                    @php
                    $accountCode = is_array($accountName) ? (isset($accountName[1]) ? $accountName[1] : null) : (isset($accountName->account_code) ? $accountName->account_code : null);
                    @endphp
                    @if ($accountCode)
                    <a href="{{ route('accoundeCode_edit', $accountCode) }}" class="btn btn-warning btn-sm">Edit</a>
                    @else
                    Edit
                    @endif
                </td>
            </tr>
            @endforeach
            @endforeach
            @else
            @foreach ($accountSubsections as $accountName)
            <tr>
                @if ($firstSection)
                <td style="font-weight: bold; color: black; text-transform: uppercase;">{{ $formattedAccountType }}</td>
                @php
                $firstSection = false;
                @endphp
                @else
                <td></td>
                @endif
                @if ($firstSubsection)
                <td style="font-weight: bold;">{{ $accountSection }}</td>
                @php
                $firstSubsection = false;
                @endphp
                @else
                <td></td>
                @endif
                @if (is_array($accountName))
                <td class="pl-4">{{ $accountName[0] }}</td>
                <td>{{ $accountName[1] }}</td>
                @else
                <td class="pl-4">{{ $accountName }}</td>
                <td></td>
                @endif
                <td>
                    @php
                    $accountCode = is_array($accountName) ? (isset($accountName[1]) ? $accountName[1] : null) : (isset($accountName->account_code) ? $accountName->account_code : null);
                    @endphp
                    @if ($accountCode)
                    <a href="{{ route('accoundeCode_edit', $accountCode) }}" class="btn btn-warning btn-sm">Edit</a>
                    @else
                    Edit
                    @endif
                </td>
            </tr>
            @endforeach
            @endif
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection