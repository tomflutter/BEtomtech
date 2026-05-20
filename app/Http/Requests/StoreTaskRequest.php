<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreTaskRequest extends FormRequest
{
    /**
     * Semua user diizinkan membuat task.
     */
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000', 
        ];
    }

    
    public function messages(): array
    {
        return [
            'title.required' => 'Judul tugas wajib diisi.',
            'title.max'      => 'Judul tugas maksimal 255 karakter.',
            'description.max' => 'Deskripsi tugas maksimal 1000 karakter.',
        ];
    }


    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422)
        );
    }
}