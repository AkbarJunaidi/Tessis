<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventories = Inventory::latest()->get();

        return view('inventories.index', compact('inventories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventories.create');
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'required|string|max:255|unique:inventories,serial_number',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('inventories', 'public');
        }

        Inventory::create([
            'name' => $request->name,
            'description' => $request->description,
            'serial_number' => $request->serial_number,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('inventories.index')
            ->with('success', 'Data inventory berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inventory $inventory)
    {
        return redirect()->route('inventories.edit', $inventory);
    }

    /**
     * Show the form for editing the resource.
     */
    public function edit(Inventory $inventory)
    {
        return view('inventories.edit', compact('inventory'));
    }

    /**
     * Update the resource.
     */
    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'serial_number' => 'required|string|max:255|unique:inventories,serial_number,' . $inventory->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $inventory->image;

        if ($request->hasFile('image')) {

            if ($inventory->image && Storage::disk('public')->exists($inventory->image)) {
                Storage::disk('public')->delete($inventory->image);
            }

            $imagePath = $request->file('image')->store('inventories', 'public');
        }

        $inventory->update([
            'name' => $request->name,
            'description' => $request->description,
            'serial_number' => $request->serial_number,
            'image' => $imagePath,
        ]);

        return redirect()
            ->route('inventories.index')
            ->with('success', 'Data inventory berhasil diperbarui.');
    }

    /**
     * Remove the resource.
     */
    public function destroy(Inventory $inventory)
    {
        if ($inventory->image && Storage::disk('public')->exists($inventory->image)) {
            Storage::disk('public')->delete($inventory->image);
        }

        $inventory->delete();

        return redirect()
            ->route('inventories.index')
            ->with('success', 'Data inventory berhasil dihapus.');
    }
}