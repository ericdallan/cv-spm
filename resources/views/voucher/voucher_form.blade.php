<div class="modal fade" id="voucherModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="voucherModalLabel">Buat Voucher Baru</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <form id="voucherForm" method="POST" action="/voucher_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="container">
                        <h2 class="text-center">Formulir Voucher</h2>
                        <div class="row mb-3">
                            <label for="voucherNumber" class="col-sm-3 col-form-label">Nomor Voucher:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="voucherNumber" name="voucher_number" value="[Otomatis Dihasilkan]" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="companyName" class="col-sm-3 col-form-label">Nama Perusahaan:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="companyName" name="companyName" value="CV. Sari Pratama Mandiri" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="voucherType" class="col-sm-3 col-form-label">Jenis Voucher:</label>
                            <div class="col-sm-3">
                                <select class="form-select" id="voucherType" name="voucher_type">
                                    <option value="JV">JV</option>
                                    <option value="MP">MP</option>
                                    <option value="MG">MG</option>
                                    <option value="CG">CG</option>
                                </select>
                            </div>
                            <label for="voucherDate" class="col-sm-2 col-form-label">Tanggal:</label>
                            <div class="col-sm-2">
                                <input type="date" class="form-control" id="voucherDate" name="voucher_date">
                            </div>
                            <div class="col-sm-2">
                                <input type="text" class="form-control" id="voucherDay" name="voucher_day" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="preparedBy" class="col-sm-3 col-form-label">Disiapkan Oleh:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="preparedBy" name="prepared_by">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="approvedBy" class="col-sm-3 col-form-label">Disetujui Oleh (Opsional):</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="approvedBy" name="approved_by">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="description" class="col-sm-3 col-form-label">Deskripsi</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="description" name="description">
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
                                    <tr>
                                        <td>
                                            <select class="form-select accountCodeSelect" name="details[0][account_code]">
                                                <option value="">Pilih Kode Akun</option>
                                                @foreach($accounts as $account)
                                                <option value="{{ $account->account_code }}">
                                                    {{ $account->account_code }} - {{ $account->account_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control accountName" name="details[0][account_name]" readonly></td>
                                        <td><input type="number" min="0" class="form-control debitInput" name="details[0][debit]"></td>
                                        <td><input type="number" min="0" class="form-control creditInput" name="details[0][credit]"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" id="addRowBtn" class="btn btn-primary">Tambah Baris</button>
                            <button type="button" class="btn btn-danger removeRowBtn">Hapus</button>
                        </div>
                        <div class="row mb-3">
                            <label for="totalDebit" class="col-sm-3 col-form-label">Total Debit:</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="totalDebit" name="total_debit" value="[Dihitung]" readonly>
                            </div>
                            <label for="totalCredit" class="col-sm-3 col-form-label">Total Kredit:</label>
                            <div class="col-sm-3">
                                <input type="text" class="form-control" id="totalCredit" name="total_credit" value="[Dihitung]" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="validation" class="col-sm-3 col-form-label">Validasi:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="validation" name="validation" value="[Pesan]" readonly>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary" id="saveVoucherBtn">Simpan Voucher</button>
                </div>
            </form>
        </div>
    </div>
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
                const debitInput = row.querySelector('.debitInput');
                const creditInput = row.querySelector('.creditInput');

                // Account Code Select Event Listener
                if (accountCodeSelect) {
                    accountCodeSelect.addEventListener('change', function() {
                        const selectedOption = this.options[this.selectedIndex];
                        accountNameInput.value = selectedOption.value === "" ? "" : selectedOption.text.split(' - ')[1] || "";
                    });
                    accountCodeSelect.name = `details[${index}][account_code]`;
                }

                // Debit Input Event Listener
                if (debitInput) {
                    debitInput.addEventListener('input', function() {
                        if (this.value) {
                            creditInput.value = '';
                            creditInput.disabled = true;
                        } else {
                            creditInput.disabled = false;
                        }
                        calculateTotalsAndValidate();
                    });
                    debitInput.name = `details[${index}][debit]`;
                }

                // Credit Input Event Listener
                if (creditInput) {
                    creditInput.addEventListener('input', function() {
                        if (this.value) {
                            debitInput.value = '';
                            debitInput.disabled = true;
                        } else {
                            debitInput.disabled = false;
                        }
                        calculateTotalsAndValidate();
                    });
                    creditInput.name = `details[${index}][credit]`;
                }

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
                inputs.forEach(input => {
                    input.value = '';
                    input.disabled = false; // Pastikan input diaktifkan saat baris baru ditambahkan
                });

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
</div>