<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>🎻 BardCourses - A Gentle Learning Platform</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-amber-50 via-orange-50 to-rose-50 min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center px-4">
        <div class="max-w-4xl w-full text-center mb-12">
            <div class="text-8xl mb-6">🎻</div>
            <h1 class="text-6xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent mb-4">
                BardCourses
            </h1>
            <p class="text-2xl text-gray-700 mb-8">A gentle, melodic learning platform where users can teach or learn.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @auth
                    <a href="{{ route('courses.index') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-4 rounded-lg font-semibold text-lg transition shadow-lg">
                        Browse Courses
                    </a>
                @else
                    <a href="{{ route('login') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-8 py-4 rounded-lg font-semibold text-lg transition shadow-lg">
                        Get Started
                    </a>
                    <a href="{{ route('register') }}" class="bg-white hover:bg-gray-50 text-amber-600 border-2 border-amber-500 px-8 py-4 rounded-lg font-semibold text-lg transition shadow-lg">
                        Sign Up
                    </a>
                @endauth
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl w-full mt-12">
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-4xl mb-4">📚</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Learn</h3>
                <p class="text-gray-600">Explore courses on any topic, from beginner to advanced levels.</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-4xl mb-4">🎓</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Teach</h3>
                <p class="text-gray-600">Share your knowledge and create engaging courses for others.</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="text-4xl mb-4">🏆</div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Certify</h3>
                <p class="text-gray-600">Earn certificates as you complete courses and master new skills.</p>
            </div>
        </div>
    </div>
</body>
</html>
