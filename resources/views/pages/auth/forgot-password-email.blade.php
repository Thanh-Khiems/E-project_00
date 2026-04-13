@extends('layouts.app')

@section('title', 'Forgot Password | MediConnect')

@section('content')
    @include('components.auth.recovery-card', ['variant' => 'email'])
@endsection
