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
                @if ($isImage)
                    <img src="{{ $url }}" alt="media" class="w-full max-h-[70vh] object-contain" />
                @elseif ($isVideo)
                    <video controls class="w-full max-h-[70vh] object-contain">
                        <source src="{{ $url }}" type="{{ $type }}" />
                    </video>
                @endif
            </div>
        </div>
    @endif

    {{ $this->form }}
@endsection


