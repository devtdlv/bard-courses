# 🎻 BardCourses

A gentle, melodic learning platform where users can teach or learn. Built with Laravel 12 and Tailwind CSS 4.

## Features

- **Courses**: Create and publish courses with rich descriptions, pricing, and difficulty levels
- **Lessons**: Support for both video and text-based lessons with real-time progress tracking
- **Progress Tracking**: Automatic time tracking that monitors how long users spend on each lesson
- **Quizzes**: Interactive quizzes with multiple question types (multiple choice, true/false, short answer)
- **Certificates**: Automatic certificate generation upon course completion
- **Comments/Discussions**: Community discussions on courses and lessons
- **User Enrollment**: Easy enrollment system with progress tracking
- **Beautiful UI**: Modern, responsive design with Tailwind CSS and a warm amber/orange theme

## Tech Stack

- **Backend**: Laravel 12
- **Frontend**: Tailwind CSS 4, Alpine.js
- **Authentication**: Laravel Breeze (Blade)
- **Database**: SQLite (default, easily switchable to MySQL/PostgreSQL)

## Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd bard-courses
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

   Visit `http://localhost:8000` in your browser.

## Seeded Accounts

After running the seeder, you can log in with:

- **Instructor**: `instructor@bardcourses.com` / `password`
- **Student 1**: `alice@example.com` / `password`
- **Student 2**: `bob@example.com` / `password`

## Database Structure

### Core Tables

- **courses**: Course information, pricing, instructor assignment
- **lessons**: Individual lessons (video or text) within courses
- **course_enrollments**: User enrollments and overall progress
- **lesson_progress**: Detailed progress tracking with watch time
- **quizzes**: Course and lesson quizzes
- **quiz_questions**: Questions for each quiz
- **quiz_attempts**: User quiz submissions and scores
- **certificates**: Course completion certificates
- **comments**: Discussion threads on courses and lessons

## Key Features Explained

### Time Tracking

The platform automatically tracks time spent on lessons:
- Tracks only when the page is visible (pauses when tab is hidden)
- Updates progress every 10 seconds
- Auto-completes lessons when 80% of duration is reached
- Shows real-time progress indicators

### Progress System

- Course progress is calculated based on completed lessons
- Individual lesson progress shows time spent and completion status
- Visual progress bars throughout the interface
- Certificates issued automatically upon course completion

### Quiz System

- Multiple question types: multiple choice, true/false, short answer
- Configurable passing scores
- Time limits (optional)
- Detailed result pages with answer review

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
```bash
./vendor/bin/pint
```

### Asset Compilation
```bash
# Development with hot reload
npm run dev

# Production build
npm run build
```

## Project Structure

```
app/
├── Http/Controllers/     # Course, Lesson, Quiz, Certificate controllers
├── Models/              # Eloquent models with relationships
└── Policies/            # Authorization policies

database/
├── migrations/          # Database schema migrations
└── seeders/             # Database seeder with sample data

resources/
├── views/
│   ├── courses/         # Course listing and detail pages
│   ├── lessons/         # Lesson viewing pages
│   ├── quizzes/         # Quiz taking and results
│   ├── certificates/    # Certificate display
│   └── layouts/         # Application layouts
└── js/                  # JavaScript for time tracking

routes/
└── web.php              # Application routes
```

## Contributing

This is a learning platform project. Feel free to fork, modify, and use it for your own courses!

## License

Open source - feel free to use and modify as needed.

---

*Built with ❤️ for learners and teachers everywhere*
