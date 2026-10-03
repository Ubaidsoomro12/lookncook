<?php

namespace App\Http\Controllers\front;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show the profile page
     */
    public function index()
    {
        $user = Auth::user();
        
        $roleNames = [
            1 => 'Admin',
            2 => 'User',
            3 => 'Manager',
            4 => 'Waiter',
            5 => 'Chef',
            6 => 'Cashier',
            7 => 'Cleaner',
            8 => 'Delivery Rider'
        ];
        
        $roleName = $roleNames[$user->role_id] ?? 'Guest';
        
        return view('pages.profile', compact('user', 'roleName'));
    }

    /**
     * Update the user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:8|confirmed',
        ];

        $validated = $request->validate($rules);

        $updateData = [];

        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }

        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }

        if ($request->filled('phone')) {
            $updateData['phone'] = $request->phone;
        }

        if ($request->filled('city')) {
            $updateData['city'] = $request->city;
        }

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if (empty($updateData)) {
            return back()->with('info', 'No changes were made to your profile.');
        }

        $user->update($updateData);

        return back()->with('success', 'Profile updated successfully!');
    }
}