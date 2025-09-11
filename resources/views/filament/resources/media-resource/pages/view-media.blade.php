@extends('filament::page')

@section('content')
    @php
        /** @var \App\Models\Media $record */
        $record = $this->record ?? null;
        $type = $record?->type;
        $url = $record?->url;
        $isImage = $type && str_starts_with($type, 'image');
        $isVideo = $type && str_starts_with($type, 'video');
    @endphp

    @if ($record)
        <div class="mb-6">
            <div class="rounded-xl overflow-hidden bg-gray-100">
                @if ($url && $isImage)
                    <img src="{{ $url }}" alt="media" class="w-full max-h-[80vh] object-contain" />
                @elseif ($url && $isVideo)
                    <video controls class="w-full max-h-[80vh] object-contain">
                        <source src="{{ $url }}" type="{{ $type }}" />
                    </video>
                @else
                    <div class="flex h-[60vh] w-full flex-col items-center justify-center gap-3 border-2 border-dashed border-gray-300 bg-white/60">
                        <x-heroicon-o-photo class="h-12 w-12 text-gray-400" />
                        <div class="text-sm text-gray-500">No preview available</div>
                        <div class="text-xs text-gray-400">Upload an image or provide a valid URL</div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{ $this->infolist }}
@endsection


