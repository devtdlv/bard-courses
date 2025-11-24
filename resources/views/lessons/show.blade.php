@extends('layouts.app')

@section('title', $lesson->title)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('courses.show', $course->slug) }}" class="text-amber-600 hover:text-amber-700 mb-4 inline-block">
            ← Back to Course
        </a>
        <h1 class="text-4xl font-bold text-gray-900">{{ $lesson->title }}</h1>
        <p class="text-gray-600 mt-2">{{ $course->title }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg p-8 mb-8" id="lesson-content">
        @if($lesson->type === 'video')
            @if($lesson->video_url)
                <div class="aspect-video mb-6">
                    <iframe id="lesson-video" src="{{ $lesson->video_url }}" class="w-full h-full rounded-lg" frameborder="0" allowfullscreen></iframe>
                </div>
            @else
                <div class="bg-gray-100 rounded-lg p-16 text-center mb-6">
                    <p class="text-gray-600">Video URL not set</p>
                </div>
            @endif
        @else
            <div class="prose max-w-none mb-6" id="lesson-text">
                {!! nl2br(e($lesson->content)) !!}
            </div>
        @endif

        @auth
            <div class="mt-4 p-4 bg-amber-50 rounded-lg border border-amber-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <div id="timer-indicator" class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-semibold text-gray-700">Tracking time...</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            <span id="current-time">Time spent: {{ $progress && $progress->watch_time_seconds > 0 ? gmdate('H:i:s', $progress->watch_time_seconds) : '00:00:00' }}</span>
                        </div>
                    </div>
                    @if($lesson->duration_minutes)
                        <div class="text-sm text-gray-600">
                            Duration: {{ $lesson->duration_minutes }} minutes
                        </div>
                    @endif
                </div>
                @if($lesson->duration_minutes)
                    <div class="mt-3">
                        <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                            <span>Progress</span>
                            <span id="progress-percentage">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="progress-bar" class="bg-amber-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                @endif
            </div>
        @endauth

        @if($lesson->description)
            <div class="border-t border-gray-200 pt-6 mt-6">
                <h3 class="font-semibold text-gray-900 mb-2">About this lesson</h3>
                <p class="text-gray-700">{{ $lesson->description }}</p>
            </div>
        @endif

        @auth
            @if(!$progress || !$progress->is_completed)
                <form action="{{ route('lessons.complete', [$course->slug, $lesson->id]) }}" method="POST" class="mt-6">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-lg font-semibold transition">
                        ✓ Mark as Completed
                    </button>
                </form>
            @else
                <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg inline-block">
                    ✓ Completed on {{ $progress->completed_at->format('M d, Y') }}
                </div>
            @endif
        @endauth
    </div>

    <div class="flex items-center justify-between bg-white rounded-xl shadow-lg p-6">
        <div>
            @if($prevLesson)
                <a href="{{ route('lessons.show', [$course->slug, $prevLesson->id]) }}" class="text-amber-600 hover:text-amber-700 font-semibold">
                    ← Previous Lesson
                </a>
            @endif
        </div>
        <div>
            @if($nextLesson)
                <a href="{{ route('lessons.show', [$course->slug, $nextLesson->id]) }}" class="text-amber-600 hover:text-amber-700 font-semibold">
                    Next Lesson →
                </a>
            @endif
        </div>
    </div>

    @auth
        <div class="bg-white rounded-xl shadow-lg p-8 mt-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Discussion</h2>
            <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                @csrf
                <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                <textarea name="content" rows="3" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent" placeholder="Add a comment..."></textarea>
                <button type="submit" class="mt-2 bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg transition">
                    Post Comment
                </button>
            </form>

            <div class="space-y-6">
                @forelse($lesson->comments as $comment)
                    <div class="border-l-4 border-amber-500 pl-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-gray-900">{{ $comment->user->name }}</span>
                            <span class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700">{{ $comment->content }}</p>
                    </div>
                @empty
                    <p class="text-gray-600">No comments yet.</p>
                @endforelse
            </div>
        </div>
    @endauth
</div>

@auth
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lessonId = {{ $lesson->id }};
    const courseSlug = '{{ $course->slug }}';
    const lessonDurationSeconds = {{ ($lesson->duration_minutes ?? 0) * 60 }};
    const initialWatchTime = {{ $progress->watch_time_seconds ?? 0 }};
    
    let watchTime = initialWatchTime;
    let startTime = Date.now();
    let isPageVisible = true;
    let updateInterval;
    let displayInterval;
    let lastUpdateTime = Date.now();

    // Format time for display
    function formatTime(seconds) {
        const hrs = Math.floor(seconds / 3600);
        const mins = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        return String(hrs).padStart(2, '0') + ':' + 
               String(mins).padStart(2, '0') + ':' + 
               String(secs).padStart(2, '0');
    }

    // Update display in real-time
    function updateDisplay() {
        if (isPageVisible) {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            const currentTime = initialWatchTime + elapsed;
            
            // Update time display
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = 'Time spent: ' + formatTime(currentTime);
            }

            // Update progress bar if lesson has duration
            if (lessonDurationSeconds > 0) {
                const progressPercentage = Math.min(100, (currentTime / lessonDurationSeconds) * 100);
                const progressBar = document.getElementById('progress-bar');
                const progressPercentageEl = document.getElementById('progress-percentage');
                
                if (progressBar) {
                    progressBar.style.width = progressPercentage + '%';
                }
                if (progressPercentageEl) {
                    progressPercentageEl.textContent = Math.round(progressPercentage) + '%';
                }
            }
        }
    }

    // Track page visibility
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            isPageVisible = false;
            const indicator = document.getElementById('timer-indicator');
            if (indicator) {
                indicator.classList.remove('bg-green-500', 'animate-pulse');
                indicator.classList.add('bg-gray-400');
            }
            // Save time when page becomes hidden
            saveProgress();
        } else {
            isPageVisible = true;
            startTime = Date.now();
            const indicator = document.getElementById('timer-indicator');
            if (indicator) {
                indicator.classList.remove('bg-gray-400');
                indicator.classList.add('bg-green-500', 'animate-pulse');
            }
        }
    });

    // Track time spent on page
    function updateWatchTime() {
        if (isPageVisible) {
            const elapsed = Math.floor((Date.now() - startTime) / 1000);
            watchTime = initialWatchTime + elapsed;
            
            // Update every 10 seconds
            if (Date.now() - lastUpdateTime >= 10000) {
                saveProgress();
                lastUpdateTime = Date.now();
            }
        }
    }

    // Save progress to server
    function saveProgress() {
        fetch(`/courses/${courseSlug}/lessons/${lessonId}/progress`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                watch_time_seconds: watchTime
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.is_completed && !document.querySelector('.lesson-completed')) {
                // Auto-completed! Show message
                const completedMsg = document.createElement('div');
                completedMsg.className = 'lesson-completed mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg';
                completedMsg.textContent = '✓ Lesson completed! You\'ve watched enough content.';
                document.getElementById('lesson-content').appendChild(completedMsg);
                
                // Update indicator
                const indicator = document.getElementById('timer-indicator');
                if (indicator) {
                    indicator.classList.remove('bg-green-500', 'animate-pulse');
                    indicator.classList.add('bg-green-600');
                }
                
                // Reload page to show completion status
                setTimeout(() => location.reload(), 2000);
            }
        })
        .catch(error => console.error('Error saving progress:', error));
    }

    // Start tracking
    updateInterval = setInterval(updateWatchTime, 1000);
    displayInterval = setInterval(updateDisplay, 1000); // Update display every second

    // Initial display update
    updateDisplay();

    // Save progress when leaving page
    window.addEventListener('beforeunload', function() {
        saveProgress();
    });

    // Save progress every 30 seconds as backup
    setInterval(saveProgress, 30000);
});
</script>
@endpush
@endauth
@endsection

