<?php

use Illuminate\Support\Facades\Route;

// --- Import namespaced controllers explicitly ---
use App\Http\Controllers\Api\HouseholdController;
use App\Http\Controllers\Api\PickupController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are stateless and automatically use the "api" middleware group.
| By default they are prefixed with /api (configured in bootstrap/app.php).
| Ref: Laravel routing docs.
*/

// ---------------------
// HOUSEHOLDS (Resource)
// ---------------------
Route::apiResource('households', HouseholdController::class);
// POST   /api/households        -> store (Create household)
// GET    /api/households        -> index (List; support search/filter/paginate)
// GET    /api/households/{id}   -> show
// PUT    /api/households/{id}   -> update
// DELETE /api/households/{id}   -> destroy

// ---------------------
// PICKUPS (POST + actions)
// ---------------------
Route::get('pickups',           [PickupController::class, 'index'])->name('pickups.index');

// Create new pickup (POST)
Route::post('pickups',          [PickupController::class, 'store'])->name('pickups.store');

// Actions (PUT)
// - schedule (requires status=pending; electronic needs safety_check=true)
Route::put('pickups/{id}/schedule', [PickupController::class, 'schedule'])->name('pickups.schedule');

// - complete (auto-creates payment by type: organic/plastic/paper=50k; electronic=100k)
Route::put('pickups/{id}/complete', [PickupController::class, 'complete'])->name('pickups.complete');

// - cancel
Route::put('pickups/{id}/cancel',   [PickupController::class, 'cancel'])->name('pickups.cancel');

// ---------------------
// PAYMENTS (POST + confirm)
// ---------------------
Route::get('payments',              [PaymentController::class, 'index'])->name('payments.index');

// Create payment (linked to household)
Route::post('payments',             [PaymentController::class, 'store'])->name('payments.store');

// Confirm payment (mark as paid)
Route::put('payments/{id}/confirm', [PaymentController::class, 'confirm'])->name('payments.confirm');

// ---------------------
// REPORTS (GET)
// ---------------------
Route::get('reports/waste-summary',                 [ReportController::class, 'wasteSummary'])->name('reports.waste-summary');
Route::get('reports/payment-summary',               [ReportController::class, 'paymentSummary'])->name('reports.payment-summary');
Route::get('reports/households/{id}/history',       [ReportController::class, 'householdHistory'])->name('reports.household-history');

// ---------------------
// Optional: Fallback to JSON for unknown API routes
// ---------------------
Route::fallback(function () {
    return response()->json([
        'error' => ['code' => 404, 'message' => 'API resource not found'],
    ], 404);
});
