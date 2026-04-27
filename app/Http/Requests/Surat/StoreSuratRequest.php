<?php

namespace App\Http\Requests\Surat;

use Illuminate\Foundation\Http\FormRequest;

class StoreSuratRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jenis_surat' => 'required|string|in:resign,permohonan,surat_tugas,rekomendasi,izin,lainnya',
            'perihal' => 'required|string',
            'file_pdf' => 'nullable|file|mimes:pdf|max:5120',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'jenis_surat.required' => 'Jenis surat wajib diisi',
            'perihal.required' => 'Perihal wajib diisi',
            'file_pdf.mimes' => 'File harus berformat PDF',
            'file_pdf.max' => 'Ukuran file maksimal 5MB',
        ];
    }
}
