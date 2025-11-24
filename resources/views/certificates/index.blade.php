@extends('layouts.app')

@section('title', 'My Certificates')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900 mb-2">My Certificates</h1>
    <p class="text-gray-600">Your completed course certificates</p>
</div>

@if($certificates->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($certificates as $certificate)
            <div class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow overflow-hidden">
                <div class="bg-gradient-to-br from-amber-400 to-orange-500 p-8 text-center">
                    <div class="text-6xl mb-4">🎓</div>
                    <h3 class="text-xl font-bold text-white">{{ $certificate->course->title }}</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 mb-4">Certificate #{{ $certificate->certificate_number }}</p>
                    <p class="text-sm text-gray-600 mb-4">Issued: {{ $certificate->issued_at->format('M d, Y') }}</p>
                    <a href="{{ route('certificates.show', [$certificate->course->slug, $certificate->id]) }}" class="block w-full bg-amber-500 hover:bg-amber-600 text-white text-center py-2 rounded-lg transition">
                        View Certificate
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white rounded-xl shadow-lg p-16 text-center">
        <div class="text-6xl mb-4">📜</div>
        <h3 class="text-2xl font-bold text-gray-900 mb-2">No Certificates Yet</h3>
        <p class="text-gray-600 mb-6">Complete a course to earn your first certificate!</p>
        <a href="{{ route('courses.index') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-lg transition">
            Browse Courses
        </a>
    </div>
@endif
@endsection

