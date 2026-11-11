<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     *
     * @return View
     */
    public function __invoke(Request $request): View
{
    $categories = Category::withCount('courses')
        ->orderBy('name', 'asc')
        ->get();

    $courses = Course::withCount(['lessons', 'students'])
        ->latest()
        ->get();

    return view('index', compact('categories', 'courses'));
}
}