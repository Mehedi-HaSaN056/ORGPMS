@extends('layouts.auth')
@section('title','Forgot Password')
@section('content')
<div class="auth-page"><div class="auth-card fade-in-up">
<div class="auth-logo"><div class="logo-icon"><i class="bi bi-buildings-fill"></i></div><h1>Reset Password</h1><p>Enter your email to receive a reset link.</p></div>
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
<form method="POST" action="{{ route('password.email') }}">@csrf
<div class="mb-3"><label class="form-label">Email Address</label><input type="email" name="email" class="form-control" required autofocus></div>
<button type="submit" class="btn btn-primary w-100">Send Reset Link</button>
<div class="text-center mt-3"><a href="{{ route('login') }}" class="small">← Back to Login</a></div>
</form>
</div></div>
@endsection
