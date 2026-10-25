<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;
use App\Models\Staff; 
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'profession' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
            'consent' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $review = Review::create([
                'name' => $request->name,
                'email' => $request->email,
                'profession' => $request->profession,
                'message' => $request->message,
                'rating' => $request->rating,
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully!',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Admin: Show all reviews
    public function index()
    {
        $reviews = Review::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.pages.reviews.index', compact('reviews'));
    }

    // Admin: Show ONLY Staff Reviews (With Live Search)
    public function staffReviewsIndex(Request $request)
    {
        $query = Review::whereNotNull('staff_id');

        // Search Logic
        if ($request->has('search') && $request->search != '') {
            $search = strtolower(trim($request->search));
            
            // Map performance words to their numeric ratings
            $ratingMap = [
                'poor' => 1,
                'average' => 2,
                'good' => 3,
                'very good' => 4,
                'verygood' => 4,
                'excellent' => 5
            ];

            $query->where(function($q) use ($search, $ratingMap) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('staff_id', 'LIKE', "%{$search}%")
                  ->orWhere('staff_review', 'LIKE', "%{$search}%")
                  ->orWhereDate('created_at', 'LIKE', "%{$search}%");
                  
                // If the search matches a performance word, search the rating column by its number
                if (array_key_exists($search, $ratingMap)) {
                    $q->orWhere('staff_rating', $ratingMap[$search]);
                } else {
                    // Otherwise, just try to match the number as a string
                    $q->orWhere('staff_rating', 'LIKE', "%{$search}%");
                }
            });
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        // Return the view directly. The JavaScript handles extracting only the table part.
        return view('admin.pages.reviews.staff_index', compact('reviews'));
    }

    // Staff: Show only MY reviews (Read-only)
    public function myStaffReviews()
    {
        $staff = \App\Models\Staff::where('user_id', auth()->id())->first();
        
        if (!$staff) {
            return redirect()->back()->with('error', 'Staff record not found.');
        }

        $reviews = Review::where('staff_id', $staff->id)->orderBy('created_at', 'desc')->paginate(10);
        
        return view('staff.pages.reviews.index', compact('reviews'));
    }

    // Admin: Update Staff Rating manually via Slider
    public function updateStaffRating(Request $request, $id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->staff_rating = $request->rating;
            $review->save();

            return response()->json(['success' => true, 'message' => 'Rating updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update rating.'], 500);
        }
    }

    // Admin: Approve review
    public function approve($id)
    {
        try {
            $review = Review::findOrFail($id);

            if ($review->staff_id) {
                $review->staff_status = 'good';
            } else {
                $review->status = 'good';
            }
            $review->save();

            return response()->json(['success' => true, 'message' => 'Review approved successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to approve review.'], 500);
        }
    }

    // Admin: Reject review
    public function reject($id)
    {
        try {
            $review = Review::findOrFail($id);

            if ($review->staff_id) {
                $review->staff_status = 'bad';
            } else {
                $review->status = 'bad';
            }
            $review->save();

            return response()->json(['success' => true, 'message' => 'Review rejected successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to reject review.'], 500);
        }
    }

    // Admin: Delete review
    public function destroy($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => 'Review deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete review.'
            ], 500);
        }
    }

    // Admin: Delete ALL reviews
    public function destroyAll()
    {
        try {
            Review::whereNotNull('staff_id')->delete();
            return response()->json([
                'success' => true,
                'message' => 'All reviews deleted successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete all reviews.'
            ], 500);
        }
    }


    // ✅ Staff: Submit Review (Table ID ke sath)
    public function staffReviewStore(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'message' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (count(preg_split('/\s+/', trim($value))) < 5) {
                        $fail('The message must be at least 5 words.');
                    }
                }
            ],
            'rating' => 'required|integer|min:1|max:5',
            'table_id' => 'required' // Table ID validate kar rahe hain
        ]);

        // Handle validation errors for AJAX vs standard form submit
        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Staff ID nikalne ki koshish
            $staffId = null;
            if (class_exists('\App\Models\Staff')) {
                $staffRecord = \App\Models\Staff::where('user_id', auth()->id())->first();
                if ($staffRecord) {
                    $staffId = $staffRecord->id;
                }
            }

            // Data Save in Database
            DB::table('reviews')->insert([
                // ✅ Naye Staff Columns
                'staff_id'      => $staffId,
                // 'staff_name'    => $request->name,
                'table_id'      => $request->table_id, // 👈 Yahan Table ki ID save hogi
                'staff_review'  => $request->message,
                'staff_rating'  => $request->rating,
                'staff_status'  => 'pending',
                
                // ✅ Purane Columns (Taake Database error na de)
                'name'          => $request->name,
                'email'         => 'staff-' . auth()->id() . '@lookncook.com',
                'profession'    => 'Staff Member', 
                'message'       => $request->message,
                'rating'        => $request->rating,
                'status'        => 'pending',

                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            // Handle success for AJAX vs standard form submit
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Review submitted successfully!']);
            }

            return redirect()->back()->with('success', 'Review submitted successfully! Data saved in database.');

        } catch (\Exception $e) {
            // Handle DB error for AJAX vs standard form submit
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->withErrors(['error' => 'DB Error: ' . $e->getMessage()]);
        }
    }

}