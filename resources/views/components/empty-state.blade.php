{{-- 
  Empty State Component
  Gunakan: <x-empty-state icon="inbox" title="Belum ada data" description="Tidak ada data yang ditemukan untuk ditampilkan" />
--}}

<div class="flex flex-col items-center justify-center py-16 px-4 text-center">
    {{-- Icon Container --}}
    <div class="mb-6 p-6 rounded-full bg-slate-100 dark:bg-zink-700">
        @if($icon === 'inbox')
            <i data-lucide="inbox" class="w-12 h-12 text-slate-400 dark:text-zink-400"></i>
        @elseif($icon === 'users')
            <i data-lucide="users" class="w-12 h-12 text-slate-400 dark:text-zink-400"></i>
        @elseif($icon === 'calendar')
            <i data-lucide="calendar" class="w-12 h-12 text-slate-400 dark:text-zink-400"></i>
        @elseif($icon === 'file-text')
            <i data-lucide="file-text" class="w-12 h-12 text-slate-400 dark:text-zink-400"></i>
        @elseif($icon === 'briefcase')
            <i data-lucide="briefcase" class="w-12 h-12 text-slate-400 dark:text-zink-400"></i>
        @elseif($icon === 'clock')
            <i data-lucide="clock" class="w-12 h-12 text-slate-400 dark:text-zink-400"></i>
        @else
            <i data-lucide="{{ $icon }}" class="w-12 h-12 text-slate-400 dark:text-zink-400"></i>
        @endif
    </div>

    {{-- Title --}}
    <h3 class="text-lg font-semibold text-slate-700 dark:text-zink-100 mb-2">
        {{ $title }}
    </h3>

    {{-- Description --}}
    @if($description)
        <p class="text-sm text-slate-500 dark:text-zink-300 mb-6 max-w-sm">
            {{ $description }}
        </p>
    @endif

    {{-- Action Button (optional) --}}
    @if($actionText && $actionUrl)
        <a href="{{ $actionUrl }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-custom-500 hover:bg-custom-600 text-white font-semibold rounded-lg transition-all duration-200">
            @if($actionIcon)
                <i data-lucide="{{ $actionIcon }}" class="w-4 h-4"></i>
            @endif
            {{ $actionText }}
        </a>
    @endif
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
