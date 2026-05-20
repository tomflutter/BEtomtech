<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * Form Request untuk validasi pembuatan Task baru.
 * Memastikan data yang masuk valid sebelum diproses controller.
 */
class StoreTaskRequest extends FormRequest
{
    /**
     * Semua user diizinkan membuat task.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk request ini.
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',   // Judul wajib, maks 255 karakter
            'description' => 'nullable|string|max:1000',  // Deskripsi opsional, maks 1000 karakter
        ];
    }

    /**
     * Pesan error kustom dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Judul tugas wajib diisi.',
            'title.max'      => 'Judul tugas maksimal 255 karakter.',
            'description.max' => 'Deskripsi tugas maksimal 1000 karakter.',
        ];
    }

    /**
     * Override: Kembalikan JSON saat validasi gagal (bukan redirect).
     */
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