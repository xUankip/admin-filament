<div
    class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5
    [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20
    [&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600
    dark:[&amp;:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 fi-fo-rich-editor max-w-full overflow-x-auto">

    <div class="min-w-0 flex-1">
        <div
            ax-load="visible"
            ax-load-src="{!! asset('js/filament/forms/components/rich-editor.js') !!}"
            x-data="richEditorFormComponent({richEditorFormComponent({
                            state: '',
                        })})"
            x-ignore
        >
            <input id="modal-content" type="hidden" value="{{$content??''}}"/>
            <trix-editor redonly input="modal-content" placeholder="" toolbar="modal-content"
                         x-ref="trix" wire:ignore=""
                         class="prose min-h-[theme(spacing.48)] max-w-none !border-none px-3 py-1.5 text-base text-gray-950
                         dark:prose-invert focus-visible:outline-none dark:text-white sm:text-sm sm:leading-6"
                         style="height: 420px; overflow: auto; resize:vertical;" contenteditable="" role="textbox"></trix-editor>
        </div>
    </div>
</div>
