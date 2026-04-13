@extends('layouts.app')

@section('title', 'Create New Password | MediConnect')

@section('content')
    @include('components.auth.recovery-card', ['variant' => 'reset', 'email' => $email, 'backRoute' => route('password.otp')])
@endsection
