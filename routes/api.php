<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('incoming-part-reports', \App\Http\Controllers\Api\IncomingPartReportController::class);
Route::apiResource('suppliers', \App\Http\Controllers\Api\SupplierController::class);
Route::apiResource('examiners', \App\Http\Controllers\Api\ExaminerController::class);
Route::apiResource('itemncs', \App\Http\Controllers\Api\ItemncController::class);
