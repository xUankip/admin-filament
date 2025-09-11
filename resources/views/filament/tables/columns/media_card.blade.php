@php
    $record = $getRecord();
    $type = $record->type;
    $url = $record->url;
    $isImage = str_starts_with($type, 'image');
    $isVideo = str_starts_with($type, 'video');
    $name = basename(parse_url($url, PHP_URL_PATH) ?? $url);
@endphp

<div class="group relative overflow-hidden rounded-xl border bg-white shadow-sm transition hover:shadow-md">
    <div class="aspect-[4/3] bg-gray-100">
        @if ($isImage)
            <img src="{{ $url }}" alt="media" class="h-full w-full object-cover"/>
        @elseif ($isVideo)
            <video class="h-full w-full object-cover" muted>
                <source src="{{ $url }}" type="{{ $type }}"/>
            </video>
        @else
            <div class="flex h-full w-full items-center justify-center text-gray-400">
                <x-heroicon-o-photo class="h-10 w-10"/>
            </div>
        @endif
    </div>

    <div class="p-3 space-y-2">
        <div class="flex items-center gap-2">
            <span class="truncate text-sm font-medium" title="{{ $name }}">{{ $name }}</span>
            <span class="ml-auto rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-700">{{ $type }}</span>
        </div>

        <div class="flex items-center gap-3 text-xs">
            @if ($record->event)
                <span class="truncate text-gray-600">Event: {{ $record->event->title }}</span>
            @endif
            @if ($record->uploader)
                <span class="truncate text-gray-600">By: {{ $record->uploader->name }}</span>
            @endif
        </div>

        @if (!empty($record->tags))
            <div class="flex flex-wrap gap-1">
                @foreach ((array) $record->tags as $tag)
                    <span class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-700">{{ $tag }}</span>
                @endforeach
            </div>
        @endif
    </div>
</div>


