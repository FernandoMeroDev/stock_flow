<div>
    <form wire:submit="store" class="space-y-3">
        <flux:input label="Nombre" wire:model="name" placeholder="Proveedor..." />

        <flux:button type="submit">
            Guardar
        </flux:button>
    </form>
</div>
