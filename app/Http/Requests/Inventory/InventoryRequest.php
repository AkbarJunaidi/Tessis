<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
{
    /**
     * Menentukan apakah pengguna diizinkan melakukan request ini.
     */
    public function authorize(): bool
    {
        // Izin diberikan karena rute ini sudah dilindungi oleh middleware 'auth' global
        return true;
    }

    /**
     * Aturan validasi yang diterapkan pada input form inventory.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Kondisi untuk membedakan validasi antara Create (POST) dan Update (PUT/PATCH)
        $inventoryId = $this->route('inventory') ? $this->route('inventory')->id : null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'serial_number' => [
                'required',
                'string',
                'max:255',
                // Mengizinkan nomor seri yang sama diabaikan jika sedang melakukan proses edit data sendiri
                'unique:inventories,serial_number,' . $inventoryId
            ],
            'image' => [
                // $this->isMethod('POST') ? 'required' : 'nullable',
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:5148' // Maksimal ukuran gambar 5MB
            ],
        ];
    }

    /**
     * Kustomisasi pesan kesalahan validasi dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama barang wajib diisi.',
            'name.max' => 'Nama barang maksimal 255 karakter.',
            'serial_number.required' => 'Serial number wajib diisi.',
            'serial_number.unique' => 'Serial number ini sudah terdaftar di sistem.',
            'image.required' => 'Gambar barang wajib diunggah.',
            'image.image' => 'Berkas harus berupa gambar.',
            'image.mimes' => 'Format gambar harus jpeg, png, atau jpg.',
            'image.max' => 'Ukuran gambar tidak boleh melebihi 2MB.',
        ];
    }
}
