@extends('reports.layouts.pdf')

@section('title', 'Laporan Buku')

@section('content')
    <h3 class="mb-4">Data Buku</h3>
    <div class="filter-info">
        <p><strong>Kategori</strong>:
            {{ $categoryName }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Penerbit</th>
                <th>Penulis</th>
            </tr>
        </thead>
        <tbody>
            @forelse($book as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->categories->pluck('name')->implode(', ') }}</td>
                    <td>{{ $item->publisher }}</td>
                    <td>{{ $item->author }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
