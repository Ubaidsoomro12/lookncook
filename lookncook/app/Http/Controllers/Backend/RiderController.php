<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RiderController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role_id != 1) {
                return redirect('/')->withErrors(['email' => 'You do not have administrative privileges.']);
            }
            return $next($request);
        });
    }

    public function index()
    {
        $riders = Rider::orderBy('name')->paginate(15);
        return view('admin.pages.riders.index', compact('riders'));
    }

    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        $riders = Rider::where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('vehicle_number', 'like', "%{$q}%")
                    ->orWhere('city', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->get()
            ->map(function ($r) {
                return [
                    'id'             => $r->id,
                    'name'           => $r->name,
                    'email'          => $r->email,
                    'phone'          => $r->phone,
                    'address'        => $r->address,
                    'city'           => $r->city,
                    'image_url'      => $r->image ? asset($r->image) : null,
                    'vehicle_type'   => $r->vehicle_type,
                    'vehicle_number' => $r->vehicle_number,
                    'is_active'      => $r->is_active,
                    'edit_url'       => route('admin.riders.edit', $r->id),
                    'delete_url'     => route('admin.riders.destroy', $r->id),
                    'toggle_url'     => route('admin.riders.toggle-status', $r->id),
                ];
            });

        return response()->json(['riders' => $riders]);
    }

    public function create()
    {
        return view('admin.pages.riders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'nullable|email|max:255|unique:riders,email',
            'phone'              => 'required|string|max:20',
            'address'            => 'required|string',
            'city'               => 'nullable|string|max:100',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'vehicle_type'       => 'required|in:bike,car,van,bicycle',
            'vehicle_number'     => 'nullable|string|max:50',
            'license_number'     => 'nullable|string|max:100',
            'cnic'               => 'nullable|string|max:20',
            'emergency_contact'  => 'nullable|string|max:20',
            'joining_date'       => 'nullable|date',
            'notes'              => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $this->storeImage($request->file('image'));
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        Rider::create($validated);

        return redirect()->route('admin.riders.index')
            ->with('success', 'Rider added successfully.');
    }

    public function edit($id)
    {
        $rider = Rider::findOrFail($id);
        return view('admin.pages.riders.edit', compact('rider'));
    }

    public function update(Request $request, $id)
    {
        $rider = Rider::findOrFail($id);

        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'email'              => 'nullable|email|max:255|unique:riders,email,' . $rider->id,
            'phone'              => 'required|string|max:20',
            'address'            => 'required|string',
            'city'               => 'nullable|string|max:100',
            'image'              => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'vehicle_type'       => 'required|in:bike,car,van,bicycle',
            'vehicle_number'     => 'nullable|string|max:50',
            'license_number'     => 'nullable|string|max:100',
            'cnic'               => 'nullable|string|max:20',
            'emergency_contact'  => 'nullable|string|max:20',
            'joining_date'       => 'nullable|date',
            'notes'              => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteImageFile($rider->image);
            $validated['image'] = $this->storeImage($request->file('image'));
        } elseif ($request->boolean('remove_image')) {
            $this->deleteImageFile($rider->image);
            $validated['image'] = null;
        } else {
            $validated['image'] = $rider->image;
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $rider->update($validated);

        return redirect()->route('admin.riders.index')
            ->with('success', 'Rider updated successfully.');
    }

    public function destroy($id)
    {
        $rider = Rider::findOrFail($id);

        // Uncomment if you add the orders() relation in the Rider model
        // if ($rider->orders()->exists()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'This rider has assigned orders and cannot be deleted. Deactivate instead.',
        //     ], 422);
        // }

        $this->deleteImageFile($rider->image);
        $rider->delete();

        return response()->json(['success' => true]);
    }

    public function toggleStatus($id)
    {
        $rider = Rider::findOrFail($id);
        $rider->is_active = !$rider->is_active;
        $rider->save();

        return response()->json([
            'success'   => true,
            'is_active' => $rider->is_active,
        ]);
    }

    private function storeImage($file): string
    {
        $destination = public_path('rider-images');
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return 'rider-images/' . $filename;
    }

    private function deleteImageFile(?string $imagePath): void
    {
        if ($imagePath) {
            $fullPath = public_path($imagePath);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }
}