<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Waiter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class WaiterController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $waiters = Waiter::latest()->paginate(10);
        return view('admin.pages.waiters.index', compact('waiters'));
    }

    /**
     * Search waiters via AJAX.
     */
    public function search(Request $request)
    {
        $q = trim($request->query('q', ''));

        $waiters = Waiter::when($q !== '', function ($query) use ($q) {
            $query->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('phone', 'like', "%{$q}%")
                ->orWhere('cnic', 'like', "%{$q}%")
                ->orWhere('address', 'like', "%{$q}%");
        })
            ->latest()
            ->get();

        return response()->json([
            'waiters' => $waiters->map(function ($w) {
                return [
                    'id' => $w->id,
                    'name' => $w->name,
                    'email' => $w->email,
                    'phone' => $w->phone,
                    'cnic' => $w->cnic,
                    'image' => $w->image ? asset($w->image) : null,
                    'cnic_front_image' => $w->cnic_front_image ? asset($w->cnic_front_image) : null,
                    'cnic_back_image' => $w->cnic_back_image ? asset($w->cnic_back_image) : null,
                    'cv_image' => $w->cv_image ? asset($w->cv_image) : null,
                    'appointment_letter_image' => $w->appointment_letter_image ? asset($w->appointment_letter_image) : null,
                    'address' => $w->address,
                    'hire_date' => $w->hire_date ? $w->hire_date->format('Y-m-d') : null,
                    'salary' => $w->salary,
                    'status' => $w->status,
                    'edit_url' => route('admin.waiter.edit', $w->id),
                    'delete_url' => route('admin.waiter.destroy', $w->id),
                ];
            }),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.waiters.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:waiters,email',
            'phone' => 'required|string|max:20|regex:/^[0-9+\-\s()]*$/',
            'cnic' => 'nullable|string|max:20|regex:/^[0-9-]*$/|unique:waiters,cnic',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cnic_front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cnic_back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cv_image' => 'nullable|file|mimes:pdf,png,jpeg,jpg,jfif|max:5120',
            'appointment_letter_image' => 'nullable|file|mimes:pdf,png,jpeg,jpg,jfif|max:5120',
            'address' => 'nullable|string|max:500',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0|max:999999999.99',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Waiter name is required.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'phone.regex' => 'Phone number can only contain numbers, spaces, +, - and ().',
            'cnic.max' => 'CNIC cannot exceed 20 characters.',
            'cnic.regex' => 'CNIC can only contain numbers and hyphens.',
            'cnic.unique' => 'This CNIC is already registered.',
            'image.image' => 'Profile image must be an image file.',
            'image.mimes' => 'Profile image must be jpeg, png, jpg, gif, or webp.',
            'image.max' => 'Profile image size must not exceed 2MB.',
            'cnic_front_image.image' => 'CNIC front image must be an image file.',
            'cnic_front_image.mimes' => 'CNIC front image must be jpeg, png, jpg, gif, or webp.',
            'cnic_front_image.max' => 'CNIC front image size must not exceed 2MB.',
            'cnic_back_image.image' => 'CNIC back image must be an image file.',
            'cnic_back_image.mimes' => 'CNIC back image must be jpeg, png, jpg, gif, or webp.',
            'cnic_back_image.max' => 'CNIC back image size must not exceed 2MB.',
            'cv_image.file' => 'CV must be a valid file.',
            'cv_image.mimes' => 'CV must be in PDF, PNG, JPEG, JPG, or JFIF format.',
            'cv_image.max' => 'CV file size must not exceed 5MB.',
            'appointment_letter_image.file' => 'Appointment letter must be a valid file.',
            'appointment_letter_image.mimes' => 'Appointment letter must be in PDF, PNG, JPEG, JPG, or JFIF format.',
            'appointment_letter_image.max' => 'Appointment letter file size must not exceed 5MB.',
            'hire_date.required' => 'Hire date is required.',
            'salary.required' => 'Salary is required.',
            'salary.numeric' => 'Salary must be a valid number.',
            'salary.max' => 'Salary cannot exceed 999,999,999.99.',
            'status.required' => 'Please select a status.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle image uploads
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->storeImage($request->file('image'), 'profile');
        }

        $cnicFrontPath = null;
        if ($request->hasFile('cnic_front_image')) {
            $cnicFrontPath = $this->storeImage($request->file('cnic_front_image'), 'cnic');
        }

        $cnicBackPath = null;
        if ($request->hasFile('cnic_back_image')) {
            $cnicBackPath = $this->storeImage($request->file('cnic_back_image'), 'cnic');
        }

        $cvPath = null;
        if ($request->hasFile('cv_image')) {
            $cvPath = $this->storeImage($request->file('cv_image'), 'documents');
        }

        $appointmentLetterPath = null;
        if ($request->hasFile('appointment_letter_image')) {
            $appointmentLetterPath = $this->storeImage($request->file('appointment_letter_image'), 'documents');
        }

        $waiter = Waiter::create([
            'name' => trim($request->name),
            'email' => trim($request->email),
            'phone' => trim($request->phone),
            'cnic' => trim($request->cnic),
            'image' => $imagePath,
            'cnic_front_image' => $cnicFrontPath,
            'cnic_back_image' => $cnicBackPath,
            'cv_image' => $cvPath,
            'appointment_letter_image' => $appointmentLetterPath,
            'address' => trim($request->address),
            'hire_date' => $request->hire_date,
            'salary' => $request->salary,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.waiter.index')
            ->with('success', 'Waiter created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $waiter = Waiter::findOrFail($id);
        return view('admin.pages.waiters.edit', compact('waiter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $waiter = Waiter::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:waiters,email,' . $id,
            'phone' => 'required|string|max:20|regex:/^[0-9+\-\s()]*$/',
            'cnic' => 'nullable|string|max:20|regex:/^[0-9-]*$/|unique:waiters,cnic,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cnic_front_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cnic_back_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cv_image' => 'nullable|file|mimes:pdf,png,jpeg,jpg,jfif|max:5120',
            'appointment_letter_image' => 'nullable|file|mimes:pdf,png,jpeg,jpg,jfif|max:5120',
            'address' => 'nullable|string|max:500',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric|min:0|max:999999999.99',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Waiter name is required.',
            'email.required' => 'Email address is required.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'phone.regex' => 'Phone number can only contain numbers, spaces, +, - and ().',
            'cnic.max' => 'CNIC cannot exceed 20 characters.',
            'cnic.regex' => 'CNIC can only contain numbers and hyphens.',
            'cnic.unique' => 'This CNIC is already registered.',
            'image.image' => 'Profile image must be an image file.',
            'image.mimes' => 'Profile image must be jpeg, png, jpg, gif, or webp.',
            'image.max' => 'Profile image size must not exceed 2MB.',
            'cnic_front_image.image' => 'CNIC front image must be an image file.',
            'cnic_front_image.mimes' => 'CNIC front image must be jpeg, png, jpg, gif, or webp.',
            'cnic_front_image.max' => 'CNIC front image size must not exceed 2MB.',
            'cnic_back_image.image' => 'CNIC back image must be an image file.',
            'cnic_back_image.mimes' => 'CNIC back image must be jpeg, png, jpg, gif, or webp.',
            'cnic_back_image.max' => 'CNIC back image size must not exceed 2MB.',
            'cv_image.file' => 'CV must be a valid file.',
            'cv_image.mimes' => 'CV must be in PDF, PNG, JPEG, JPG, or JFIF format.',
            'cv_image.max' => 'CV file size must not exceed 5MB.',
            'appointment_letter_image.file' => 'Appointment letter must be a valid file.',
            'appointment_letter_image.mimes' => 'Appointment letter must be in PDF, PNG, JPEG, JPG, or JFIF format.',
            'appointment_letter_image.max' => 'Appointment letter file size must not exceed 5MB.',
            'hire_date.required' => 'Hire date is required.',
            'salary.required' => 'Salary is required.',
            'salary.numeric' => 'Salary must be a valid number.',
            'salary.max' => 'Salary cannot exceed 999,999,999.99.',
            'status.required' => 'Please select a status.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Handle image uploads
        $imagePath = $waiter->image;
        if ($request->hasFile('image')) {
            if ($waiter->image) {
                $this->deleteImage($waiter->image);
            }
            $imagePath = $this->storeImage($request->file('image'), 'profile');
        }

        $cnicFrontPath = $waiter->cnic_front_image;
        if ($request->hasFile('cnic_front_image')) {
            if ($waiter->cnic_front_image) {
                $this->deleteImage($waiter->cnic_front_image);
            }
            $cnicFrontPath = $this->storeImage($request->file('cnic_front_image'), 'cnic');
        }

        $cnicBackPath = $waiter->cnic_back_image;
        if ($request->hasFile('cnic_back_image')) {
            if ($waiter->cnic_back_image) {
                $this->deleteImage($waiter->cnic_back_image);
            }
            $cnicBackPath = $this->storeImage($request->file('cnic_back_image'), 'cnic');
        }

        $cvPath = $waiter->cv_image;
        if ($request->hasFile('cv_image')) {
            if ($waiter->cv_image) {
                $this->deleteImage($waiter->cv_image);
            }
            $cvPath = $this->storeImage($request->file('cv_image'), 'documents');
        }

        $appointmentLetterPath = $waiter->appointment_letter_image;
        if ($request->hasFile('appointment_letter_image')) {
            if ($waiter->appointment_letter_image) {
                $this->deleteImage($waiter->appointment_letter_image);
            }
            $appointmentLetterPath = $this->storeImage($request->file('appointment_letter_image'), 'documents');
        }

        $updateData = [
            'name' => trim($request->name),
            'email' => trim($request->email),
            'phone' => trim($request->phone),
            'cnic' => trim($request->cnic),
            'image' => $imagePath,
            'cnic_front_image' => $cnicFrontPath,
            'cnic_back_image' => $cnicBackPath,
            'cv_image' => $cvPath,
            'appointment_letter_image' => $appointmentLetterPath,
            'address' => trim($request->address),
            'hire_date' => $request->hire_date,
            'salary' => $request->salary,
            'status' => $request->status,
        ];

        $waiter->update($updateData);

        return redirect()->route('admin.waiter.index')
            ->with('success', 'Waiter updated successfully.');
    }

    /**
     * Remove the specified resource from storage (AJAX with JSON response).
     */
    public function destroy($id)
    {
        try {
            $waiter = Waiter::find($id);
            
            if (!$waiter) {
                return response()->json([
                    'success' => false,
                    'message' => 'Waiter not found!'
                ], 404);
            }

            // Delete all images if exists
            if ($waiter->image) {
                $this->deleteImage($waiter->image);
            }
            if ($waiter->cnic_front_image) {
                $this->deleteImage($waiter->cnic_front_image);
            }
            if ($waiter->cnic_back_image) {
                $this->deleteImage($waiter->cnic_back_image);
            }
            if ($waiter->cv_image) {
                $this->deleteImage($waiter->cv_image);
            }
            if ($waiter->appointment_letter_image) {
                $this->deleteImage($waiter->appointment_letter_image);
            }

            $waiter->delete();

            return response()->json([
                'success' => true,
                'message' => 'Waiter deleted successfully!',
                'id' => $id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting waiter: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store image in respective folders
     */
    private function storeImage($file, $type = 'profile')
    {
        $folder = 'waiters';

        // Determine subfolder based on type
        if ($type === 'cnic') {
            $folder = 'waiters/cnic';
        } elseif ($type === 'documents') {
            $folder = 'waiters/documents';
        } else {
            $folder = 'waiters/profile';
        }

        $destination = public_path($folder);
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
        $file->move($destination, $filename);

        return $folder . '/' . $filename;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $waiter = Waiter::findOrFail($id);
        return view('admin.pages.waiters.view', compact('waiter'));
    }

    /**
     * Delete image from public folder
     */
    private function deleteImage($path)
    {
        if ($path && File::exists(public_path($path))) {
            File::delete(public_path($path));
        }
    }
}