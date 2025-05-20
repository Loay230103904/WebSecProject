@extends('layouts.master')
@section('title', 'Purchase History')
@section('content')

<div class="container mt-4">
    <h2 class="text-center mb-4">My Purchase History</h2>

    @if($purchases->isEmpty())
        <div class="alert alert-info text-center">
            <i class="fas fa-shopping-cart"></i> You haven't purchased any products yet.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Date</th>
                        <th>status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $index => $purchase)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <strong>{{ $purchase->product->name ?? 'Unknown Product' }}</strong>
                            </td>
                            <td>${{ number_format($purchase->price, 2) }}</td>
                            <td>{{ $purchase->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
                @if(auth()->user()->can('track_delivery'))
<form action="{{ route('purchase.status.add', $purchase->id) }}" method="POST">
    @csrf
    <input type="text" name="status" placeholder="Add Status" required>
    <button type="submit" class="btn btn-warning">Set Status</button>
</form>
@endif

            </table>
        </div>
    @endif
</div>

@endsection
