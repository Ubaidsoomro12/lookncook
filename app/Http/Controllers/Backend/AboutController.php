<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class AboutController extends Controller
{
    public function index()
    {
        $about = About::first();

        if (!$about) {
            $about = About::create([
                'title' => '',
                'subtitle' => '',
                'subdescription' => '',
                'description' => '',
            ]);
        }

        return view('admin.pages.about.index', compact('about'));
    }

    public function update(Request $request, About $about)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'subdescription' => 'nullable|string',
            'description' => 'nullable|string',
            'image1' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image2' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image3' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $destination = public_path('images');

        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        foreach ([1, 2, 3] as $slot) {
            $field = "image{$slot}";

            if ($request->hasFile($field)) {
                $file = $request->file($field);
                $filename = "about{$slot}." . $file->getClientOriginalExtension();
                $file->move($destination, $filename);
                $validated[$field] = $filename;
            } else {
                unset($validated[$field]);
            }
        }

        $about->update($validated);

        return redirect()->back()->with('success', 'About section updated successfully.');
    }
}