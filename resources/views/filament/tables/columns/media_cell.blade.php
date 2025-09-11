<div class="flex items-center gap-3">
    @php
        $type = $getRecord()->type;
        $url = $getRecord()->url;
        $isImage = str_starts_with($type, 'image');
        $isVideo = str_starts_with($type, 'video');
    @endphp

    <div class="w-10 h-10 rounded-md overflow-hidden bg-gray-100 flex items-center justify-center">
        @if ($url && $isImage)
            <img src="{{ $url }}" alt="media" class="w-full h-full object-cover" />
        @elseif ($url && $isVideo)
            <video class="w-full h-full object-cover" muted>
                <source src="{{ $url }}" type="{{ $type }}" />
            </video>
        @else
            <div class="flex h-full w-full items-center justify-center">
                <x-heroicon-o-photo class="w-6 h-6 text-gray-400" />
            </div>
        @endif
    </div>

    
</div>


