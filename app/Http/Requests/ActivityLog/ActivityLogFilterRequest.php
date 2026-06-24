<?php

namespace App\Http\Requests\ActivityLog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ActivityLogFilterRequest extends FormRequest
{
    /**
     * Menentukan paksaan hak akses pengguna terhadap filter ini.
     * Berdasarkan permission, Super Admin dan Admin diizinkan (Employee ditolak nanti di level middleware/policy).
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Aturan validasi ketat untuk parameter pencarian halaman Activity Logs.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'module' => ['nullable', 'string', 'in:All,Authentication,Inventory,Tracking Progress,Integrasi Data,User Management'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'action' => ['nullable', 'string', 'in:All,Create,Update,Delete,Move,Upload,Change Status,Login,Logout,Create User,Update User,Change Password,Change Role,Delete User'],
            'date_from' => ['nullable', 'date', 'date_format:Y-m-d'],
            'date_to' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:date_from'],
        ];
    }

    /**
     * Kustomisasi pesan kesalahan jika validasi input range tanggal atau tipe data salah.
     */
    public function messages(): array
    {
        return [
            'module.in' => 'Kluster modul yang dipilih tidak terdaftar di dalam sistem.',
            'action.in' => 'Jenis tindakan yang dipilih tidak dikenali oleh sistem audit.',
            'user_id.exists' => 'Data user pemicu yang dipilih tidak ditemukan.',
            'date_from.date_format' => 'Format tanggal awal pencarian harus berupa YYYY-MM-DD.',
            'date_to.date_format' => 'Format tanggal akhir pencarian harus berupa YYYY-MM-DD.',
            'date_to.after_or_equal' => 'Tanggal akhir pencarian tidak boleh mendahului tanggal awal.',
        ];
    }

    /**
     * Otomatis membersihkan input kosong (nullify) sebelum masuk ke pemrosesan query.
     */
    protected function passedValidation(): void
    {
        $this->merge(array_filter($this->validated(), function ($value) {
            return $value !== null && $value !== '';
        }));
    }
}
