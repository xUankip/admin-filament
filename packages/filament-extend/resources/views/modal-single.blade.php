@script
<script>
    window.addEventListener('open-modal-single', function (e) {
        document.getElementById('modalContent').innerHTML = '';
        $dispatch('open-modal', {id: 'singleModal', content: e.detail.view})
        setTimeout(function () {
            document.getElementById('modalContent').innerHTML = e.detail.view;
        }, 80)
    });

</script>
@endscript
<script>
    function modalClearContent() {
        setTimeout(function () {
            document.getElementById('modalContent').innerHTML = '';
        }, 369)
    }
</script>

<x-filament::modal :close-by-clicking-away="false" id="singleModal" sticky-header
                   @close-modal.window="modalClearContent()"
                   :width="$width??'sm'"
>
    @if(!empty($heading))
        <x-slot name="heading">
            <div id="modalHeading">
                {!! $heading !!}
            </div>
        </x-slot>
    @endif
    <div id="modalContent" class="-mx-6 -mb-6 -mt-6"></div>
</x-filament::modal>
