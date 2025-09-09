<div>
    @isset($jsPath)
        <script>{!! file_get_contents($jsPath) !!}</script>
    @endisset
    @isset($cssPath)
        <style>{!! file_get_contents($cssPath) !!}</style>
    @endisset

    <div
        x-data="LivewireUIModal()"
        x-on:close.stop="setShowPropertyTo(false)"
        x-on:keydown.escape.window="show && closeModalOnEscape()"
        x-show="show"
        class="fixed inset-0 z-[50] overflow-y-auto"
        style="display: none;"
    >
        <div class="flex items-center justify-center px-4 pt-4 pb-10 text-center sm:block sm:p-0">
            <div
                x-show="show"
                x-on:click="closeModalOnClickAway()"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 transition-all transform"
            >
                <div class="fixed left-0 top-0 right-0 bottom-0 inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <div
                x-show="show && showActiveComponent"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"

                class="inline-block transform transition-all sm:my-8 sm:align-middle relative"
                id="modal-container"
                x-trap.noscroll.inert="show && showActiveComponent"
                aria-modal="true"
            >
                <button x-on:click="closeModalOnClickAway()" class="fixed right-0 top-0 w-[48px] h-[48px] bg-white rounded-3"><i class="fa fa-close"></i> </button>

                @forelse($components as $id => $component)
                    <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}" wire:key="{{ $id }}">
                        <div class="mt-8"> <!-- Thêm khoảng cách trên cho nội dung chính -->
                            @livewire($component['name'], $component['arguments'], key($id))
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>
