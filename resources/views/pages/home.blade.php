@extends('layouts.app')

@section('title', 'MediConnect')

@section('content')
    @include('components.hero')
    @include('components.about-section')
    @include('components.doctors-section')
    @include('components.services-section')
    @include('components.news-section')
    @include('components.feedback-section')
@endsection