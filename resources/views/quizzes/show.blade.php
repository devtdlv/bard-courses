@extends('layouts.app')

@section('title', $quiz->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('courses.show', $course->slug) }}" class="text-amber-600 hover:text-amber-700 mb-4 inline-block">
            ← Back to Course
        </a>
        <h1 class="text-4xl font-bold text-gray-900">{{ $quiz->title }}</h1>
        @if($quiz->description)
            <p class="text-gray-600 mt-2">{{ $quiz->description }}</p>
        @endif
    </div>

    <form action="{{ route('quizzes.submit', [$course->slug, $quiz->id]) }}" method="POST" class="bg-white rounded-xl shadow-lg p-8">
        @csrf

        @foreach($quiz->questions as $index => $question)
            <div class="mb-8 pb-8 border-b border-gray-200 last:border-0">
                <div class="flex items-start mb-4">
                    <span class="bg-amber-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">
                        {{ $index + 1 }}
                    </span>
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ $question->question }}</h3>
                        <p class="text-sm text-gray-600 mb-4">Points: {{ $question->points }}</p>

                        @if($question->type === 'multiple_choice' && $question->options)
                            <div class="space-y-3">
                                @foreach($question->options as $option)
                                    <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-amber-50 cursor-pointer">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option }}" required class="mr-3">
                                        <span>{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @elseif($question->type === 'true_false')
                            <div class="space-y-3">
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-amber-50 cursor-pointer">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="true" required class="mr-3">
                                    <span>True</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg hover:bg-amber-50 cursor-pointer">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="false" required class="mr-3">
                                    <span>False</span>
                                </label>
                            </div>
                        @else
                            <textarea name="answers[{{ $question->id }}]" rows="3" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                placeholder="Your answer..."></textarea>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

        <div class="flex items-center justify-end space-x-4 mt-8">
            <a href="{{ route('courses.show', $course->slug) }}" class="text-gray-600 hover:text-gray-800">Cancel</a>
            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-lg font-semibold transition">
                Submit Quiz
            </button>
        </div>
    </form>
</div>
@endsection

