@extends('layouts.app')

@section('title', 'Doctor')

@section('content')

{{-- SECTION: HERO & BREADCRUMB --}}
<section class="doctors-hero bg-white border-bottom">
    {{-- 1. TITLE & DESCRIPTION SECTION (HERO) --}}
    <div class="container py-5 mt-4">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8 col-md-10">
                <h1 class="display-5 fw-bold text-dark mb-3">Doctors</h1>
                <p class="text-muted fs-6 lh-lg mx-auto" style="max-width: 750px;">
                    Medical research continues to evolve, providing new insights into patient care and innovative
                    treatment methods. Our team of dedicated doctors is committed to delivering the highest quality
                    healthcare services to ensure your well-being.
                </p>
            </div>
        </div>
    </div>
</section>

{{-- BREADCRUMB --}}
<section class="bg-[#ececec]">
    <div class="max-w-7xl mx-auto px-6 py-4">
        <span class="text-blue-600 text-sm">Home</span>
        <span class="text-gray-500 text-sm"> / Doctors</span>
    </div>
</section>

<style>
    .doctors-hero h1 {
        letter-spacing: -0.5px;
    }
</style>

{{-- DOCTORS GRID --}}
<section class="bg-[#f7f7f7] min-vh-100 d-flex align-items-center">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 ">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 justify-content-center items-start ">

            @include('components.doctor-card', [
                'name' => 'Dr. Bennett',
                'specialty' => 'Cardiologist',
                'description' => 'Dr. Bennett believes that a healthy heart is the engine of a fulfilling life.',
                'experience' => '15+ ',
                'hospital' => 'Cardiology ',
            ])

            @include('components.doctor-card', [
                'name' => 'Dr. Sophia Miller',
                'specialty' => 'Pediatrics',
                'description' => 'Specializing in newborn care and child development.',
                'experience' => '12+ ',
                'hospital' => 'Children Hospital',
            ])

            @include('components.doctor-card', [
                'name' => 'Dr. Julian Thorne',
                'specialty' => 'Dermatology',
                'description' => 'Expert in advanced skincare and dermatology treatment.',
                'experience' => '10+',
                'hospital' => 'Skin Wellness',
            ])

            @include('components.doctor-card', [
                'name' => 'Dr. Elena Vance',
                'specialty' => 'Neurology',
                'description' => 'Dedicated to treating brain and nervous system disorders.',
                'experience' => '14+',
                'hospital' => 'Neurology Institute',
            ])

            @include('components.doctor-card', [
                'name' => 'Dr. Marcus Sterling',
                'specialty' => 'Orthopedics',
                'description' => 'Expert in sports medicine and joint reconstruction.',
                'experience' => '11+',
                'hospital' => 'Bone & Joint Center',
            ])

            @include('components.doctor-card', [
                'name' => 'Dr. Isabella Ross',
                'specialty' => 'Oncology',
                'description' => 'Providing compassionate cancer care and recovery support.',
                'experience' => '13+',
                'hospital' => 'Cancer Center',
            ])

            @include('components.doctor-card', [
                'name' => 'Dr. Victor Nguyen',
                'specialty' => 'Gastroenterology',
                'description' => 'Advanced digestive health treatments and prevention.',
                'experience' => '9+',
                'hospital' => 'Digestive Clinic',
            ])

            @include('components.doctor-card', [
                'name' => 'Dr. Clara Whitmore',
                'specialty' => 'Psychiatry',
                'description' => 'Mental health support with personalized therapy plans.',
                'experience' => '16+',
                'hospital' => 'Mind Care',
            ])

        </div>
    </div>
</section>

@endsection

@section('content')
    <div class="container mx-auto py-10">
        <h1 class="text-3xl font-bold">Doctor Page</h1>
        <p>This is the doctor interface page.</p>
    </div>
@endsection
