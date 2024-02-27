<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\PaymentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\UpdatePaymentRequest;
use App\Http\Resources\PaymentResource;
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
        return PaymentResource::collection(
            Payment::paginate()
        );
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

        if (! $payment->exists) {
            return $this->fail();
        }

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
        return PaymentResource::make($payment);
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

    /**
     * Fail response.
     */
    private function fail()
    {
        return response()->json([
            'message' => 'something went wrong',
        ], Response::HTTP_BAD_REQUEST);
    }
}
