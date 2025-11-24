<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::where('is_published', true)
            ->with('instructor')
            ->latest()
            ->paginate(12);

        return view('courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'thumbnail' => 'nullable|url',
        ]);

        $validated['instructor_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_published'] = false;

        $course = Course::create($validated);

        return redirect()->route('courses.show', $course->slug)
            ->with('success', 'Course created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        $course->load(['instructor', 'lessons', 'quizzes', 'comments.user']);

        $isEnrolled = auth()->check() && CourseEnrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->exists();

        // Get lesson progress for enrolled users
        $lessonProgress = [];
        $enrollment = null;
        if ($isEnrolled && auth()->check()) {
            $enrollment = CourseEnrollment::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->first();

            $progressRecords = \App\Models\LessonProgress::where('user_id', auth()->id())
                ->whereIn('lesson_id', $course->lessons->pluck('id'))
                ->get()
                ->keyBy('lesson_id');

            foreach ($course->lessons as $lesson) {
                $lessonProgress[$lesson->id] = $progressRecords->get($lesson->id);
            }
        }

        return view('courses.show', compact('course', 'isEnrolled', 'lessonProgress', 'enrollment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);
        return view('courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'level' => 'required|in:beginner,intermediate,advanced',
            'thumbnail' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        if ($request->has('title') && $request->title !== $course->title) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $course->update($validated);

        return redirect()->route('courses.show', $course->slug)
            ->with('success', 'Course updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);
        $course->delete();

        return redirect()->route('courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    /**
     * Enroll user in a course
     */
    public function enroll(Course $course)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $enrollment = CourseEnrollment::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $course->id,
            ],
            [
                'enrolled_at' => now(),
                'progress_percentage' => 0,
            ]
        );

        $course->increment('students_count');

        return redirect()->route('courses.show', $course->slug)
            ->with('success', 'Successfully enrolled in course!');
    }
}
