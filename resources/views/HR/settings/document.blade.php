@extends('layouts.master')

@section('content')
<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
<div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto max-w-4xl">

{{-- breadcrumb --}}
<div class="flex items-center justify-between py-4">
    <div>
        <h5 class="text-base font-bold text-slate-900">Document Signature Settings</h5>
        <p class="text-xs text-slate-500 mt-0.5">Configure logo, company name, color, and font for system-generated documents.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- kiri: pengaturan teks & warna --}}
    <div class="lg:col-span-2">
        <form action="{{ route('hr.settings.document.update') }}" method="POST">
            @csrf
            <div class="ds-section">
                <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                    <i data-lucide="file-text" class="w-4 h-4 text-custom-500"></i>
                    <h6 class="text-sm font-bold text-slate-900">Branding & Text</h6>
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm" required>
                    @error('company_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Accent Color (Hex) <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <input type="color" name="accent_color" value="{{ old('accent_color', $settings['accent_color']) }}" class="h-9 w-9 rounded border border-slate-200 cursor-pointer" required>
                            <input type="text" value="{{ old('accent_color', $settings['accent_color']) }}" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm uppercase" disabled>
                        </div>
                        @error('accent_color')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-600 mb-1">Font Family <span class="text-red-500">*</span></label>
                        <select name="font_family" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm" required>
                            <option value="Arial" {{ $settings['font_family'] === 'Arial' ? 'selected' : '' }}>Arial</option>
                            <option value="Times New Roman" {{ $settings['font_family'] === 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                            <option value="Helvetica" {{ $settings['font_family'] === 'Helvetica' ? 'selected' : '' }}>Helvetica</option>
                            <option value="Georgia" {{ $settings['font_family'] === 'Georgia' ? 'selected' : '' }}>Georgia</option>
                        </select>
                        @error('font_family')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-bold text-slate-600 mb-1">Footer Text (Optional)</label>
                    <textarea name="footer_text" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm">{{ old('footer_text', $settings['footer_text']) }}</textarea>
                    @error('footer_text')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="ds-btn btn-green w-full justify-center">
                    <i data-lucide="save" class="w-4 h-4"></i> Save Settings
                </button>
            </div>
        </form>
    </div>

    {{-- kanan: upload logo --}}
    <div class="lg:col-span-1">
        <div class="ds-section">
            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                <i data-lucide="image" class="w-4 h-4 text-custom-500"></i>
                <h6 class="text-sm font-bold text-slate-900">Document Logo</h6>
            </div>

            <div class="mb-4 text-center">
                @if($settings['logo_path'])
                    <div class="mb-3 p-3 border border-dashed border-slate-300 rounded-lg bg-slate-50 flex items-center justify-center min-h-[120px]">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url($settings['logo_path']) }}" alt="Logo Dokumen" class="max-w-full max-h-[100px] object-contain">
                    </div>
                @else
                    <div class="mb-3 p-3 border border-dashed border-slate-300 rounded-lg bg-slate-50 flex items-center justify-center min-h-[120px]">
                        <p class="text-xs text-slate-400">No logo yet</p>
                    </div>
                @endif
            </div>

            <form action="{{ route('hr.settings.document.logo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <input type="file" name="logo" accept="image/png, image/jpeg, image/jpg" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-custom-50 file:text-custom-700 hover:file:bg-custom-100" required>
                    <p class="text-[10px] text-slate-400 mt-1">Format: PNG, JPG (Max 2MB)</p>
                    @error('logo')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit" class="ds-btn btn-outline w-full justify-center">
                    <i data-lucide="upload" class="w-4 h-4"></i> Upload Logo
                </button>
            </form>
        </div>
    </div>

</div>

</div>
</div>
@endsection
