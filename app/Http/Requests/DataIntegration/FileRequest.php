<?php

namespace App\Http\Requests\DataIntegration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        // Kondisi jika rute adalah mengunggah file baru
        if ($this->isMethod('post')) {
            return [
                'file' => [
                    'required',
                    'file',
                    // Membatasi ekstensi sesuai Aturan File pada tugas (pdf, doc, docx, xls, xlsx, jpg, jpeg, png)
                    'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
                    // Membatasi ukuran file maksimal 10MB (10240 KB) sebagai Best Practice performa PHP laragon
                    'max:10240',
                ],
                'folder_id' => 'nullable|exists:folders,id',
            ];
        }

        // Kondisi jika rute adalah proses Rename File (PATCH/PUT)
        return [
            'file_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[^\\/\\?%\\*:|"<>]+$/'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Berkas file wajib dipilih.',
            'file.file' => 'Format unggahan harus berupa berkas file.',
            'file.mimes' => 'Format file ditolak. Hanya mengizinkan format: pdf, doc, docx, xls, xlsx, jpg, jpeg, png.',
            'file.max' => 'Ukuran file terlalu besar. Maksimal ukuran yang diizinkan adalah 10 MB.',
            'folder_id.exists' => 'Folder tujuan tidak ditemukan.',
            'file_name.required' => 'Nama file baru wajib diisi.',
            'file_name.regex' => 'Nama file tidak boleh mengandung karakter terlarang.',
        ];
    }
}
