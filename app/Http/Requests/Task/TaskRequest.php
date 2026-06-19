<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id'  => ['required', 'exists:projects,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status'      => ['required', Rule::in(['Todo', 'In Progress', 'Review', 'Done'])],
            'priority'    => ['required', Rule::in(['Low', 'Medium', 'High'])],
            'deadline'    => ['required', 'date'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'    => 'Judul tugas (task title) wajib diisi.',
            'status.in'         => 'Status yang dipilih harus: Todo, In Progress, Review, atau Done.',
            'priority.in'       => 'Tingkat prioritas harus: Low, Medium, atau High.',
            'deadline.required' => 'Tanggal deadline tugas wajib diisi.',
            'assigned_to.exists'=> 'User pelaksana yang dipilih tidak terdaftar di sistem.',
        ];
    }
}
