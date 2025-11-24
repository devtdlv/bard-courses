@extends('layouts.app')

@section('title', 'Certificate')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl shadow-2xl p-12 border-4 border-amber-300">
        <div class="text-center mb-8">
            <div class="text-8xl mb-4">🎓</div>
            <h1 class="text-5xl font-bold text-gray-900 mb-2">Certificate of Completion</h1>
            <p class="text-xl text-gray-700">This is to certify that</p>
        </div>

        <div class="text-center mb-8">
            <h2 class="text-4xl font-bold text-amber-700 mb-4">{{ $certificate->user->name }}</h2>
            <p class="text-xl text-gray-700 mb-2">has successfully completed the course</p>
            <h3 class="text-3xl font-bold text-gray-900">{{ $certificate->course->title }}</h3>
        </div>

        <div class="flex items-center justify-between mt-12 pt-8 border-t-2 border-amber-300">
            <div class="text-center">
                <div class="text-4xl mb-2">🎻</div>
                <p class="font-semibold text-gray-900">BardCourses</p>
                <p class="text-sm text-gray-600">Learning Platform</p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Certificate Number</p>
                <p class="font-mono font-semibold text-gray-900">{{ $certificate->certificate_number }}</p>
                <p class="text-sm text-gray-600 mt-2">{{ $certificate->issued_at->format('F d, Y') }}</p>
            </div>
        </div>
    </div>

    <div class="mt-8 text-center">
        <a href="{{ route('certificates.download', [$course->slug, $certificate->id]) }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-lg font-semibold transition mr-4">
            Download PDF
        </a>
        <a href="{{ route('certificates.index') }}" class="inline-block text-gray-600 hover:text-gray-800">
            Back to Certificates
        </a>
    </div>
</div>
@endsection

