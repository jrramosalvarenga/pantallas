<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $payments = Payment::with('client', 'contract')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->from, fn ($q, $d) => $q->where('due_date', '>=', $d))
            ->when($request->to, fn ($q, $d) => $q->where('due_date', '<=', $d))
            ->when($request->search, fn ($q, $s) => $q->whereHas('client', fn ($c) => $c->where('business_name', 'ilike', "%{$s}%")))
            ->orderByRaw("CASE status WHEN 'vencido' THEN 1 WHEN 'pendiente' THEN 2 WHEN 'parcial' THEN 3 WHEN 'pagado' THEN 4 ELSE 5 END")
            ->orderBy('due_date')
            ->paginate(20);

        $stats = [
            'pendiente' => Payment::where('status', 'pendiente')->sum('amount'),
            'vencido' => Payment::where('status', 'pendiente')->where('due_date', '<', now())->sum('amount'),
            'cobrado_mes' => Payment::where('status', 'pagado')
                ->whereMonth('paid_date', now()->month)
                ->whereYear('paid_date', now()->year)
                ->sum('amount_paid'),
        ];

        return view('payments.index', compact('payments', 'stats'));
    }

    public function show(Payment $payment)
    {
        $payment->load('client', 'contract.plan');

        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        $payment->load('client', 'contract');

        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'due_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Pago actualizado exitosamente.');
    }

    public function registerPayment(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'reference' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $newAmountPaid = $payment->amount_paid + $validated['amount_paid'];
        $status = $newAmountPaid >= $payment->amount ? 'pagado' : 'parcial';

        $payment->update([
            'amount_paid' => $newAmountPaid,
            'paid_date' => now(),
            'status' => $status,
            'payment_method' => $validated['payment_method'],
            'reference' => $validated['reference'],
            'notes' => $validated['notes'],
        ]);

        Income::create([
            'contract_id' => $payment->contract_id,
            'payment_id' => $payment->id,
            'client_id' => $payment->client_id,
            'description' => 'Pago de contrato '.($payment->contract->contract_number ?? ''),
            'amount' => $validated['amount_paid'],
            'date' => now(),
            'reference' => $validated['reference'],
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Pago registrado por L '.number_format($validated['amount_paid'], 2));
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('payments.index')
            ->with('success', 'Pago eliminado.');
    }
}
