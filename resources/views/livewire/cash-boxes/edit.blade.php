<div class="flex space-x-1">
    <flux:input wire:model.live="name" size="sm" />

    <flux:button wire:click="destroy({{$cash_box->id}})" icon="trash" size="sm" />
</div>
