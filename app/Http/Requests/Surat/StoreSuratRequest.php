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
            'surat_type_id' => 'required|exists:surat_types,id',
            'jenis_surat' => 'nullable|string',
            'perihal' => 'required|string',
            'file_pdf' => 'required|file|mimes:pdf|max:5120',
            'ttd_coordinates' => 'nullable|string',
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
            'file_pdf.required' => 'File surat wajib diunggah.',
            'file_pdf.mimes' => 'File harus berformat PDF',
            'file_pdf.max' => 'Ukuran file maksimal 5MB',
        ];
    }
}
