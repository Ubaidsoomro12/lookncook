<?php

namespace App\Http\Controllers\pos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PosTable;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PosTableController extends Controller
{
    /**
     * Display a listing of the tables.
     */
    public function index()
    {
        $tables = PosTable::orderBy('table_number')->paginate(20);
        return view('admin.pages.pos_table.index', compact('tables'));
    }

    /**
     * Show the form for creating a new table.
     */
    public function create()
    {
        return view('admin.pages.pos_table.create');
    }

    /**
     * Store a newly created table in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'table_number' => 'required|string|max:50|unique:pos_tables,table_number',
            'table_name' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1|max:50',
            'location' => 'nullable|string|max:100',
            'section' => 'nullable|string|max:50',
            'description' => 'nullable|string',
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
            'capacity' => $request->capacity,
            'location' => $request->location,
            'section' => $request->section,
            'description' => $request->description,
            'status' => $request->status,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.pos_tables.index')
            ->with('success', 'Table created successfully!');
    }

    /**
     * Display the specified table.
     */
    public function show($id)
    {
        $table = PosTable::findOrFail($id);
        return view('admin.pages.pos_table.show', compact('table'));
    }

    /**
     * Show the form for editing the specified table.
     */
    public function edit($id)
    {
        $table = PosTable::findOrFail($id);
        return view('admin.pages.pos_table.edit', compact('table'));
    }

    /**
     * Update the specified table in storage.
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
            'capacity' => 'required|integer|min:1|max:50',
            'location' => 'nullable|string|max:100',
            'section' => 'nullable|string|max:50',
            'description' => 'nullable|string',
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
            'capacity' => $request->capacity,
            'location' => $request->location,
            'section' => $request->section,
            'description' => $request->description,
            'status' => $request->status,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()->route('admin.pos_tables.index')
            ->with('success', 'Table updated successfully!');
    }

    /**
     * Remove the specified table from storage.
     */
    public function destroy($id)
    {
        $table = PosTable::findOrFail($id);
        
        // Check if table has active orders
        if ($table->activeOrder) {
            return redirect()->route('admin.pos_tables.index')
                ->with('error', 'Cannot delete table with active orders!');
        }

        $table->delete();

        return redirect()->route('admin.pos_tables.index')
            ->with('success', 'Table deleted successfully!');
    }

    /**
     * Update table status.
     */
    public function updateStatus(Request $request, $id)
    {
        $table = PosTable::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:available,occupied,reserved,maintenance',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $table->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Table status updated successfully!',
            'status' => $table->status,
            'status_badge' => $table->status_badge,
        ]);
    }

    /**
     * Search tables via AJAX.
     */
    public function search(Request $request)
    {
        $query = $request->get('query');
        
        $tables = PosTable::where('table_number', 'LIKE', "%{$query}%")
                    ->orWhere('table_name', 'LIKE', "%{$query}%")
                    ->orWhere('location', 'LIKE', "%{$query}%")
                    ->orWhere('section', 'LIKE', "%{$query}%")
                    ->where('is_active', true)
                    ->limit(20)
                    ->get();

        return response()->json($tables);
    }
}