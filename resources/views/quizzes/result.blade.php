@extends('layouts.app')

@section('title', 'Quiz Results')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('courses.show', $course->slug) }}" class="text-amber-600 hover:text-amber-700 mb-4 inline-block">
            ← Back to Course
        </a>
        <h1 class="text-4xl font-bold text-gray-900">Quiz Results</h1>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
        <div class="text-center mb-8">
            @if($attempt->passed)
                <div class="text-6xl mb-4">🎉</div>
                <h2 class="text-3xl font-bold text-green-600 mb-2">Congratulations!</h2>
                <p class="text-gray-600">You passed the quiz!</p>
            @else
                <div class="text-6xl mb-4">📚</div>
                <h2 class="text-3xl font-bold text-orange-600 mb-2">Keep Learning!</h2>
                <p class="text-gray-600">You scored {{ number_format($attempt->percentage, 1) }}%. Passing score is {{ $quiz->passing_score }}%.</p>
            @endif
        </div>

        <div class="grid grid-cols-3 gap-4 mb-8">
            <div class="bg-amber-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-amber-600">{{ $attempt->score }}</div>
                <div class="text-sm text-gray-600">Points Earned</div>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $attempt->total_points }}</div>
                <div class="text-sm text-gray-600">Total Points</div>
            </div>
            <div class="bg-purple-50 rounded-lg p-4 text-center">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($attempt->percentage, 1) }}%</div>
                <div class="text-sm text-gray-600">Score</div>
            </div>
        </div>

        <h3 class="text-xl font-bold text-gray-900 mb-6">Review Your Answers</h3>
        <div class="space-y-6">
            @foreach($quiz->questions as $index => $question)
                @php
                    $userAnswer = $attempt->answers[$question->id] ?? null;
                    $isCorrect = $userAnswer && strtolower(trim($userAnswer)) === strtolower(trim($question->correct_answer));
                @endphp
                <div class="border rounded-lg p-6 {{ $isCorrect ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    <div class="flex items-start mb-4">
                        <span class="bg-{{ $isCorrect ? 'green' : 'red' }}-500 text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4 flex-shrink-0">
                            {{ $isCorrect ? '✓' : '✗' }}
                        </span>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-2">{{ $question->question }}</h4>
                            <div class="space-y-2 text-sm">
                                <div>
                                    <span class="font-semibold">Your answer:</span>
                                    <span class="{{ $isCorrect ? 'text-green-700' : 'text-red-700' }}">{{ $userAnswer ?? 'No answer' }}</span>
                                </div>
                                @if(!$isCorrect)
                                    <div>
                                        <span class="font-semibold">Correct answer:</span>
                                        <span class="text-green-700">{{ $question->correct_answer }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

