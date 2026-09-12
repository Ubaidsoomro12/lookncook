<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::all();
        return view('admin.pages.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('admin.pages.branches.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'branch_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'nullable|in:active,inactive'
        ]);

        Branch::create($request->all());

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch created successfully!');
    }

    public function show($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.pages.branches.show', compact('branch'));
    }

    public function edit($id)
    {
        $branch = Branch::findOrFail($id);
        return view('admin.pages.branches.edit', compact('branch'));
    }

    public function update(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);

        $request->validate([
            'branch_name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'nullable|in:active,inactive'
        ]);

        $branch->update($request->all());

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch updated successfully!');
    }

    public function destroy($id)
    {
        $branch = Branch::findOrFail($id);
        $branch->delete();

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch deleted successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $branch = Branch::findOrFail($id);
        $branch->status = $request->status;
        $branch->save();

        return response()->json(['success' => true, 'message' => 'Status updated successfully!']);
    }

    public function search(Request $request)
    {
        $search = $request->get('q');
        $branches = Branch::where('branch_name', 'LIKE', "%{$search}%")
            ->orWhere('address', 'LIKE', "%{$search}%")
            ->get();

        return response()->json($branches);
    }
}