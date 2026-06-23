<?php

namespace App\Services\DataIntegration;

use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use Exception;

class FolderService
{
    /**
     * Menyimpan folder baru ke dalam database.
     *
     * @param array $data
     * @return Folder
     * @throws Exception
     */
    public function createFolder(array $data): Folder
    {
        try {
            return Folder::create([
                'name' => $data['name'],
                'parent_id' => $data['parent_id'] ?? null,
                'created_by' => Auth::id(),
            ]);
        } catch (Exception $e) {
            throw new Exception('Gagal membuat folder: ' . $e->getMessage());
        }
    }

    public function renameFolder(\App\Models\Folder $folder, string $newName): bool
    {
        return $folder->update(['name' => $newName]);
    }
    public function moveFolder(\App\Models\Folder $folder, ?int $targetFolderId): bool
    {
        // Validasi Defensif: Mencegah folder masuk ke dirinya sendiri
        if ($targetFolderId === $folder->id) {
            throw new \Exception('Folder tidak dapat dipindahkan ke dalam dirinya sendiri.');
        }
        return $folder->update(['parent_id' => $targetFolderId]);
    }

    public function deleteFolder(\App\Models\Folder $folder): ?bool
    {
        return $folder->delete();
    }
}
