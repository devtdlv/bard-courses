<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display user's certificates
     */
    public function index()
    {
        $certificates = Certificate::where('user_id', auth()->id())
            ->with('course')
            ->latest()
            ->get();

        return view('certificates.index', compact('certificates'));
    }

    /**
     * Display a specific certificate
     */
    public function show(Course $course, Certificate $certificate)
    {
        if ($certificate->user_id !== auth()->id()) {
            abort(403);
        }

        $certificate->load(['course', 'user']);

        return view('certificates.show', compact('certificate', 'course'));
    }

    /**
     * Download certificate as PDF (placeholder)
     */
    public function download(Course $course, Certificate $certificate)
    {
        if ($certificate->user_id !== auth()->id()) {
            abort(403);
        }

        // TODO: Implement PDF generation
        return response()->json(['message' => 'PDF download coming soon']);
    }
}
