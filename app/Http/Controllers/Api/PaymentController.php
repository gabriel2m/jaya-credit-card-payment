<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\PaymentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Models\Payment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, PaymentService $paymentService)
    {
        if (empty($request->getContent())) {
            return response()->json([
                'message' => 'payment not provided in the request body',
            ], Response::HTTP_BAD_REQUEST);
        }

        $payment = $paymentService->create(
            app(StorePaymentRequest::class)->validated()
        );

        return response()->json(
            $payment->setVisible([
                'id',
                'created_at',
            ]),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentRequest $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
    }
}
