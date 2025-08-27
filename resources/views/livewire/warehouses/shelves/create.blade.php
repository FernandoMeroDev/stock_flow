<div>
    <form wire:submit="store" class="space-y-6">
        <div>
            <flux:heading size="lg">Crear Percha</flux:heading>
            <flux:text class="mt-2">
                Ingrese a la información de la percha. La última percha actualmente es {{$last_shelf_number}}.
            </flux:text>
        </div>
        
        <flux:input wire:model="form.number" label="Número" placeholder="Número de la percha" min="1" />
        <flux:input wire:model="form.levels_count" label="Pisos" placeholder="Cantidad de pisos" min="1" />

        <div class="flex justify-between">
            <flux:button type="submit" variant="primary">
                Guardar
            </flux:button>

            <a href="{{route('warehouses.edit', $form->warehouse->id)}}">
                <flux:button>Cancelar</flux:button>
            </a>
        </div>
    </form>
</div>
