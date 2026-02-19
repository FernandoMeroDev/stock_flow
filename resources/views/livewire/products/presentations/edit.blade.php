<td class="py-3 space-y-1">
    <div class="flex">
        <flux:input
            wire:model.live="name"
            class="col-span-2 mr-1"
            placeholder="1 Unidad"
            size="sm"
        />
        <flux:button icon="star" size="sm" class="shrink-0"></flux:button>
    </div>
    <div class="flex">
        <flux:label class="w-28">
            Precio ($):
        </flux:label>
        <flux:input wire:model.live="price" placeholder="1.50" size="sm" />
    </div>
    <div class="flex">
        <flux:label class="w-32">
            Unidades:
        </flux:label>
        <flux:input wire:model.live="units" placeholder="1" size="sm" class="mr-1" />
        <flux:button wire:click="destroy({{$presentation->id}})" icon="trash" size="sm" class="shrink-0"></flux:button>
    </div>
    <div>
        <flux:error name="name" />
        <flux:error name="price" />
        <flux:error name="units" />
    </div>
</td>
