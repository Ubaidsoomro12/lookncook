<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role_id != 1) {
                return redirect('/')->withErrors([
                    'email' => 'You do not have administrative privileges to access this area.'
                ]);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.pages.categories.index', compact('categories'));
    }

    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        $categories = Category::when($q !== '', function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('slug', 'like', "%{$q}%")
                      ->orWhere('description', 'like', "%{$q}%");
            })
            ->latest()
            ->get(['id', 'name', 'description', 'slug', 'image', 'status']);

        return response()->json([
            'categories' => $categories->map(function ($cat) {
                return [
                    'id'          => $cat->id,
                    'name'        => $cat->name,
                    'description' => $cat->description,
                    'slug'        => $cat->slug,
                    'status'      => $cat->status,
                    'image'       => $cat->image ? asset($cat->image) : null,
                    'edit_url'    => route('admin.categories.edit', $cat->id),
                    'delete_url'  => route('admin.categories.destroy', $cat->id),
                ];
            }),
        ]);
    }

    public function create()
    {
        return view('admin.pages.categories.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|min:2|max:255|unique:categories,name',
            'description' => [
                'required',
                'string',
                'max:1000',
                function ($attribute, $value, $fail) {
                    if (strlen(trim((string) $value)) < 10) {
                        $fail('The description must be at least 10 characters (excluding spaces).');
                    }
                },
            ],
            'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status'      => 'required|in:active,inactive',
        ], [
            'name.required'        => 'Category name is required.',
            'name.min'              => 'Category name must be at least 2 characters.',
            'name.unique'           => 'A category with this name already exists.',
            'description.required' => 'Description is required.',
            'image.required'       => 'Category image is required.',
            'image.image'          => 'The file must be an image.',
            'image.mimes'          => 'Image must be jpg, jpeg, png, or webp.',
            'image.max'            => 'Image size must not exceed 4MB.',
            'status.required'      => 'Please select a status.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $slug = Str::slug($request->input('name'));
        $original = $slug;
        $i = 1;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $original . '-' . $i++;
        }

        $imagePath = $this->storeImage($request->file('image'));
        $descriptionValue = trim((string) $request->input('description'));

        $newCategory = new Category();
        $newCategory->name        = trim((string) $request->input('name'));
        $newCategory->description = $descriptionValue;
        $newCategory->slug        = $slug;
        $newCategory->image       = $imagePath;
        $newCategory->status      = $request->input('status');
        $newCategory->save();

        // Verify what actually landed in the DB
        Log::info('CATEGORY STORE - Fresh from DB after save:', [
            'id' => $newCategory->id,
            'description' => $newCategory->fresh()->description,
        ]);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.pages.categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name'        => 'required|string|min:2|max:255|unique:categories,name,' . $category->id,
            'description' => [
                'required',
                'string',
                'max:1000',
                function ($attribute, $value, $fail) {
                    if (strlen(trim((string) $value)) < 10) {
                        $fail('The description must be at least 10 characters (excluding spaces).');
                    }
                },
            ],
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'status'      => 'required|in:active,inactive',
        ], [
            'name.required'        => 'Category name is required.',
            'name.min'              => 'Category name must be at least 2 characters.',
            'name.unique'           => 'A category with this name already exists.',
            'description.required' => 'Description is required.',
            'image.image'          => 'The file must be an image.',
            'image.mimes'          => 'Image must be jpg, jpeg, png, or webp.',
            'image.max'            => 'Image size must not exceed 4MB.',
            'status.required'      => 'Please select a status.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->input('name') !== $category->name) {
            $slug = Str::slug($request->input('name'));
            $original = $slug;
            $i = 1;
            while (Category::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $original . '-' . $i++;
            }
            $category->slug = $slug;
        }

        if ($request->hasFile('image')) {
            $this->deleteImage($category->image);
            $category->image = $this->storeImage($request->file('image'));
        }

        $category->name        = trim((string) $request->input('name'));
        $category->description = trim((string) $request->input('description'));
        $category->status      = $request->input('status');
        $category->save();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $this->deleteImage($category->image);
        $category->delete();

        return response()->json(['success' => true]);
    }

    private function storeImage($file)
    {
        $destination = public_path('categories');
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'categories/' . $filename;
    }

    private function deleteImage($path)
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}