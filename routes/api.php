<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\FeeInvoiceController;
use App\Http\Controllers\ReceiptController;

// Auth Routes
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    // Auth User
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Students API
    Route::apiResource('students', StudentController::class);

    // Invoices API
    Route::apiResource('invoices', FeeInvoiceController::class);

    // PDF Generation API
    Route::get('/invoices/{invoice}/pdf', [ReceiptController::class, 'generateReceipt']);
});