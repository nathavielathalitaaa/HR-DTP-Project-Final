<?php

namespace App\Http\Controllers;

use App\Models\DocumentSetting;
use Illuminate\Http\Request;

class DocumentSettingController extends Controller
{
    // nampilin halaman pengaturan dokumen dgn load semua setting dr database / pake default value klo blm ada
    public function index()
    {
        $settings = [
            'company_name' => DocumentSetting::get('company_name', 'HR Sinergi Hotel & Villa'),
            'accent_color' => DocumentSetting::get('accent_color', '#04A54C'),
            'font_family' => DocumentSetting::get('font_family', 'Arial'),
            'footer_text' => DocumentSetting::get('footer_text', 'Dokumen ini sah dan ditandatangani secara digital.'),
            'logo_path' => DocumentSetting::get('logo_path', ''),
        ];

        return view('hr.settings.document', compact('settings'));
    }

    // update semua pengaturan dokumen (nama perusahaan, warna, font, footer) setelah validasi input, 
    // lalu simpan ke database via model DocumentSetting
    public function update(Request $request)
    {
        // validasi input: company_name wajib max 255 char, accent_color wajib 7 char, font_family wajib salah satu dari 4 opsi, footer_text optional max 500 char
        $request->validate([
            'company_name' => 'required|string|max:255',
            'accent_color' => 'required|string|size:7',
            'font_family' => 'required|string|in:Arial,Times New Roman,Helvetica,Georgia',
            'footer_text' => 'nullable|string|max:500',
        ]);

        // simpan semua setting yg udah divalidasi ke database pake method set dr model DocumentSetting
        DocumentSetting::set('company_name', $request->company_name);
        DocumentSetting::set('accent_color', $request->accent_color);
        DocumentSetting::set('font_family', $request->font_family);
        DocumentSetting::set('footer_text', $request->footer_text);

        flash()->success('Pengaturan dokumen berhasil disimpan.');
        return redirect()->route('hr.settings.document');
    }

    // handle upload logo dokumen dgn validasi image, simpan ke public storage, lalu update path-nya di database
    public function uploadLogo(Request $request)
    {
        // validasi: logo wajib, harus image, format png/jpg/jpeg, max 2mb
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // proses upload klo file beneran ada: simpan ke folder 'document-logos' di default disk (s3/local), update path di database, tampilin pesan sukses
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('document-logos');
            DocumentSetting::set('logo_path', $path);
            flash()->success('Logo dokumen berhasil diunggah.');
        }

        return redirect()->route('hr.settings.document');
    }
}