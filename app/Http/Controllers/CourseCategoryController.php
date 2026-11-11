<?php

namespace App\Http\Controllers;

use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseCategoryController extends Controller
{
    public function index(Request $request)
    {
        // 1. Always fetch ALL categories for the filter dropdown options
        $allCategories = CourseCategory::orderBy('name')->get();

        // 2. Build filtered query for the grid cards
        $query = CourseCategory::withCount('courses');

        if ($request->filled('category')) {
            $query->where(function ($q) use ($request) {
                $q->where('slug', $request->category)
                  ->orWhere('id', $request->category);
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()
                            ->paginate(9)
                            ->withQueryString();

                            

        return view('course.categories', compact('categories', 'allCategories'));
    }
}