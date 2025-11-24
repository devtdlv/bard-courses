<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    /**
     * Display a quiz
     */
    public function show(Course $course, Quiz $quiz)
    {
        // Check if user is enrolled
        $isEnrolled = auth()->check() && CourseEnrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->exists();

        if (!$isEnrolled) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You must enroll in this course to take the quiz.');
        }

        $quiz->load('questions');

        return view('quizzes.show', compact('course', 'quiz'));
    }

    /**
     * Submit quiz answers
     */
    public function submit(Request $request, Course $course, Quiz $quiz)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'answers' => 'required|array',
        ]);

        $answers = $request->answers;
        $questions = $quiz->questions;
        $score = 0;
        $totalPoints = 0;

        foreach ($questions as $question) {
            $totalPoints += $question->points;
            $userAnswer = $answers[$question->id] ?? null;

            if ($userAnswer && $this->checkAnswer($question, $userAnswer)) {
                $score += $question->points;
            }
        }

        $percentage = $totalPoints > 0 ? ($score / $totalPoints) * 100 : 0;
        $passed = $percentage >= $quiz->passing_score;

        $attempt = QuizAttempt::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id,
            'answers' => $answers,
            'score' => $score,
            'total_points' => $totalPoints,
            'percentage' => $percentage,
            'passed' => $passed,
            'started_at' => now(),
            'completed_at' => now(),
        ]);

        // If passed and course is completed, issue certificate
        if ($passed) {
            $enrollment = CourseEnrollment::where('user_id', auth()->id())
                ->where('course_id', $course->id)
                ->first();

            if ($enrollment && $enrollment->progress_percentage >= 100) {
                $this->issueCertificate($course, auth()->user());
            }
        }

        return redirect()->route('quizzes.result', [$course->slug, $quiz->id, $attempt->id])
            ->with('success', $passed ? 'Congratulations! You passed the quiz!' : 'Quiz completed. Review your answers below.');
    }

    /**
     * Show quiz result
     */
    public function result(Course $course, Quiz $quiz, QuizAttempt $attempt)
    {
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $quiz->load('questions');
        $attempt->load('quiz');

        return view('quizzes.result', compact('course', 'quiz', 'attempt'));
    }

    /**
     * Check if answer is correct
     */
    private function checkAnswer(QuizQuestion $question, $userAnswer): bool
    {
        $correctAnswer = strtolower(trim($question->correct_answer));
        $userAnswer = strtolower(trim($userAnswer));

        return $correctAnswer === $userAnswer;
    }

    /**
     * Issue certificate
     */
    private function issueCertificate(Course $course, $user): void
    {
        $certificate = \App\Models\Certificate::firstOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ],
            [
                'certificate_number' => 'BC-' . strtoupper(uniqid()),
                'issued_at' => now(),
            ]
        );
    }
}
