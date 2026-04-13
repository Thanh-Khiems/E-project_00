@extends('layouts.app')

@section('title', 'Verification Code | MediConnect')

@section('content')
    @include('components.auth.recovery-card', ['variant' => 'otp', 'email' => $email, 'backRoute' => route('password.request')])
@endsection
