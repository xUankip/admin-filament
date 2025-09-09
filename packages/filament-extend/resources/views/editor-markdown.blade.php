<div
    class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5
        [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600
        dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 fi-fo-markdown-editor max-w-full overflow-hidden font-mono text-base
        text-gray-950 dark:text-white sm:text-sm">
    <div class="min-w-0 flex-1">
        <div ax-load="visible" ax-load-src="{{asset('js/filament/forms/components/markdown-editor.js')}}"
             x-data="markdownEditorFormComponent({ isLiveDebounced: false, isLiveOnBlur: false, liveDebounce: null, placeholder: null,
                  toolbarButtons: JSON.parse('[]'),
                   translations: JSON.parse('{}'),
                     })"
             x-ignore wire:ignore><textarea x-ref="editor" class="hidden">{!! $content??'' !!}</textarea></div>
    </div>
</div>
