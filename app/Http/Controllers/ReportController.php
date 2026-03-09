<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionTransaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function download(Request $request)
    {
        $from   = $request->get('from',  now()->startOfMonth()->format('Y-m-d'));
        $to = $request->get('to', now()->format('Y-m-d'));

        $transactions = SubscriptionTransaction::whereBetween('paid_date', [$from, $to])
            ->orderBy('paid_date')
            ->get();

        $periode = Carbon::parse($from)->format('d/m/y')
            . ' s/d '
            . Carbon::parse($to)->format('d/m/y');

        $pdf = Pdf::loadView('reports.transactions', compact('transactions', 'periode'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("laporan-penjualan-{$from}-{$to}.pdf");
    }
}
