<?php

namespace App\Services\DataIntegration;

use App\Models\Folder;
use Illuminate\Support\Facades\Auth;
use App\Services\ActivityLog\ActivityLogService;
use Exception;

class FolderService
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
     * Menyimpan folder baru ke dalam database.
     *
     * @param array $data
     * @return Folder
     * @throws Exception
     */
    public function createFolder(array $data): Folder
    {
        try {
            $folder = Folder::create([
                'name' => $data['name'],
                'parent_id' => $data['parent_id'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // Pemicu Log Audit Trail untuk aksi Create Folder
            $context = $folder->parent_id ? 'di dalam Sub-Folder ID #' . $folder->parent_id : 'di Root Directory';
            $this->activityLogService->log(
                Auth::id(),
                'Integrasi Data',
                'Membuat folder baru dengan nama "' . $folder->name . '" ' . $context
            );

            return $folder;
        } catch (Exception $e) {
            throw new Exception('Gagal membuat folder: ' . $e->getMessage());
        }
    }

    /**
     * Mengubah nama folder.
     *
     * @param Folder $folder
     * @param string $newName
     * @return bool
     */
    public function renameFolder(Folder $folder, string $newName): bool
    {
        $oldName = $folder->name;
        $updated = $folder->update(['name' => $newName]);

        if ($updated) {
            // Pemicu Log Audit Trail untuk aksi Rename Folder
            $this->activityLogService->log(
                Auth::id(),
                'Integrasi Data',
                'Mengubah nama folder dari "' . $oldName . '" menjadi "' . $newName . '"'
            );
        }

        return $updated;
    }

    /**
     * Memindahkan lokasi folder.
     *
     * @param Folder $folder
     * @param int|null $targetFolderId
     * @return bool
     * @throws Exception
     */
    public function moveFolder(Folder $folder, ?int $targetFolderId): bool
    {
        // Validasi Defensif: Mencegah folder masuk ke dirinya sendiri
        if ($targetFolderId === $folder->id) {
            throw new Exception('Folder tidak dapat dipindahkan ke dalam dirinya sendiri.');
        }

        $oldParentId = $folder->parent_id;
        $updated = $folder->update(['parent_id' => $targetFolderId]);

        if ($updated) {
            $fromContext = $oldParentId ? 'Folder ID #' . $oldParentId : 'Root';
            $toContext = $targetFolderId ? 'Folder ID #' . $targetFolderId : 'Root';

            // Pemicu Log Audit Trail untuk aksi Move Folder
            $this->activityLogService->log(
                Auth::id(),
                'Integrasi Data',
                'Memindahkan folder "' . $folder->name . '" dari ' . $fromContext . ' ke ' . $toContext
            );
        }

        return $updated;
    }

    /**
     * Menghapus folder menggunakan Soft Delete.
     *
     * @param Folder $folder
     * @return bool|null
     */
    public function deleteFolder(Folder $folder): ?bool
    {
        $folderName = $folder->name;
        $deleted = $folder->delete();

        if ($deleted) {
            // Pemicu Log Audit Trail untuk aksi Delete Folder
            $this->activityLogService->log(
                Auth::id(),
                'Integrasi Data',
                'Melakukan soft delete pada folder "' . $folderName . '"'
            );
        }

        return $deleted;
    }
}
