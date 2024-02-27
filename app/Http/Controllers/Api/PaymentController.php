<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\PaymentService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConfirmPaymentRequest;
use App\Http\Requests\StorePaymentRequest;
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
     * Set the payment as paid in storage.
     */
    public function confirm(ConfirmPaymentRequest $request, Payment $payment, PaymentService $paymentService)
    {
        if (! $paymentService->confirm($payment)) {
            return $this->fail();
        }

        return response(status: Response::HTTP_NO_CONTENT);
    }

    /**
     * Set the payment as canceled in storage.
     */
    public function cancel(Payment $payment, PaymentService $paymentService)
    {
        if (! $paymentService->cancel($payment)) {
            return $this->fail();
        }

        return response(status: Response::HTTP_NO_CONTENT);
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
