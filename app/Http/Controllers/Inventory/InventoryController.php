<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Http\Requests\Inventory\InventoryRequest;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InventoryController extends Controller
{
    /**
     * Menyuntikkan InventoryService melalui Constructor Injection.
     */
    public function __construct(
        protected InventoryService $inventoryService
    ) {}

    /**
     * Menampilkan daftar semua barang inventory.
     */
    public function index(): View
    {
        $inventories = $this->inventoryService->getAllPaginated(10);
        return view('inventory.index', compact('inventories'));
    }

    /**
     * Menampilkan formulir tambah barang baru.
     */
    public function create(): View
    {
        return view('inventory.create');
    }

    /**
     * Menyimpan data barang baru ke sistem.
     */
    public function store(InventoryRequest $request): RedirectResponse
    {
        $this->inventoryService->createInventory(
            $request->validated(),
            $request->file('image')
        );

        return redirect()->route('inventory.index')
            ->with('success', 'Data inventory baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan informasi detail spesifik suatu barang.
     */
    public function show(Inventory $inventory): View
    {
        return view('inventory.show', compact('inventory'));
    }

    /**
     * Menampilkan formulir edit/pembaruan data barang.
     */
    public function edit(Inventory $inventory): View
    {
        return view('inventory.edit', compact('inventory'));
    }

    /**
     * Memproses pembaruan data barang di database.
     */
    public function update(InventoryRequest $request, Inventory $inventory): RedirectResponse
    {
        $this->inventoryService->updateInventory(
            $inventory,
            $request->validated(),
            $request->file('image')
        );

        return redirect()->route('inventory.index')
            ->with('success', 'Data inventory berhasil diperbarui.');
    }

    /**
     * Menghapus barang dari daftar utama menggunakan metode Soft Delete.
     */
    public function destroy(Inventory $inventory): RedirectResponse
    {
        $this->inventoryService->deleteInventory($inventory);

        return redirect()->route('inventory.index')
            ->with('success', 'Data inventory berhasil dihapus.');
    }
}
