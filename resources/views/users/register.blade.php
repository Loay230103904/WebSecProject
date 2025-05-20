@extends('layouts.master')

@section('title', 'Users List')

@section('content')

<form action="{{ route('do_register') }}" method="post">
    {{ csrf_field() }}

    <div class="form-group">
        @foreach($errors->all() as $error)        
        <div class="alert alert-danger">
            <strong>Error</strong> {{ $error }}
        </div>
        @break
        @endforeach
    </div>

    <div class="form-group mb-2">
        <label for="name" class="form-label">Name:</label>
        <input type="text" class="form-control" placeholder="Name" name="name" required>
    </div>

    <div class="form-group mb-2">
        <label for="email" class="form-label">Email:</label>
        <input type="email" class="form-control" placeholder="Email" name="email" required>
    </div>

    <div class="form-group mb-2">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" placeholder="Password" name="password" required>
    </div>

    <div class="form-group mb-2">
        <label for="password_confirmation" class="form-label">Password Confirmation:</label>
        <input type="password" class="form-control" placeholder="Password Confirmation" name="password_confirmation" required>
    </div>

    <div class="form-group mb-2">
        <label for="address" class="form-label">Address:</label>
        <input type="text" class="form-control" placeholder="Address" name="address" required>
    </div>

    <div class="form-group mb-2">
        <label for="phone" class="form-label">Phone:</label>
        <input type="text" class="form-control" placeholder="Phone" name="phone" required>
    </div>

    <div class="form-group mb-2">
        <button type="submit" class="btn btn-primary">Register</button>
    </div>
</form>

@endsection
