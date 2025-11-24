<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\CourseEnrollment;
use App\Models\LessonProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin/instructor user
        $instructor = User::create([
            'name' => 'Master Bard',
            'email' => 'instructor@bardcourses.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create student users
        $student1 = User::create([
            'name' => 'Alice Learner',
            'email' => 'alice@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $student2 = User::create([
            'name' => 'Bob Student',
            'email' => 'bob@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create Course 1: Introduction to Web Development
        $course1 = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Introduction to Web Development',
            'description' => 'Learn the fundamentals of web development including HTML, CSS, and JavaScript. Perfect for beginners who want to start their journey in web development.',
            'slug' => Str::slug('Introduction to Web Development'),
            'price' => 49.99,
            'level' => 'beginner',
            'is_published' => true,
            'duration_minutes' => 480,
            'students_count' => 2,
            'rating' => 4.8,
        ]);

        // Course 1 Lessons
        $lesson1_1 = Lesson::create([
            'course_id' => $course1->id,
            'title' => 'Welcome to Web Development',
            'description' => 'An introduction to the world of web development',
            'type' => 'text',
            'content' => "Welcome to your web development journey! In this course, you'll learn everything you need to build beautiful, functional websites.\n\nWeb development is the process of creating websites and web applications. It involves writing code that browsers can understand and display.\n\nWe'll start with the basics and gradually build up to more advanced concepts.",
            'duration_minutes' => 15,
            'order' => 1,
            'is_free' => true,
        ]);

        $lesson1_2 = Lesson::create([
            'course_id' => $course1->id,
            'title' => 'HTML Basics',
            'description' => 'Learn the structure of HTML documents',
            'type' => 'text',
            'content' => "HTML (HyperText Markup Language) is the foundation of every webpage.\n\nHTML uses tags to structure content. For example:\n- <h1> for headings\n- <p> for paragraphs\n- <a> for links\n- <img> for images\n\nLet's start building!",
            'duration_minutes' => 30,
            'order' => 2,
            'is_free' => false,
        ]);

        $lesson1_3 = Lesson::create([
            'course_id' => $course1->id,
            'title' => 'CSS Styling',
            'description' => 'Make your websites beautiful with CSS',
            'type' => 'text',
            'content' => "CSS (Cascading Style Sheets) is used to style HTML elements.\n\nWith CSS, you can:\n- Change colors and fonts\n- Control layout and spacing\n- Add animations and transitions\n- Make responsive designs\n\nCSS makes the web beautiful!",
            'duration_minutes' => 45,
            'order' => 3,
            'is_free' => false,
        ]);

        // Course 1 Quiz
        $quiz1 = Quiz::create([
            'course_id' => $course1->id,
            'title' => 'Web Development Fundamentals Quiz',
            'description' => 'Test your knowledge of HTML and CSS basics',
            'passing_score' => 70,
            'time_limit_minutes' => 15,
            'order' => 1,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'What does HTML stand for?',
            'type' => 'multiple_choice',
            'options' => [
                'HyperText Markup Language',
                'High Tech Modern Language',
                'Home Tool Markup Language',
                'Hyperlink and Text Markup Language',
            ],
            'correct_answer' => 'HyperText Markup Language',
            'points' => 10,
            'order' => 1,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'CSS is used for styling web pages.',
            'type' => 'true_false',
            'correct_answer' => 'true',
            'points' => 10,
            'order' => 2,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz1->id,
            'question' => 'Which HTML tag is used for the largest heading?',
            'type' => 'multiple_choice',
            'options' => ['<h1>', '<h6>', '<heading>', '<head>'],
            'correct_answer' => '<h1>',
            'points' => 10,
            'order' => 3,
        ]);

        // Create Course 2: Advanced JavaScript
        $course2 = Course::create([
            'instructor_id' => $instructor->id,
            'title' => 'Advanced JavaScript',
            'description' => 'Master advanced JavaScript concepts including ES6+, async/await, and modern frameworks. For developers ready to level up their skills.',
            'slug' => Str::slug('Advanced JavaScript'),
            'price' => 79.99,
            'level' => 'advanced',
            'is_published' => true,
            'duration_minutes' => 600,
            'students_count' => 1,
            'rating' => 4.9,
        ]);

        $lesson2_1 = Lesson::create([
            'course_id' => $course2->id,
            'title' => 'ES6+ Features',
            'description' => 'Explore modern JavaScript features',
            'type' => 'text',
            'content' => "ES6 (ECMAScript 2015) introduced many powerful features:\n\n- Arrow functions\n- Destructuring\n- Template literals\n- Promises\n- Classes\n- Modules\n\nThese features make JavaScript more powerful and easier to write.",
            'duration_minutes' => 60,
            'order' => 1,
            'is_free' => true,
        ]);

        $lesson2_2 = Lesson::create([
            'course_id' => $course2->id,
            'title' => 'Async/Await',
            'description' => 'Master asynchronous programming',
            'type' => 'text',
            'content' => "Async/await makes asynchronous code look and behave like synchronous code.\n\nInstead of callbacks or promises, you can write:\n\nasync function fetchData() {\n  const response = await fetch(url);\n  const data = await response.json();\n  return data;\n}\n\nMuch cleaner!",
            'duration_minutes' => 45,
            'order' => 2,
            'is_free' => false,
        ]);

        // Course 2 Quiz
        $quiz2 = Quiz::create([
            'course_id' => $course2->id,
            'title' => 'JavaScript Advanced Concepts',
            'description' => 'Test your understanding of advanced JavaScript',
            'passing_score' => 75,
            'time_limit_minutes' => 20,
            'order' => 1,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question' => 'What does async/await help with?',
            'type' => 'short_answer',
            'correct_answer' => 'asynchronous programming',
            'points' => 20,
            'order' => 1,
        ]);

        QuizQuestion::create([
            'quiz_id' => $quiz2->id,
            'question' => 'Arrow functions preserve the "this" context.',
            'type' => 'true_false',
            'correct_answer' => 'true',
            'points' => 15,
            'order' => 2,
        ]);

        // Enroll students
        $enrollment1 = CourseEnrollment::create([
            'user_id' => $student1->id,
            'course_id' => $course1->id,
            'enrolled_at' => now()->subDays(5),
            'progress_percentage' => 50,
        ]);

        $enrollment2 = CourseEnrollment::create([
            'user_id' => $student2->id,
            'course_id' => $course1->id,
            'enrolled_at' => now()->subDays(3),
            'progress_percentage' => 25,
        ]);

        $enrollment3 = CourseEnrollment::create([
            'user_id' => $student1->id,
            'course_id' => $course2->id,
            'enrolled_at' => now()->subDays(2),
            'progress_percentage' => 10,
        ]);

        // Add lesson progress
        LessonProgress::create([
            'user_id' => $student1->id,
            'lesson_id' => $lesson1_1->id,
            'is_completed' => true,
            'completed_at' => now()->subDays(4),
        ]);

        LessonProgress::create([
            'user_id' => $student1->id,
            'lesson_id' => $lesson1_2->id,
            'is_completed' => true,
            'completed_at' => now()->subDays(3),
        ]);

        LessonProgress::create([
            'user_id' => $student2->id,
            'lesson_id' => $lesson1_1->id,
            'is_completed' => true,
            'completed_at' => now()->subDays(2),
        ]);

        // Add some comments
        \App\Models\Comment::create([
            'user_id' => $student1->id,
            'course_id' => $course1->id,
            'content' => 'Great course! Really enjoying the content so far.',
        ]);

        \App\Models\Comment::create([
            'user_id' => $student2->id,
            'course_id' => $course1->id,
            'content' => 'The HTML basics lesson was very clear. Thank you!',
        ]);

        $this->command->info('Database seeded successfully!');
        $this->command->info('Instructor: instructor@bardcourses.com / password');
        $this->command->info('Student 1: alice@example.com / password');
        $this->command->info('Student 2: bob@example.com / password');
    }
}
