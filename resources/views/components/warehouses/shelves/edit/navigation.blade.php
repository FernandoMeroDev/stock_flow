<div {{$attributes->merge(['class' => 'space-y-2'])}}>
    <div class="flex justify-center">
        <a href="{{route('warehouses.edit', $shelf->warehouse->id)}}" class="font-bold text-xl self-center">
            {{$shelf->warehouse->name}}
        </a>
    </div>
    <div class="flex justify-between">
        <a {{$getShelfRoute('previous')}}>
            <flux:button :disabled="is_null($previous_shelf_id)" variant="primary" icon="arrow-left"></flux:button>
        </a>
        <p class="font-bold self-center">Percha {{$shelf->number}}</p>
        <a {{$getShelfRoute('next')}}>
            <flux:button :disabled="is_null($next_shelf_id)" variant="primary" icon="arrow-right"></flux:button>
        </a>
    </div>
    <div class="flex justify-between">
        <a {{$getLevelRoute('previous')}}>
            <flux:button :disabled="is_null($previous_level_id)" icon="arrow-left"></flux:button>
        </a>
        <p class="font-bold self-center">Piso {{$level->number}}</p>
        <a {{$getLevelRoute('next')}}>
            <flux:button :disabled="is_null($next_level_id)" icon="arrow-right"></flux:button>
        </a>
    </div>
</div>