<div x-data="{ deleting: false }" class="grid grid-cols-2 gap-4">
    <flux:button x-on:click="deleting = false" type="submit" variant="primary">
        Guardar cambios
    </flux:button>

    <flux:button 
        x-on:click="deleting = !deleting"
        variant="danger" 
    >Eliminar</flux:button>
    <div x-show="deleting"></div>
    <div x-show="deleting" class="grid gap-1 grid-cols-2">
        <p class="col-span-2 font-bold text-center">
            ¿Seguro?
        </p>
        <flux:button x-on:click="deleting = false">No</flux:button>
        <flux:button variant="danger" wire:click="delete" x-on:click="deleting = false">Si</flux:button>
    </div>
</div>