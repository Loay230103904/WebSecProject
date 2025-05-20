@extends('layouts.master')

@section('title', 'Bought Products')

@section('content')
<div class="container mt-4">
    <h2 class="mb-3">Bought Products</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @canany(['delivery_operations', 'edit_products'])

    <div class="mb-3">
        <a href="{{ route('bought_products') }}" class="btn btn-secondary btn-sm">All</a>
        <a href="{{ route('bought_products', ['state' => 'delivered']) }}" class="btn btn-success btn-sm">Delivered</a>
        <a href="{{ route('bought_products', ['state' => 'refused']) }}" class="btn btn-danger btn-sm">Refused</a>
    </div>
    @endcan

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Price</th>
                <th>Address</th>
                <th>Purchase Date</th>
                @canany(['delivery_operations', 'edit_products'])
                    <th>User Name</th>
                    <th>Phone Number</th>
                @endcan
                <th>State</th>
                @can('delivery_operations')
                    <th>Actions</th> {{-- Buttons only for delivery role --}}
                @endcan
            </tr>
            
        </thead>
        <tbody>
            @foreach($boughtProducts as $boughtProduct)
            <tr>
                <td>{{ $boughtProduct->product?->name ?? 'Delete Product' }}</td>
                <td>{{ $boughtProduct->product?->price ?? 'N/A' }}</td>
                <td>{{ $boughtProduct->user?->address ?? 'No address' }}</td>
                <td>{{ $boughtProduct->created_at }}</td>
                @canany(['delivery_operations', 'edit_products'])
                    <td>{{ $boughtProduct->user->name ?? 'Deleted User'}}</td>
                    <td>{{ $boughtProduct->user->phone ?? 'No Phone'}}</td>

                @endcan
                <td>{{ $boughtProduct->state ?? 'Pending' }}</td>
                @can('delivery_operations')
                <td>
                    @if($boughtProduct->state === 'pending')
                        <form action="{{ route('product_delivered', $boughtProduct->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Delivered</button>
                        </form>

                        <form action="{{ route('product_refused', $boughtProduct->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Refused</button>
                        </form>
                    @else
                        <span class="badge 
                            {{ $boughtProduct->state === 'delivered' ? 'bg-success' : '' }}
                            {{ $boughtProduct->state === 'refused' ? 'bg-danger' : '' }}">
                            {{ ucfirst($boughtProduct->state) }}
                        </span>
                    @endif
                </td>
                @endcan
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
