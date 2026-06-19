<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Izin dilewati karena sudah dilindungi auth middleware global
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'deadline'    => ['required', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama project wajib diisi.',
            'name.max'          => 'Nama project maksimal 255 karakter.',
            'deadline.required' => 'Tanggal batas waktu (deadline) wajib ditentukan.',
            'deadline.date'     => 'Format tanggal tidak valid.',
            'deadline.after_or_equal' => 'Tanggal deadline tidak boleh hari kemarin.',
        ];
    }
}
