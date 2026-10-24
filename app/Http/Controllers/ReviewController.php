<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;
<<<<<<< HEAD
use App\Models\Staff; 
=======
>>>>>>> muzamil_dev
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
<<<<<<< HEAD
    // Submit review (frontend - Customer)
=======
    // Submit review (frontend)
>>>>>>> muzamil_dev
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

    // Admin: Approve review
    public function approve($id)
    {
        try {
            $review = Review::findOrFail($id);
<<<<<<< HEAD
            if ($review->staff_id) {
                $review->staff_status = 'good';
            } else {
                $review->status = 'good';
            }
            $review->save();

            return response()->json(['success' => true, 'message' => 'Review approved successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to approve review.'], 500);
=======
            $review->status = 'approved';
            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Review approved successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve review.'
            ], 500);
>>>>>>> muzamil_dev
        }
    }

    // Admin: Reject review
    public function reject($id)
    {
        try {
            $review = Review::findOrFail($id);
<<<<<<< HEAD
            if ($review->staff_id) {
                $review->staff_status = 'bad';
            } else {
                $review->status = 'bad';
            }
            $review->save();

            return response()->json(['success' => true, 'message' => 'Review rejected successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to reject review.'], 500);
=======
            $review->status = 'rejected';
            $review->save();

            return response()->json([
                'success' => true,
                'message' => 'Review rejected successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject review.'
            ], 500);
>>>>>>> muzamil_dev
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
<<<<<<< HEAD

    // ✅ Staff: Submit Review (Table ID ke sath)
    public function staffReviewStore(Request $request)
    {
        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'rating' => 'required|integer|min:1|max:5',
            'table_id' => 'required' // Table ID validate kar rahe hain
        ]);

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
                'staff_name'    => $request->name,
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

            return redirect()->back()->with('success', 'Review submitted successfully! Data saved in database.');

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'DB Error: ' . $e->getMessage()]);
        }
    }
=======
>>>>>>> muzamil_dev
}