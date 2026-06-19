<?php

namespace App\Services;

use App\Models\Inventory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class InventoryService
{
    /**
     * Mengambil daftar data inventory dengan paginasi.
     */
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Inventory::latest()->paginate($perPage);
    }

    /**
     * Memproses penyimpanan data inventory baru beserta unggahan gambar.
     */
    public function createInventory(array $data, ?\Illuminate\Http\UploadedFile $imageFile = null): Inventory
    {
        $imagePath = null;

        // Jika user memilih untuk mengunggah gambar opsional
        if ($imageFile) {
            // Simpan gambar ke storage/app/public/assets/inventory/images
            $imagePath = $imageFile->store('assets/inventory/images', 'public');
        }

        // Simpan rekaman data ke database
        return Inventory::create([
            'name' => $data['name'],
            'serial_number' => $data['serial_number'],
            'image' => $imagePath,
            'qr_code' => null, // Folder dan kode QR otomatis di-generate pada tahap 14
        ]);
    }

    /**
     * Memproses pembaruan data inventory.
     */
    public function updateInventory(Inventory $inventory, array $data, ?\Illuminate\Http\UploadedFile $imageFile = null): Inventory
    {
        if ($imageFile) {
            // Hapus gambar lama dari disk public jika ada
            if ($inventory->image) {
                Storage::disk('public')->delete($inventory->image);
            }
            // Simpan gambar yang baru
            $inventory->image = $imageFile->store('assets/inventory/images', 'public');
        }

        $inventory->name = $data['name'];
        $inventory->serial_number = $data['serial_number'];
        $inventory->save();

        return $inventory;
    }

    /**
     * Memproses penghapusan data secara aman (Soft Delete).
     */
    public function deleteInventory(Inventory $inventory): bool
    {
        // Berkas fisik gambar sengaja tidak dihapus saat Soft Delete agar bisa direstore kembali jika dibutuhkan
        return $inventory->delete();
    }
}
