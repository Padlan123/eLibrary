@extends('reports.layouts.pdf')

@section('title', 'Laporan Penjualan')
@section('subtitle', 'Periode: ' . $periode)

@section('content')
    <h3 class="mb-4">Data Berlangganan</h3>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>No. Invoice</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->paid_date->format('d/m/Y') }}</td>
                    <td>{{ $item->invoice_number }}</td>
                    <td>{{ $item->member->username }}</td>
                    <td>Rp {{ number_format($item->package->price, 0, ',', '.') }}</td>
                    <td>
                        <span class="{{ $item->status === 'completed' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
