@extends('reports.layouts.pdf')

@section('title', 'Laporan Penjualan')
@section('subtitle', 'Periode: ' . $periode)

@section('content')
    <h3 class="mb-4">Data Berlangganan</h3>
    <div class="filter-info">
        <p><strong>Periode</strong>: {{ $periode }}</p>
        @if (!empty($status))
            <p><strong>Status</strong>: {{ implode(', ', array_map('ucfirst', $status)) }}</p>
        @endif
        @if (!empty($packageNames))
            <p><strong>Paket</strong>: {{ implode(', ', $packageNames) }}</p>
        @endif
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>No. Invoice</th>
                <th>Pelanggan</th>
                <th>Paket</th>
                <th>Status</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->paid_date->format('d/m/Y') }}</td>
                    <td>{{ $item->invoice_number }}</td>
                    <td>{{ $item->member->username }}</td>
                    <td>{{ $item->package->name }}</td>
                    <td>
                        <span class="{{ $item->status === 'completed' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td>Rp {{ number_format($item->package->price, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <td colspan="6" style="text-align:right"><strong>Total</strong></td>
            <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tfoot>
    </table>
@endsection
