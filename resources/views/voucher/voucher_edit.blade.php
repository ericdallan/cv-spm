@extends('layouts/app')

@section('content')
@section('title', 'Edit Voucher')


@if (session('success'))
<div id="success-message" class="alert alert-success" style="cursor: pointer;">
    {{ session('success') }}
</div>
@endif

@if ($errors->any())
<div id="error-message" class="alert alert-danger" style="cursor: pointer;">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@elseif (session('message'))
<div id="error-message" class="alert alert-danger" style="cursor: pointer;">
    {{ session('message') }}
</div>
@endif


<form id="voucherForm" method="POST" action="{{ route('voucher.update', $voucher->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="container mt-4">
        <h2 class="text-center">Formulir Edit {{ $headingText }} Voucher</h2>
        <div class="row mb-3">
            <label for="voucherNumber" class="col-sm-3 col-form-label">Nomor Voucher:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="voucherNumber" name="voucher_number" value="{{ $voucher->voucher_number }}" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <label for="companyName" class="col-sm-3 col-form-label">Nama Perusahaan:</label>
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
            <label for="voucherType" class="col-sm-3 col-form-label">Jenis Voucher:</label>
            <div class="col-sm-3">
                <select class="form-select" id="voucherType" name="voucher_type">
                    <option value="JV" {{ $voucher->voucher_type == 'JV' ? 'selected' : '' }}>JV</option>
                    <option value="MP" {{ $voucher->voucher_type == 'MP' ? 'selected' : '' }}>MP</option>
                    <option value="MG" {{ $voucher->voucher_type == 'MG' ? 'selected' : '' }}>MG</option>
                    <option value="CG" {{ $voucher->voucher_type == 'CG' ? 'selected' : '' }}>CG</option>
                </select>
            </div>
            <label for="voucherDate" class="col-sm-2 col-form-label">Tanggal:</label>
            <div class="col-sm-2">
                <input type="date" class="form-control" id="voucherDate" name="voucher_date" value="{{ $voucher->voucher_date->format('Y-m-d') }}">
            </div>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="voucherDay" name="voucher_day">
            </div>
        </div>
        <div class="row mb-3">
            <label for="preparedBy" class="col-sm-3 col-form-label">Disiapkan Oleh:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="preparedBy" name="prepared_by" value="{{ $voucher->prepared_by }}">
            </div>
        </div>
        <div class="row mb-3">
            <label for="approvedBy" class="col-sm-3 col-form-label">Disetujui Oleh (Opsional):</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="approvedBy" name="approved_by" value="{{ $voucher->approved_by }}">
            </div>
        </div>
        <div class="row mb-3">
            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="description" name="description" value="{{ $voucher->description }}">
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered" id="voucherDetailsTable">
                <thead>
                    <tr class="text-center">
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Debit</th>
                        <th>Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($voucher->details as $index => $detail)
                    <tr>
                        <td>
                            <select class="form-select accountCodeSelect" name="details[{{ $index }}][account_code]">
                                <option value="">Pilih Kode Akun</option>
                                @foreach($accounts as $account)
                                <option value="{{ $account->account_code }}">
                                    {{ $account->account_code }} - {{ $account->account_name }}
                                </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" class="form-control accountName" name="details[{{ $index }}][account_name]" value="{{ $detail->account_name }}" readonly></td>
                        <td><input type="number" min="0" class="form-control debitInput" name="details[{{ $index }}][debit]" value="{{ $detail->debit }}"></td>
                        <td><input type="number" min="0" class="form-control creditInput" name="details[{{ $index }}][credit]" value="{{ $detail->credit }}"></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" id="addRowBtn" class="btn btn-primary">Tambah Baris</button>
            <button type="button" class="btn btn-danger removeRowBtn">Hapus</button>
        </div>
        <div class="row mb-3">
            <label for="totalDebit" class="col-sm-3 col-form-label">Total Debit:</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="totalDebit" name="total_debit" value="{{ $voucher->total_debit }}" readonly>
            </div>
            <label for="totalCredit" class="col-sm-3 col-form-label">Total Kredit:</label>
            <div class="col-sm-3">
                <input type="text" class="form-control" id="totalCredit" name="total_credit" value="{{ $voucher->total_credit }}" readonly>
            </div>
        </div>
        <div class="row mb-3">
            <label for="validation" class="col-sm-3 col-form-label">Validasi:</label>
            <div class="col-sm-9">
                <input type="text" class="form-control" id="validation" name="validation" value="[Pesan]" readonly>
            </div>
        </div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary" id="saveVoucherBtn">Simpan Perubahan</button>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tableBody = document.querySelector('#voucherDetailsTable tbody');
        const addRowBtn = document.getElementById('addRowBtn');
        const removeRowBtn = document.querySelector('.removeRowBtn');

        function calculateTotalsAndValidate() {
            let totalDebit = 0;
            let totalCredit = 0;

            const debitInputs = document.querySelectorAll('.debitInput');
            debitInputs.forEach(input => {
                totalDebit += parseFloat(input.value) || 0;
            });

            const creditInputs = document.querySelectorAll('.creditInput');
            creditInputs.forEach(input => {
                totalCredit += parseFloat(input.value) || 0;
            });

            document.getElementById('totalDebit').value = totalDebit;
            document.getElementById('totalCredit').value = totalCredit;

            const saveButton = document.getElementById('saveVoucherBtn');
            if (totalDebit !== totalCredit) {
                document.getElementById('validation').value = "Total nilai debit tidak sama dengan total nilai kredit. Mohon koreksi transaksi Anda untuk memastikan keseimbangan keuangan.";
                saveButton.disabled = true;
                return false;
            } else {
                document.getElementById('validation').value = "Voucher berhasil divalidasi. Voucher Anda masih berlaku dan dapat digunakan.";
                saveButton.disabled = false;
                return true;
            }
        }

        function attachRowEventListeners(row, index) {
            const accountCodeSelect = row.querySelector('.accountCodeSelect');
            const accountNameInput = row.querySelector('.accountName');
            const inputFields = row.querySelectorAll('.debitInput, .creditInput');

            // Account Code Select Event Listener
            if (accountCodeSelect) {
                accountCodeSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    accountNameInput.value = selectedOption.value === "" ? "" : selectedOption.text.split(' - ')[1] || "";
                });
                accountCodeSelect.name = `details[${index}][account_code]`;
            }

            // Input Fields (Debit/Credit) Event Listeners and Name Update
            inputFields.forEach(input => {
                input.addEventListener('input', calculateTotalsAndValidate);
                const fieldName = input.name.split('[')[2].slice(0, -1); // Extract field name (debit or credit)
                input.name = `details[${index}][${fieldName}]`;
            });

            // Account Name Input Name Update
            if (accountNameInput) {
                accountNameInput.name = `details[${index}][account_name]`;
            }
        }

        calculateTotalsAndValidate();
        attachRowEventListeners(tableBody.querySelector('tr'), 0);

        addRowBtn.addEventListener('click', function() {
            const newRow = tableBody.querySelector('tr').cloneNode(true);
            const selects = newRow.querySelectorAll('select');
            const inputs = newRow.querySelectorAll('input');

            selects.forEach(select => select.value = '');
            inputs.forEach(input => input.value = '');

            const newIndex = tableBody.querySelectorAll('tr').length;

            // Update name attributes with the new index
            selects.forEach(select => select.name = select.name.replace(/\[\d+\]/, `[${newIndex}]`));
            inputs.forEach(input => input.name = input.name.replace(/\[\d+\]/, `[${newIndex}]`));

            tableBody.appendChild(newRow);
            attachRowEventListeners(newRow, newIndex);
            calculateTotalsAndValidate();
        });

        removeRowBtn.addEventListener('click', function() {
            const rows = tableBody.querySelectorAll('tr');
            if (rows.length > 1) {
                tableBody.removeChild(rows[rows.length - 1]);
                calculateTotalsAndValidate();
            } else {
                alert("Tidak dapat menghapus baris terakhir.");
            }
        });

        const inputFields = document.querySelectorAll('.debitInput, .creditInput');
        inputFields.forEach(input => {
            input.addEventListener('change', calculateTotalsAndValidate);
        });
    });

    function updateVoucherDay() {
        const voucherDate = document.getElementById('voucherDate').value;
        if (voucherDate) {
            const date = new Date(voucherDate);
            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const dayName = days[date.getDay()];
            document.getElementById('voucherDay').value = dayName;
        } else {
            document.getElementById('voucherDay').value = "";
        }
    }
    document.getElementById('voucherDate').addEventListener('change', updateVoucherDay);

    function setTodayVoucherDate() {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        const formattedDate = `${year}-${month}-${day}`;
        document.getElementById('voucherDate').value = formattedDate;
        updateVoucherDay();
    }
    setTodayVoucherDate();
</script>

@endsection