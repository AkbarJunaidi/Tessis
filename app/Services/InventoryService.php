<?php

namespace App\Services;

use App\Models\Inventory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLog\ActivityLogService;

class InventoryService
{
    /**
     * @var ActivityLogService
     */
    protected $activityLogService;

    /**
     * Mendaftarkan ActivityLogService ke dalam Constructor melalui Dependency Injection.
     */
    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

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
        $inventory = Inventory::create([
            'name' => $data['name'],
            'serial_number' => $data['serial_number'],
            'image' => $imagePath,
            'qr_code' => null, // Folder dan kode QR otomatis di-generate pada tahap 14
        ]);

        // Pemicu Log Audit Trail untuk aksi Create Inventory
        $this->activityLogService->log(
            Auth::id(),
            'Inventory',
            'Menambahkan aset barang baru: ' . $inventory->name . ' (S/N: ' . $inventory->serial_number . ')'
        );

        return $inventory;
    }

    /**
     * Memproses pembaruan data inventory.
     */
    public function updateInventory(Inventory $inventory, array $data, ?\Illuminate\Http\UploadedFile $imageFile = null): Inventory
    {
        $oldName = $inventory->name;
        $oldSerialNumber = $inventory->serial_number;

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

        // Pemicu Log Audit Trail untuk aksi Update Inventory
        $this->activityLogService->log(
            Auth::id(),
            'Inventory',
            'Mengubah aset barang "' . $oldName . '" (S/N: ' . $oldSerialNumber . ') menjadi "' . $inventory->name . '" (S/N: ' . $inventory->serial_number . ')'
        );

        return $inventory;
    }

    /**
     * Memproses penghapusan data secara aman (Soft Delete).
     */
    public function deleteInventory(Inventory $inventory): bool
    {
        $itemName = $inventory->name;
        $itemSerialNumber = $inventory->serial_number;

        // Berkas fisik gambar sengaja tidak dihapus saat Soft Delete agar bisa direstore kembali jika dibutuhkan
        $deleted = $inventory->delete();

        if ($deleted) {
            // Pemicu Log Audit Trail untuk aksi Delete Inventory
            $this->activityLogService->log(
                Auth::id(),
                'Inventory',
                'Melakukan soft delete pada aset barang: ' . $itemName . ' (S/N: ' . $itemSerialNumber . ')'
            );
        }

        return $deleted;
    }
}
