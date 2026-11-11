<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AlumniController extends Controller
{
    public function index(Request $request)
    {
        $query = Alumni::latest();

        if ($request->filled('filter') && in_array($request->filter, ['active', 'inactive', 'suspended'])) {
            $query->where('status', $request->filter);
        }

        $alumnis = $query->paginate(10);
        return view('dashboard.alumni.index', compact('alumnis'));
    }

    public function create()
    {
        return view('dashboard.alumni.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:alumnis,email',
            'password' => 'required|min:6',
            'status' => 'required|string|in:active,inactive,suspended',
            'course_id' => 'required|exists:courses,id',
            'image_base64' => 'required',
        ]);

        $imagePath = null;
        if ($request->filled('image_base64')) {
            $base64Image = $request->image_base64;
            list($type, $base64Image) = explode(';', $base64Image);
            list(, $base64Image) = explode(',', $base64Image);
            $imageDecoded = base64_decode($base64Image);

            $destinationPath = public_path('uploads/alumni');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $imageName = time() . '_' . uniqid() . '.jpg';
            file_put_contents($destinationPath . '/' . $imageName, $imageDecoded);
            $imagePath = 'uploads/alumni/' . $imageName;
        }

        // Generate sequential or formatted registration number like MEA-000013
        $latestAlumni = Alumni::latest('id')->first();
        $nextId = $latestAlumni ? $latestAlumni->id + 1 : 1;
        $registerNo = 'MEA-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);

        Alumni::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $request->status,
            'course_id' => $request->course_id,
            'image' => $imagePath,
            'register_no' => $registerNo,
        ]);

        return redirect()->route('admin.alumni.index')
            ->with('success', 'Alumni record created successfully.');
    }

    public function edit(Alumni $alumni)
    {
        return view('dashboard.alumni.edit', compact('alumni'));
    }

    public function update(Request $request, Alumni $alumni)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:alumnis,email,' . $alumni->id,
            'status' => 'required|string|in:active,inactive,suspended',
            'course_id' => 'required|exists:courses,id',
            'image_base64' => 'nullable',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'status' => $request->status,
            'course_id' => $request->course_id,
        ];

        if ($request->filled('image_base64')) {
            if ($alumni->image && file_exists(public_path($alumni->image))) {
                @unlink(public_path($alumni->image));
            }

            $base64Image = $request->image_base64;
            list($type, $base64Image) = explode(';', $base64Image);
            list(, $base64Image) = explode(',', $base64Image);
            $imageDecoded = base64_decode($base64Image);

            $destinationPath = public_path('uploads/alumni');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            $imageName = time() . '_' . uniqid() . '.jpg';
            file_put_contents($destinationPath . '/' . $imageName, $imageDecoded);
            $data['image'] = 'uploads/alumni/' . $imageName;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $alumni->update($data);

        return redirect()->route('admin.alumni.index')
            ->with('success', 'Alumni record updated successfully.');
    }

    public function destroy(Alumni $alumni)
    {
        if ($alumni->image && file_exists(public_path($alumni->image))) {
            @unlink(public_path($alumni->image));
        }
        
        $alumni->delete();

        return redirect()->route('admin.alumni.index')
            ->with('success', 'Alumni record deleted successfully.');
    }
}