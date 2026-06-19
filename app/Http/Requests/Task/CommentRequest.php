<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Mengizinkan semua user yang lolos middleware auth
    }

    public function rules(): array
    {
        return [
            'task_id' => 'required|exists:tasks,id',
            'comment' => 'required|string|min:3|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'comment.required' => 'Kolom komentar progress tidak boleh dikosongkan.',
            'comment.min'      => 'Isi catatan progress minimal harus memuat 3 karakter.',
            'comment.max'      => 'Catatan progress terlalu panjang, maksimal 1000 karakter.',
        ];
    }
}
