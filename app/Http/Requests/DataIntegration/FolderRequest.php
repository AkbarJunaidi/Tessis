<?php

namespace App\Http\Requests\DataIntegration;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FolderRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan untuk membuat permintaan ini.
     */
    public function authorize(): bool
    {
        return Auth::check(); // Mengamankan form wajib kondisi login
    }

    /**
     * Aturan validasi yang berlaku untuk request data folder.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                // Mencegah karakter aneh pada penamaan folder demi keamanan OS Storage
                'regex:/^[^\\/\\?%\\*:|"<>]+$/'
            ],
            'parent_id' => 'nullable|exists:folders,id',
        ];
    }
    
    /**
     * Kustomisasi pesan error bahasa Indonesia yang bersahabat untuk pengguna.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama folder wajib diisi.',
            'name.string' => 'Nama folder harus berupa teks valid.',
            'name.max' => 'Nama folder maksimal berukuran 255 karakter.',
            'name.regex' => 'Nama folder tidak boleh mengandung karakter terlarang (\ / ? % * : | " < >).',
            'parent_id.exists' => 'Folder tujuan tidak terdaftar di sistem.',
        ];
    }
}
