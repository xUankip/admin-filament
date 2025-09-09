<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }" class="flex relative">
        <input class="flex-1"
               {!! $isRequired() ? 'required' : null !!}
               {!! $isDisabled() ? 'disabled' : null !!}
               min="{{ $getMin()}}"
               max="{{ $getMax()}}"
               value="{{$getDefaultState()}}"
               step="{{ $getStep()}}"
        {{ $applyStateBindingModifiers('wire:model') }}="{{ $getStatePath() }}"
        oninput="this.nextElementSibling.value = this.value"
        type="range" x-model="state"/>
        <output class="ml-2 range-current"
                style="top: 15px;right: 0;font-size: 14px">{{$getDefaultState()}}</output>
        @if($getShowMin())
            <output class="absolute range-min" style="top: 15px;left: 0;font-size: 14px">{{$getMin()}}</output>
        @endif
    </div>

</x-dynamic-component>
