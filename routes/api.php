<?php
use App\Http\Controllers\Api\{HouseholdController, PickupController, PaymentController};


use Illuminate\Support\Facades\Route;

Route::apiResource('households', HouseholdController::class);

Route::get('pickups', [PickupController::class, 'index']);
Route::post('pickups', [PickupController::class, 'store']);
Route::put('pickups/{id}/schedule', [PickupController::class, 'schedule']);
Route::put('pickups/{id}/complete', [PickupController::class, 'complete']);
Route::put('pickups/{id}/cancel', [PickupController::class, 'cancel']);

Route::get('payments', [PaymentController::class, 'index']);
Route::post('payments', [PaymentController::class, 'store']);
Route::put('payments/{id}/confirm', [PaymentController::class, 'confirm']);

#Route::get('reports/waste-summary', [ReportController::class, 'wasteSummary']);
#Route::get('reports/payment-summary', [ReportController::class, 'paymentSummary']);
Route::get('reports/households/{id}/history', [ReportController::class, 'householdHistory']);
