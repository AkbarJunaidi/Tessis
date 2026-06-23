<?php

namespace App\Http\Controllers\DataIntegration;

use App\Http\Controllers\Controller;
use App\Http\Requests\DataIntegration\FileRequest;
use App\Services\DataIntegration\FileService;
use App\Models\File;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Exception;

class FileController extends Controller
{
    protected FileService $fileService;

    /**
     * Dependency Injection melalui Constructor
     */
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    /**
     * Menampilkan halaman My Files (Ruang penyimpanan pribadi user aktif).
     */
    public function myFiles(): View
    {
        // Hanya mengambil file milik user login (Isolasi data privasi)
        $files = File::where('user_id', Auth::id())->get();

        return view('data-integration.my-files', compact('files'));
    }

    /**
     * Memproses unggah file dari Folder Management ataupun My Files.
     */
    public function store(FileRequest $request): RedirectResponse
    {
        try {
            if (!$request->hasFile('file')) {
                return redirect()->back()->with('error', 'Berkas unggahan tidak ditemukan.');
            }

            // folder_id akan bernilai null jika diunggah langsung dari menu My Files
            $folderId = $request->input('folder_id') ?: null;

            $this->fileService->uploadFile($request->file('file'), $folderId);

            return redirect()->back()->with('success', 'Berkas berhasil diunggah dan diverifikasi.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Menangani pengunduhan berkas menggunakan BinaryFileResponse (Symfony).
     */
    public function download(File $file): BinaryFileResponse|RedirectResponse
    {
        try {
            return $this->fileService->downloadFile($file);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mengubah nama berkas dokumen (Rename File).
     */
    public function rename(File $file, Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'file_name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9_\-\s\.]+$/']
            ], [
                'file_name.required' => 'Nama file baru wajib diisi.',
                'file_name.regex' => 'Nama file tidak boleh mengandung karakter khusus ilegal.'
            ]);

            $this->fileService->renameFile($file, $request->input('file_name'));

            return redirect()->back()->with('success', 'Nama berkas berhasil diperbarui.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Memindahkan posisi berkas (Move File).
     */
    public function move(File $file, Request $request): RedirectResponse
    {
        try {
            // target_folder_id bernilai null berarti dipindahkan ke root/My Files
            $targetFolderId = $request->input('target_folder_id') ?: null;

            $this->fileService->moveFile($file, $targetFolderId);

            return redirect()->back()->with('success', 'Berkas berhasil dipindahkan tempat.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Menghapus berkas dari sistem (Soft Delete).
     */
    public function destroy(File $file): RedirectResponse
    {
        try {
            $this->fileService->deleteFile($file);

            return redirect()->back()->with('success', 'Berkas berhasil dihapus dari penyimpanan.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
