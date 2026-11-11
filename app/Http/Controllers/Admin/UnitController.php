<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the units.
     */
    public function index()
    {
        $units = Unit::with('course')
            ->withCount('sections')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('dashboard.courses.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create()
    {
        $courses = Course::orderBy('title', 'asc')->get();

        return view('dashboard.courses.units.create', compact('courses'));
    }

    /**
     * Store a newly created unit in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:255',
            'order'       => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        Unit::create($validated);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit created successfully.');
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit)
    {
        $courses = Course::orderBy('title', 'asc')->get();

        return view('dashboard.courses.units.edit', compact('unit', 'courses'));
    }

    /**
     * Update the specified unit in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:255',
            'order'       => 'nullable|integer|min:1',
            'description' => 'nullable|string',
        ]);

        $unit->update($validated);

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit updated successfully.');
    }

    /**
     * Remove the specified unit from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('admin.units.index')
            ->with('success', 'Unit deleted successfully.');
    }
}