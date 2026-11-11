<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Unit;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Section::with(['unit.course'])->withCount('lessons');

        if ($request->has('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        $sections = $query
            ->orderBy('unit_id', 'asc')
            ->orderBy('order', 'asc')
            ->orderBy('id', 'asc')
            ->paginate(10);

        return view('dashboard.courses.sections.index', compact('sections'));
    }

    public function create()
    {
        $units = Unit::with('course')->orderBy('title', 'asc')->get();

        return view('dashboard.courses.sections.create', compact('units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id'     => 'required|exists:units,id',
            'title'       => 'required|string|max:255',
            'order'       => 'nullable|integer|min:1',
            'content'     => 'nullable|string',
        ]);

        Section::create($validated);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section created successfully.');
    }

    public function edit(Section $section)
    {
        $units = Unit::with('course')->orderBy('title', 'asc')->get();

        return view('dashboard.courses.sections.edit', compact('section', 'units'));
    }

    public function update(Request $request, Section $section)
    {
        $validated = $request->validate([
            'unit_id'     => 'required|exists:units,id',
            'title'       => 'required|string|max:255',
            'order'       => 'nullable|integer|min:1',
            'content'     => 'nullable|string',
        ]);

        $section->update($validated);

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section updated successfully.');
    }

    public function destroy(Section $section)
    {
        $section->delete();

        return redirect()->route('admin.sections.index')
            ->with('success', 'Section deleted successfully.');
    }
}