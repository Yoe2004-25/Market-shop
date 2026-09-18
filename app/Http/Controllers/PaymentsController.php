<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentsRequest;
use App\Http\Requests\UpdatePaymentsRequest;
use App\Models\Orders;
use App\Models\Payments;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentsController extends Controller
{
    public function __construct( private PaymentService $service) {}

    public function index(Request $request): View
    {
        $payments = Payments::with(['order'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);
        return view('payments.index', compact('payments'));
    }

    public function create(Request $request): View
    {
        $orders = Orders::with('user')->latest()->take(50)->get();

        return view('payments.create', compact('orders'));
    }
    public function store(StorePaymentsRequest $request): RedirectResponse
    {
        try {
            $order   = Orders::findOrFail($request->order_id);
            $payment = $this->service->createPayment($order, $request->payment_method);
            return redirect()->route('payments.show', $payment)->with('success', 'Payment created successfully.');
        } catch (\Throwable $th) {
            return back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function show(Payments $payment): View
    {
        $payment->load('order.user');

        return view('payments.show', compact('payment'));
    }

    public function edit(Payments $payment): View
    {
        return view('payments.edit', compact('payment'));
    }

    public function update(UpdatePaymentsRequest $request, Payments $payment): RedirectResponse
    {
        $payment->update($request->validated());

        return redirect() ->route('payments.index') ->with('success', 'Payment updated.');
    }

    /** DELETE /payments/{payment} */
    public function destroy(Payments $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()
            ->route('payments.index')
            ->with('success', 'Payment deleted.');
    }

    public function confirmCash(Payments $payment): RedirectResponse
    {
        try {
            $this->service->confirmCashPayment($payment);
            return back()->with('success', 'Cash payment confirmed.');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    public function refund(Payments $payment): RedirectResponse
    {
        $this->service->refund($payment);

        return back()->with('success', 'Payment refunded.');
    }
}