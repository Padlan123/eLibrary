<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function download(SubscriptionTransaction $invoice)
    {
        // Pastikan hanya pemilik invoice yang bisa download
        abort_if($invoice->member_id !== Auth::id(), 403);

        $pdf = Pdf::loadView('invoice.pdf', compact('invoice'));

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }
}
