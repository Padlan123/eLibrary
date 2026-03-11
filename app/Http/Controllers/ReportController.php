<?php

namespace App\Http\Controllers;

use App\Models\Package;
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
        $packageId = $request->get('package_id', []);
        $status    = $request->get('status', ['pending', 'completed', 'rejected']);
        $search    = $request->get('search', '');

        $transactions = SubscriptionTransaction::with('package', 'member')
            ->when($packageId, fn($q) => $q->whereIn('package_id', $packageId))
            ->when($status, fn($q) => $q->whereIn('status', $status))
            ->when($search, fn($q) => $q->whereHas(
                'member',
                fn($q) =>
                $q->where('username', 'like', '%' . $search . '%')
            ))
            ->whereBetween('paid_date', [
                Carbon::parse($from)->startOfDay(),
                Carbon::parse($to)->endOfDay(),
            ])
            ->orderBy('paid_date')
            ->get();

        $total = SubscriptionTransaction::with('package')
            ->when($packageId, function ($query) use ($packageId) {
                $query->whereIn('package_id', $packageId);
            })
            ->when($status, function ($query) use ($status) {
                $query->whereIn('status', $status);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('member', function ($q) use ($search) {
                    $q->where('username', 'like', '%' . $search . '%');
                });
            })
            ->when($from && $to, function ($query) use ($from, $to) {
                $query->whereBetween('paid_date', [Carbon::parse($from)->startOfDay(), Carbon::parse($to)->endOfDay()]);
            })
            ->join('packages', 'subscription_transactions.package_id', '=', 'packages.id')
            ->sum('packages.price');

        $packageNames = !empty($packageId)
            ? Package::whereIn('id', $packageId)->pluck('name')->toArray()
            : [];

        $periode = Carbon::parse($from)->format('d/m/y')
            . ' s/d '
            . Carbon::parse($to)->format('d/m/y');

        $pdf = Pdf::loadView('reports.transactions', compact('transactions', 'periode', 'total', 'packageNames', 'status'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("laporan-penjualan-{$from}-{$to}.pdf");
    }
}
