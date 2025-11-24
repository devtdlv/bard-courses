<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display a lesson
     */
    public function show(Course $course, Lesson $lesson)
    {
        // Check if user is enrolled
        $isEnrolled = auth()->check() && CourseEnrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->exists();

        if (!$isEnrolled && !$lesson->is_free) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You must enroll in this course to access this lesson.');
        }

        // Get all lessons for navigation
        $lessons = $course->lessons;
        $currentIndex = $lessons->search(function ($l) use ($lesson) {
            return $l->id === $lesson->id;
        });

        $prevLesson = $currentIndex > 0 ? $lessons[$currentIndex - 1] : null;
        $nextLesson = $currentIndex < $lessons->count() - 1 ? $lessons[$currentIndex + 1] : null;

        // Get user progress
        $progress = null;
        if (auth()->check()) {
            $progress = LessonProgress::where('user_id', auth()->id())
                ->where('lesson_id', $lesson->id)
                ->first();
        }

        return view('lessons.show', compact('course', 'lesson', 'prevLesson', 'nextLesson', 'progress'));
    }

    /**
     * Update lesson progress with watch time
     */
    public function updateProgress(Request $request, Course $course, Lesson $lesson)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'watch_time_seconds' => 'required|integer|min:0',
        ]);

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'lesson_id' => $lesson->id,
            ],
            [
                'watch_time_seconds' => $request->watch_time_seconds,
            ]
        );

        // Auto-complete if watched at least 80% of lesson duration
        $lessonDurationSeconds = ($lesson->duration_minutes ?? 0) * 60;
        $completionThreshold = $lessonDurationSeconds * 0.8;

        if ($progress->watch_time_seconds >= $completionThreshold && !$progress->is_completed) {
            $progress->update([
                'is_completed' => true,
                'completed_at' => now(),
            ]);

            $this->updateCourseProgress($course, auth()->id());
        }

        return response()->json([
            'success' => true,
            'progress' => $progress,
            'is_completed' => $progress->is_completed,
        ]);
    }

    /**
     * Mark lesson as completed
     */
    public function complete(Course $course, Lesson $lesson)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $progress = LessonProgress::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'lesson_id' => $lesson->id,
            ],
            [
                'is_completed' => true,
                'completed_at' => now(),
            ]
        );

        $this->updateCourseProgress($course, auth()->id());

        return response()->json(['success' => true, 'progress' => $progress]);
    }

    /**
     * Update course progress percentage
     */
    private function updateCourseProgress(Course $course, $userId)
    {
        $enrollment = CourseEnrollment::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            $totalLessons = $course->lessons()->count();
            $completedLessons = LessonProgress::where('user_id', $userId)
                ->where('is_completed', true)
                ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                ->count();

            $enrollment->update([
                'progress_percentage' => $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0,
            ]);

            // Check if course is completed
            if ($enrollment->progress_percentage >= 100 && !$enrollment->completed_at) {
                $enrollment->update(['completed_at' => now()]);
            }
        }
    }
}
