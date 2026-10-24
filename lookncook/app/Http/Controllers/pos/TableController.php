<?php

namespace App\Http\Controllers\pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosTable;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class TableController extends Controller
{
    /**
     * 📋 Display a listing of tables.
     */
    public function index(Request $request)
    {
        $query = PosTable::query();

        // Search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('table_number', 'LIKE', "%{$search}%")
                  ->orWhere('table_name', 'LIKE', "%{$search}%")
                  ->orWhere('branch_name', 'LIKE', "%{$search}%")
                  ->orWhere('zone', 'LIKE', "%{$search}%");
            });
        }

        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Table type filter
        if ($request->has('table_type') && !empty($request->table_type)) {
            $query->where('table_type', $request->table_type);
        }

        // Zone filter
        if ($request->has('zone') && !empty($request->zone)) {
            $query->where('zone', $request->zone);
        }

        $tables = $query->orderBy('table_number')->paginate(20);

        // Stats for cards
        $availableCount = PosTable::where('status', 'available')->where('is_active', true)->count();
        $occupiedCount = PosTable::where('status', 'occupied')->where('is_active', true)->count();
        $reservedCount = PosTable::where('status', 'reserved')->where('is_active', true)->count();

        return view('pos.pages.tables.index', compact('tables', 'availableCount', 'occupiedCount', 'reservedCount'));
    }

    /**
     * ➕ Show form for creating a new table.
     */
    public function create()
    {
        return view('pos.pages.tables.create');
    }

    /**
     * 💾 Store a newly created table.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_number' => 'required|string|max:50|unique:pos_tables,table_number',
            'table_name' => 'nullable|string|max:100',
            'branch_id' => 'nullable|integer',
            'branch_name' => 'nullable|string|max:255',
            'branch_location' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1|max:50',
            'table_type' => 'nullable|in:dining,bar,lounge,private,booth,outdoor,indoor',
            'description' => 'nullable|string',
            'zone' => 'nullable|in:male area,family area,indoor,booth,outdoor,dining',
            'floor' => 'nullable|string|max:50',
            'qr_code' => 'nullable|string|max:255',
            'status' => 'required|in:available,occupied,reserved,maintenance',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        PosTable::create([
            'table_number' => $request->table_number,
            'table_name' => $request->table_name,
            'branch_id' => $request->branch_id,
            'branch_name' => $request->branch_name,
            'branch_location' => $request->branch_location,
            'capacity' => $request->capacity,
            'table_type' => $request->table_type ?? 'dining',
            'description' => $request->description,
            'zone' => $request->zone ?? 'indoor',
            'floor' => $request->floor,
            'qr_code' => $request->qr_code,
            'status' => $request->status,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Table created successfully!');
    }

    /**
     * 🔍 Display the specified table.
     */
    public function show($id)
    {
        $table = PosTable::findOrFail($id);
        return view('pos.pages.tables.show', compact('table'));
    }

    /**
     * ✏️ Show form for editing the specified table.
     */
    public function edit($id)
    {
        $table = PosTable::findOrFail($id);
        return view('pos.pages.tables.edit', compact('table'));
    }

    /**
     * 🔄 Update the specified table.
     */
    public function update(Request $request, $id)
    {
        $table = PosTable::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'table_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('pos_tables', 'table_number')->ignore($id),
            ],
            'table_name' => 'nullable|string|max:100',
            'branch_id' => 'nullable|integer',
            'branch_name' => 'nullable|string|max:255',
            'branch_location' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1|max:50',
            'table_type' => 'nullable|in:dining,bar,lounge,private,booth,outdoor,indoor',
            'description' => 'nullable|string',
            'zone' => 'nullable|in:male area,family area,indoor,booth,outdoor,dining',
            'floor' => 'nullable|string|max:50',
            'qr_code' => 'nullable|string|max:255',
            'status' => 'required|in:available,occupied,reserved,maintenance',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $table->update([
            'table_number' => $request->table_number,
            'table_name' => $request->table_name,
            'branch_id' => $request->branch_id,
            'branch_name' => $request->branch_name,
            'branch_location' => $request->branch_location,
            'capacity' => $request->capacity,
            'table_type' => $request->table_type ?? $table->table_type,
            'description' => $request->description,
            'zone' => $request->zone ?? $table->zone,
            'floor' => $request->floor,
            'qr_code' => $request->qr_code,
            'status' => $request->status,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.tables.index')
            ->with('success', 'Table updated successfully!');
    }

    /**
     * 🗑️ Remove the specified table (AJAX with JSON response).
     */
    public function destroy($id)
    {
        try {
            // Find the table
            $table = PosTable::find($id);
            
            if (!$table) {
                return response()->json([
                    'success' => false,
                    'message' => 'Table not found!'
                ], 404);
            }

            // Delete the table
            $table->delete();

            return response()->json([
                'success' => true,
                'message' => 'Table deleted successfully!',
                'id' => $id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting table: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔄 Update table status via AJAX.
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $table = PosTable::findOrFail($id);
            
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:available,occupied,reserved,maintenance',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status'
                ], 400);
            }

            $table->update(['status' => $request->status]);

            $statusBadges = [
                'available' => '<span class="badge bg-success px-3 py-2">Available</span>',
                'occupied' => '<span class="badge bg-warning text-dark px-3 py-2">Occupied</span>',
                'reserved' => '<span class="badge bg-danger px-3 py-2">Reserved</span>',
                'maintenance' => '<span class="badge bg-secondary px-3 py-2">Maintenance</span>',
            ];

            return response()->json([
                'success' => true,
                'message' => 'Table status updated successfully!',
                'status' => $table->status,
                'status_badge' => $statusBadges[$table->status] ?? $table->status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * 🔍 Search tables via AJAX.
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', $request->get('query', ''));
            
            $tables = PosTable::where('table_number', 'LIKE', "%{$query}%")
                        ->orWhere('table_name', 'LIKE', "%{$query}%")
                        ->orWhere('branch_name', 'LIKE', "%{$query}%")
                        ->orWhere('zone', 'LIKE', "%{$query}%")
                        ->where('is_active', true)
                        ->limit(20)
                        ->get();

            return response()->json([
                'success' => true,
                'data' => $tables
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error searching tables: ' . $e->getMessage()
            ], 500);
        }
    }
}