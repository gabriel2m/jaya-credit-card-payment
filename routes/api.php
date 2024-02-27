<?php

use App\Http\Controllers\Api\PaymentController;
use App\Http\Middleware\ClientOrAuthApi;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(ClientOrAuthApi::class)->group(function () {
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::missing(
        fn () => response()->json([
            'message' => 'Bankslip not found with the specified id',
        ], Response::HTTP_NOT_FOUND)
    )->group(function () {
        Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
        Route::patch('payments/{payment}', [PaymentController::class, 'confirm'])->name('payments.confirm');
    });
});
