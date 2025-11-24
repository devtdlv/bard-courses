@extends('layouts.app')

@section('title', $course->title)

@section('content')
<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
    @if($course->thumbnail)
        <img src="{{ $course->thumbnail }}" alt="{{ $course->title }}" class="w-full h-64 object-cover">
    @else
        <div class="w-full h-64 bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
            <span class="text-9xl">🎻</span>
        </div>
    @endif
    <div class="p-8">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-4">
                <span class="px-3 py-1 bg-amber-100 text-amber-800 text-sm font-semibold rounded">{{ ucfirst($course->level) }}</span>
                <span class="text-gray-600">👤 {{ $course->instructor->name }}</span>
                <span class="text-gray-600">📚 {{ $course->students_count }} students</span>
            </div>
            <span class="text-3xl font-bold text-amber-600">${{ number_format($course->price, 2) }}</span>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $course->title }}</h1>
        <p class="text-gray-700 text-lg mb-6">{{ $course->description }}</p>
        
        @if(!$isEnrolled)
            <form action="{{ route('courses.enroll', $course) }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-3 rounded-lg font-semibold transition">
                    Enroll Now
                </button>
            </form>
        @else
            <div class="space-y-4">
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg inline-block">
                    ✓ You are enrolled in this course
                </div>
                @if($enrollment)
                    <div class="mt-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">Course Progress</span>
                            <span class="text-sm font-bold text-amber-600">{{ number_format($enrollment->progress_percentage, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-amber-500 h-2.5 rounded-full transition-all" style="width: {{ $enrollment->progress_percentage }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Course Content</h2>
            <div class="space-y-4">
                @forelse($course->lessons as $lesson)
                    @php
                        $progress = $lessonProgress[$lesson->id] ?? null;
                        $isCompleted = $progress && $progress->is_completed;
                        $watchTime = $progress ? $progress->watch_time_seconds : 0;
                        $lessonDurationSeconds = ($lesson->duration_minutes ?? 0) * 60;
                        $progressPercentage = $lessonDurationSeconds > 0 ? min(100, ($watchTime / $lessonDurationSeconds) * 100) : 0;
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-amber-50 transition {{ $isCompleted ? 'border-green-300 bg-green-50' : '' }}">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4 flex-1">
                                <span class="text-2xl">{{ $lesson->type === 'video' ? '🎥' : '📄' }}</span>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">
                                        <h3 class="font-semibold text-gray-900">{{ $lesson->title }}</h3>
                                        @if($isCompleted)
                                            <span class="text-green-600 text-sm">✓ Completed</span>
                                        @elseif($watchTime > 0)
                                            <span class="text-amber-600 text-sm">In Progress</span>
                                        @endif
                                    </div>
                                    @if($lesson->description)
                                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($lesson->description, 80) }}</p>
                                    @endif
                                    @if($isEnrolled && $progress)
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            @if($watchTime > 0)
                                                <span>Time: {{ gmdate('i:s', $watchTime) }}</span>
                                            @endif
                                            @if($lesson->duration_minutes)
                                                <span>Duration: {{ $lesson->duration_minutes }} min</span>
                                            @endif
                                        </div>
                                        @if($watchTime > 0 && $lessonDurationSeconds > 0)
                                            <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                                <div class="bg-amber-500 h-1.5 rounded-full transition-all" style="width: {{ $progressPercentage }}%"></div>
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-4 ml-4">
                                @if($lesson->is_free)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Free</span>
                                @endif
                                @if($isEnrolled || $lesson->is_free)
                                    <a href="{{ route('lessons.show', [$course->slug, $lesson->id]) }}" class="text-amber-600 hover:text-amber-700 font-semibold whitespace-nowrap">
                                        {{ $isCompleted ? 'Review' : ($watchTime > 0 ? 'Continue' : 'Start') }} →
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600">No lessons available yet.</p>
                @endforelse
            </div>
        </div>

        @if($course->quizzes->count() > 0)
            <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Quizzes</h2>
                <div class="space-y-4">
                    @foreach($course->quizzes as $quiz)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-amber-50 transition">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="font-semibold text-gray-900">📝 {{ $quiz->title }}</h3>
                                    @if($quiz->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ $quiz->description }}</p>
                                    @endif
                                </div>
                                @if($isEnrolled)
                                    <a href="{{ route('quizzes.show', [$course->slug, $quiz->id]) }}" class="text-amber-600 hover:text-amber-700 font-semibold">
                                        Take Quiz →
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Discussion</h2>
            @auth
                <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                    <textarea name="content" rows="3" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="Add a comment..."></textarea>
                    <button type="submit" class="mt-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg transition">
                        Post Comment
                    </button>
                </form>
            @else
                <p class="text-gray-600 mb-4">Please <a href="{{ route('login') }}" class="text-amber-600 hover:underline">login</a> to join the discussion.</p>
            @endauth

            <div class="space-y-6">
                @forelse($course->comments as $comment)
                    <div class="border-l-4 border-amber-500 pl-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-900">{{ $comment->user->name }}</span>
                            <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700">{{ $comment->content }}</p>
                        @if(auth()->id() === $comment->user_id)
                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-700 text-sm">Delete</button>
                            </form>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-600">No comments yet. Be the first to comment!</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-lg p-6 sticky top-20">
            <h3 class="font-bold text-gray-900 mb-4">Course Info</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Level:</span>
                    <span class="font-semibold">{{ ucfirst($course->level) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Lessons:</span>
                    <span class="font-semibold">{{ $course->lessons->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Quizzes:</span>
                    <span class="font-semibold">{{ $course->quizzes->count() }}</span>
                </div>
                @if($course->duration_minutes)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Duration:</span>
                        <span class="font-semibold">{{ $course->duration_minutes }} min</span>
                    </div>
                @endif
                @if($isEnrolled && $enrollment)
                    <div class="pt-3 border-t border-gray-200 mt-3">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Your Progress:</span>
                            <span class="font-semibold text-amber-600">{{ number_format($enrollment->progress_percentage, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full transition-all" style="width: {{ $enrollment->progress_percentage }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

