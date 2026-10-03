<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Review;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    // Submit review (frontend)
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
        }
    }

    // Admin: Reject review
    public function reject($id)
    {
        try {
            $review = Review::findOrFail($id);
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
}