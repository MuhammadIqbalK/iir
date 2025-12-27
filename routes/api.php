<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Public Routes - Login (no auth required)
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes - Require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Incoming Part Reports
    Route::apiResource('incoming-part-reports', \App\Http\Controllers\Api\IncomingPartReportController::class);
    Route::get('ipr-excel-global', [\App\Http\Controllers\Api\IncomingPartReportController::class, 'exportType1']);
    Route::get('ipr-excel-recap', [\App\Http\Controllers\Api\IncomingPartReportController::class, 'exportType2']);

    // Suppliers
    Route::apiResource('suppliers', \App\Http\Controllers\Api\SupplierController::class);
    Route::get('suppliers-dropdown', [\App\Http\Controllers\Api\SupplierController::class, 'supplierDropdown']);

    // Examiners
    Route::apiResource('examiners', \App\Http\Controllers\Api\ExaminerController::class);
    Route::get('examiners-dropdown', [\App\Http\Controllers\Api\ExaminerController::class, 'examinerDropdown']);

    // Items
    Route::apiResource('itemncs', \App\Http\Controllers\Api\ItemncController::class);
    Route::get('itemncs-dropdown', [\App\Http\Controllers\Api\ItemncController::class, 'itemncDropdown']);
    Route::get('itemncs-category-dropdown', [\App\Http\Controllers\Api\ItemncController::class, 'itemncCategoryDropdown']);
});

// 404 Handler
Route::any('{any?}', function () {
    return response()->json([
        'message' => 'Route not found.'
    ], 404);
})->where('any', '.*');
