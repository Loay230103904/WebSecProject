
@extends('layouts.master')

@section('title', 'Stock Operations')

@section('content')
<div class="container mt-4">
    <h2>Low Stock Products</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Current Stock</th>
                <th>Increase Stock</th>
            </tr>
        </thead>
        <tbody>
            @forelse($lowStockProducts as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->stock }}</td>
                <td>
                    <form action="{{ route('increase_stock', $product->id) }}" method="POST" class="d-flex">
                        @csrf
                        <input type="number" name="stock" class="form-control me-2" min="1" required placeholder="Amount">
                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3">No low-stock products.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
