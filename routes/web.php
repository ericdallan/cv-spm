<?php

use App\Http\Controllers\AccountCodeController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\generalLedgerController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

//LandingPage Before Login
Route::get('/', [LoginController::class, 'login_page'])->name('login_page');
//LandingPage Admin
Route::get('/dashboard', [AdminController::class, 'dashboard_page'])->name('dashboard_page');
//Pegawai 
Route::get('/employee_page', [EmployeeController::class, 'employee_page'])->name('employee_page');
//Voucher
Route::get('/voucher_page', [VoucherController::class, 'voucher_page'])->name('voucher_page');
Route::post('/voucher_form', [VoucherController::class, 'voucher_form'])->name('voucher_form');
Route::get('/voucher/{id}', [VoucherController::class, 'voucher_detail'])->name('voucher_detail');
Route::get('/voucher/edit/{id}', [VoucherController::class, 'voucher_edit'])->name('voucher_edit');
Route::delete('/voucher/{id}', [VoucherController::class, 'voucher_delete'])->name('voucher.delete');
Route::put('/voucher/update/{id}', [VoucherController::class, 'voucher_update'])->name('voucher.update');
Route::get('/voucher/{id}/pdf', [VoucherController::class, 'generatePdf'])->name('voucher_pdf');
//Buku Besar
Route::get('/generalLedger_page', [generalLedgerController::class, 'generalledger_page'])->name('generalledger_page');
Route::get('/general-ledger/print', [ExportController::class, 'generalledger_print'])->name('generalledger_print');
//Neraca Saldo
Route::get('/trialBalance_page', [generalLedgerController::class, 'trialBalance_page'])->name('trialBalance_page');
Route::get('/export/neraca-saldo', [ExportController::class, 'exportNeracaSaldo'])->name('export_neraca_saldo');
//Laba Rugi
Route::get('/incomeStatement_page', [generalLedgerController::class, 'incomeStatement_page'])->name('incomeStatement_page');
Route::get('/export/income-statment', [ExportController::class, 'exportIncomeStatement'])->name('export_income_statement');
//Neraca
Route::get('/balanceSheet_page', [generalLedgerController::class, 'balanceSheet_page'])->name('balanceSheet_page');
Route::get('/export/balance-sheet', [ExportController::class, 'exportBalanceSheet'])->name('export_BalanceSheet');
//AccountCode
Route::get('/account_page', [AccountCodeController::class, 'account_page'])->name('account_page');
Route::post('/account/create', [AccountCodeController::class, 'create_account'])->name('account_create');
Route::get('/account_page/edit/{accountCode}', [AccountCodeController::class, 'edit_account'])->name('accoundeCode_edit');
Route::put('/account_update/{accountCode}', [AccountCodeController::class, 'update_account'])->name('account_update');
//perusahaan
Route::get('/company_page', [CompanyController::class, 'company_page'])->name('company_page');
Route::get('/company_page/edit', [CompanyController::class, 'edit'])->name('company.edit');
Route::post('/company_page/update', [CompanyController::class, 'update'])->name('company.update');