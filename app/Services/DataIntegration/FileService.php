<?php

namespace App\Services\DataIntegration;

use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Exception;

class FileService
{
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
            return File::create([
                'folder_id' => $folderId, // Null artinya masuk ke My Files pribadi
                'user_id' => Auth::id(),
                'file_name' => $originalName,
                'file_path' => $path,
                'file_size' => $fileSize,
                'file_type' => $fileType,
            ]);

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

        return response()->download($absolutePath, $file->file_name);
    }

    /**
     * Mengubah nama berkas dokumen (Rename File).
     * Fixes: Call to unknown method renameFile()
     *
     * @param File $file
     * @param string $newFileName
     * @return bool
     * @throws Exception
     */
    public function renameFile(File $file, string $newFileName): bool
    {
        try {
            return $file->update([
                'file_name' => $newFileName
            ]);
        } catch (Exception $e) {
            throw new Exception('Gagal mengubah nama file: ' . $e->getMessage());
        }
    }

    /**
     * Memindahkan lokasi berkas antar folder / ke ruang bersama (Move File).
     * Fixes: Call to unknown method moveFile()
     *
     * @param File $file
     * @param int|null $targetFolderId
     * @return bool
     * @throws Exception
     */
    public function moveFile(File $file, ?int $targetFolderId): bool
    {
        try {
            return $file->update([
                'folder_id' => $targetFolderId
            ]);
        } catch (Exception $e) {
            throw new Exception('Gagal memindahkan file: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus berkas secara aman menggunakan Soft Delete.
     * Fixes: Call to unknown method deleteFile()
     *
     * @param File $file
     * @return bool|null
     * @throws Exception
     */
    public function deleteFile(File $file): ?bool
    {
        try {
            // Menggunakan softDeletes() sesuai source of truth database design
            return $file->delete();
        } catch (Exception $e) {
            throw new Exception('Gagal menghapus file: ' . $e->getMessage());
        }
    }
}
