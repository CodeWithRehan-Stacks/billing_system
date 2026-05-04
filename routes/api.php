<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\FeeInvoiceController;
use App\Http\Controllers\FeePaymentController;
use App\Http\Controllers\ReceiptController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('students', StudentController::class);
Route::apiResource('invoices', FeeInvoiceController::class)->only(['index', 'show']);
Route::apiResource('payments', FeePaymentController::class)->only(['index', 'store']);
Route::get('payments/{payment}/receipt', [ReceiptController::class, 'generateReceipt']);