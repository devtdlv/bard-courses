<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a new comment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
            'course_id' => 'nullable|exists:courses,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $validated['user_id'] = auth()->id();

        $comment = Comment::create($validated);

        if ($request->course_id) {
            return redirect()->route('courses.show', Course::find($request->course_id)->slug)
                ->with('success', 'Comment added successfully!');
        }

        if ($request->lesson_id) {
            $lesson = Lesson::find($request->lesson_id);
            return redirect()->route('lessons.show', [$lesson->course->slug, $lesson->id])
                ->with('success', 'Comment added successfully!');
        }

        return back()->with('success', 'Comment added successfully!');
    }

    /**
     * Delete a comment
     */
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== auth()->id()) {
            abort(403);
        }

        $comment->delete();

        return back()->with('success', 'Comment deleted successfully!');
    }
}
