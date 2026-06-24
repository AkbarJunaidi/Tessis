<?php

namespace App\Services\DataIntegration;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Services\ActivityLog\ActivityLogService;
use Exception;

class FileService
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
     * Menangani proses upload file dan simpan records ke database.
     *
     * @param UploadedFile $uploadedFile
     * @param int|null $folderId
     * @return File
     * @throws Exception
     */
    public function uploadFile(UploadedFile $uploadedFile, ?int $folderId = null): File
    {
        try {
            $originalName = $uploadedFile->getClientOriginalName();

            // Generate nama unik untuk file fisik agar tidak bertabrakan di storage
            $fileName = time() . '_' . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();

            // Tentukan path penyimpanan (lokal) sesuai standar Laravel 12
            $path = $uploadedFile->storeAs('uploads/data_integration', $fileName, 'public');

            // Ambil ukuran file dalam bytes
            $fileSize = $uploadedFile->getSize();

            // Ambil tipe ektensi file
            $fileType = $uploadedFile->getClientOriginalExtension();

            // Simpan metadata ke database Eloquent ORM
            $file = File::create([
                'folder_id' => $folderId, // Null artinya masuk ke My Files pribadi
                'user_id' => Auth::id(),
                'file_name' => $originalName,
                'file_path' => $path,
                'file_size' => $fileSize,
                'file_type' => $fileType,
            ]);

            // Pemicu Log Audit Trail dinamis untuk aksi Upload
            $context = $folderId ? 'Folder Management' : 'My Files';
            $this->activityLogService->log(
                Auth::id(),
                'Integrasi Data',
                'Mengunggah berkas baru bernama "' . $file->file_name . '" ke ' . $context
            );

            return $file;

        } catch (Exception $e) {
            throw new Exception('Gagal mengunggah file: ' . $e->getMessage());
        }
    }

    /**
     * Menangani proses pengunduhan berkas secara aman.
     *
     * @param File $file
     * @return BinaryFileResponse
     * @throws Exception
     */
    public function downloadFile(File $file): BinaryFileResponse
    {
        if (!Storage::disk('public')->exists($file->file_path)) {
            throw new Exception('Berkas fisik tidak ditemukan di server penyimpanan.');
        }

        $absolutePath = storage_path('app/public/' . $file->file_path);

        // Pemicu Log Audit Trail dinamis untuk aksi Download
        $this->activityLogService->log(
            Auth::id(),
            'Integrasi Data',
            'Mengunduh berkas "' . $file->file_name . '"'
        );

        return response()->download($absolutePath, $file->file_name);
    }

    /**
     * Mengubah nama berkas dokumen (Rename File).
     *
     * @param File $file
     * @param string $newFileName
     * @return bool
     * @throws Exception
     */
    public function renameFile(File $file, string $newFileName): bool
    {
        try {
            $oldFileName = $file->file_name;

            $updated = $file->update([
                'file_name' => $newFileName
            ]);

            if ($updated) {
                // Pemicu Log Audit Trail dinamis untuk aksi Rename
                $this->activityLogService->log(
                    Auth::id(),
                    'Integrasi Data',
                    'Mengubah nama berkas dari "' . $oldFileName . '" menjadi "' . $newFileName . '"'
                );
            }

            return $updated;
        } catch (Exception $e) {
            throw new Exception('Gagal mengubah nama file: ' . $e->getMessage());
        }
    }

    /**
     * Memindahkan lokasi berkas antar folder / ke ruang bersama (Move File).
     *
     * @param File $file
     * @param int|null $targetFolderId
     * @return bool
     * @throws Exception
     */
    public function moveFile(File $file, ?int $targetFolderId): bool
    {
        try {
            $oldFolderId = $file->folder_id;

            $updated = $file->update([
                'folder_id' => $targetFolderId
            ]);

            if ($updated) {
                $fromContext = $oldFolderId ? 'Folder ID #' . $oldFolderId : 'My Files';
                $toContext = $targetFolderId ? 'Folder ID #' . $targetFolderId : 'My Files';

                // Pemicu Log Audit Trail dinamis untuk aksi Move
                $this->activityLogService->log(
                    Auth::id(),
                    'Integrasi Data',
                    'Memindahkan berkas "' . $file->file_name . '" dari ' . $fromContext . ' ke ' . $toContext
                );
            }

            return $updated;
        } catch (Exception $e) {
            throw new Exception('Gagal memindahkan file: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus berkas secara aman menggunakan Soft Delete.
     *
     * @param File $file
     * @return bool|null
     * @throws Exception
     */
    public function deleteFile(File $file): ?bool
    {
        try {
            $fileName = $file->file_name;

            // Menggunakan softDeletes() sesuai source of truth database design
            $deleted = $file->delete();

            if ($deleted) {
                // Pemicu Log Audit Trail dinamis untuk aksi Delete
                $this->activityLogService->log(
                    Auth::id(),
                    'Integrasi Data',
                    'Melakukan soft delete pada berkas "' . $fileName . '"'
                );
            }

            return $deleted;
        } catch (Exception $e) {
            throw new Exception('Gagal menghapus file: ' . $e->getMessage());
        }
    }
}
