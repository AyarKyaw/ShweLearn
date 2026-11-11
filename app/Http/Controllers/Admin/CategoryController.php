<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CourseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('dashboard.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        // Fetch all course categories for the dropdown
        $courseCategories = CourseCategory::orderBy('name')->get();

        return view('dashboard.categories.create', compact('courseCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_category_id' => 'required|exists:course_categories,id',
            'name'               => 'required|string|max:255|unique:categories,name',
            'icon'               => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'is_active'          => 'nullable|boolean',
        ]);

        $category = new Category();
        $category->course_category_id = $validated['course_category_id'];
        $category->name = $validated['name'];
        $category->slug = Str::slug($validated['name']);
        $category->is_active = $request->has('is_active');

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('categories', 'public');
            $category->icon_path = 'storage/' . $path;
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $courseCategories = CourseCategory::orderBy('name')->get();

        return view('dashboard.categories.edit', compact('category', 'courseCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'course_category_id' => 'required|exists:course_categories,id',
            'name'               => 'required|string|max:255|unique:categories,name,' . $category->id,
            'icon'               => 'nullable|image|mimes:jpeg,png,jpg,svg,webp|max:2048',
            'is_active'          => 'nullable|boolean',
        ]);

        $category->course_category_id = $validated['course_category_id'];
        $category->name = $validated['name'];
        $category->slug = Str::slug($validated['name']);
        $category->is_active = $request->has('is_active');

        if ($request->hasFile('icon')) {
            // Remove old image if it exists
            if ($category->icon_path) {
                $relativeStoragePath = str_replace('storage/', '', $category->icon_path);
                Storage::disk('public')->delete($relativeStoragePath);
            }

            $path = $request->file('icon')->store('categories', 'public');
            $category->icon_path = 'storage/' . $path;
        }

        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->icon_path) {
            $relativeStoragePath = str_replace('storage/', '', $category->icon_path);
            Storage::disk('public')->delete($relativeStoragePath);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}