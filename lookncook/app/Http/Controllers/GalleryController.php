<?php


namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::first();
        return view('admin.pages.gallery.index', compact('gallery'));
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        // Validation Rules
        $rules = [
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image1_title' => 'nullable|string|max:255',
            'image2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image2_title' => 'nullable|string|max:255',
            'image3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image3_title' => 'nullable|string|max:255',
            'gallery_img1' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_img1_title' => 'nullable|string|max:255',
            'gallery_img2' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_img2_title' => 'nullable|string|max:255',
            'gallery_img3' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_img3_title' => 'nullable|string|max:255',
            'gallery_img4' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_img4_title' => 'nullable|string|max:255',
            'gallery_img5' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_img5_title' => 'nullable|string|max:255',
            'gallery_img6' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_img6_title' => 'nullable|string|max:255',
            'gallery_img7' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_img7_title' => 'nullable|string|max:255',
            'gallery_img8' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_img8_title' => 'nullable|string|max:255',
            'gallery_img9' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_img9_title' => 'nullable|string|max:255',
        ];

        $request->validate($rules);

        // All image fields
        $imageFields = [
            'image1', 'image2', 'image3',
            'gallery_img1', 'gallery_img2', 'gallery_img3',
            'gallery_img4', 'gallery_img5', 'gallery_img6',
            'gallery_img7', 'gallery_img8', 'gallery_img9'
        ];

        // Create folder if not exists
        if (!file_exists(public_path('gallery_images'))) {
            mkdir(public_path('gallery_images'), 0777, true);
        }

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old image
                if ($gallery->$field && file_exists(public_path($gallery->$field))) {
                    unlink(public_path($gallery->$field));
                }
                
                // Upload to public/gallery_images
                $file = $request->file($field);
                $filename = time() . '_' . $field . '_' . $file->getClientOriginalName();
                $file->move(public_path('gallery_images'), $filename);
                $gallery->$field = 'gallery_images/' . $filename;
            }
        }

        // Update text fields
        $textFields = [
            'title', 'subtitle', 'description',
            'image1_title', 'image2_title', 'image3_title',
            'gallery_img1_title', 'gallery_img2_title', 'gallery_img3_title',
            'gallery_img4_title', 'gallery_img5_title', 'gallery_img6_title',
            'gallery_img7_title', 'gallery_img8_title', 'gallery_img9_title'
        ];

        foreach ($textFields as $field) {
            $gallery->$field = $request->$field;
        }

        $gallery->save();

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Gallery updated successfully!');
    }
}