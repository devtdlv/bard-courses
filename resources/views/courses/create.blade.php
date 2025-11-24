@extends('layouts.app')

@section('title', 'Create Course')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-4xl font-bold text-gray-900 mb-8">Create a New Course</h1>

    <form action="{{ route('courses.store') }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
        @csrf

        <div class="mb-6">
            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Course Title</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            @error('title')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="6" required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Price ($)</label>
                <input type="number" name="price" id="price" value="{{ old('price', 0) }}" step="0.01" min="0" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                @error('price')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="level" class="block text-sm font-semibold text-gray-700 mb-2">Level</label>
                <select name="level" id="level" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent">
                    <option value="beginner" {{ old('level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ old('level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced" {{ old('level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
                @error('level')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mb-6">
            <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail URL (optional)</label>
            <input type="url" name="thumbnail" id="thumbnail" value="{{ old('thumbnail') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent">
            @error('thumbnail')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('courses.index') }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-lg font-semibold transition">
                Create Course
            </button>
        </div>
    </form>
</div>
@endsection

