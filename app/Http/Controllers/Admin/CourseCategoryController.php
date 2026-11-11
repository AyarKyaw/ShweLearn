<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CourseCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseCategory::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->withCount('courses')
                            ->latest()
                            ->paginate(10)
                            ->withQueryString();

        return view('dashboard.course-categories.index', compact('categories'));
    }

    public function create()
    {
        return view('dashboard.course-categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:course_categories,name',
            'slug'        => 'nullable|string|max:255|unique:course_categories,slug',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('course-categories', 'public');
        }

        CourseCategory::create([
            'name'        => $request->name,
            'slug'        => $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name),
            'image'       => $imagePath,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.course-categories.index')
                        ->with('success', 'Category created successfully!');
    }

    public function show(CourseCategory $courseCategory)
    {
        $category = $courseCategory;
        $category->loadCount('courses');
        return view('dashboard.course-categories.show', compact('category'));
    }

    public function edit(CourseCategory $courseCategory)
    {
        $category = $courseCategory;
        return view('dashboard.course-categories.edit', compact('category'));
    }

    public function update(Request $request, CourseCategory $courseCategory)
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:course_categories,name,' . $courseCategory->id,
            'slug'        => 'nullable|string|max:255|unique:course_categories,slug,' . $courseCategory->id,
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $data = [
            'name'        => $request->name,
            'slug'        => $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->name),
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($courseCategory->image && Storage::disk('public')->exists($courseCategory->image)) {
                Storage::disk('public')->delete($courseCategory->image);
            }

            // Store new image in storage/app/public/course-categories
            $data['image'] = $request->file('image')->store('course-categories', 'public');
        }

        $courseCategory->update($data);

        return redirect()->route('admin.course-categories.index')
                        ->with('success', 'Category updated successfully!');
    }

    public function destroy(CourseCategory $courseCategory)
    {
        if ($courseCategory->courses()->exists()) {
            return redirect()->back()
                             ->with('error', 'Cannot delete category that has assigned courses.');
        }

        $courseCategory->delete();

        return redirect()->route('admin.course-categories.index')
                         ->with('success', 'Category deleted successfully!');
    }
}