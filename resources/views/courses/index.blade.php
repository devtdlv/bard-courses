@extends('layouts.app')

@section('title', 'All Courses')

@section('content')
<div class="mb-8">
    <h1 class="text-4xl font-bold text-gray-900 mb-2">Discover Courses</h1>
    <p class="text-gray-600">Explore our collection of courses taught by passionate instructors</p>
</div>

@if($courses->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($courses as $course)
            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow overflow-hidden">
                @if($course->thumbnail)
                    <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="w-full h-48 bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                        <span class="text-6xl">🎻</span>
                    </div>
                @endif
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-1 bg-amber-100 text-amber-800 text-xs font-semibold rounded">{{ ucfirst($course->level) }}</span>
                        <span class="text-amber-600 font-bold">${{ number_format($course->price, 2) }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $course->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($course->description, 100) }}</p>
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>👤 {{ $course->instructor->name }}</span>
                        <span>📚 {{ $course->students_count }} students</span>
                    </div>
                    <a href="{{ route('courses.show', $course->slug) }}" class="block w-full bg-amber-500 hover:bg-amber-600 text-white text-center py-2 rounded-lg transition">
                        View Course
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $courses->links() }}
    </div>
@else
    <div class="text-center py-16">
        <p class="text-gray-600 text-lg">No courses available yet. Be the first to create one!</p>
        @auth
            <a href="{{ route('courses.create') }}" class="inline-block mt-4 bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-lg transition">
                Create Your First Course
            </a>
        @endauth
    </div>
@endif
@endsection

