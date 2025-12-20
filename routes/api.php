<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('incoming-part-reports', \App\Http\Controllers\Api\IncomingPartReportController::class);

Route::apiResource('suppliers', \App\Http\Controllers\Api\SupplierController::class);
Route::get('suppliers-dropdown', [\App\Http\Controllers\Api\SupplierController::class, 'supplierDropdown']);

Route::apiResource('examiners', \App\Http\Controllers\Api\ExaminerController::class);
Route::get('examiners-dropdown', [\App\Http\Controllers\Api\ExaminerController::class, 'examinerDropdown']);

Route::apiResource('itemncs', \App\Http\Controllers\Api\ItemncController::class);
Route::get('itemncs-dropdown', [\App\Http\Controllers\Api\ItemncController::class, 'itemncDropdown']);


Route::any('{any?}', function () {
    return response()->json([
        'message' => 'Route not found.'
    ], 404);
})->where('any', '.*');
