<div>
    <div class="space-y-6">
        <div>
            <flux:heading size="lg">Crear Percha</flux:heading>
            <flux:text class="mt-2">Ingrese a la información de la percha.</flux:text>
        </div>
        
        <flux:input wire:model="form.number" label="Número" placeholder="Número de la percha" min="1" />
        <flux:input wire:model="form.levels_count" label="Pisos" placeholder="Cantidad de pisos" min="1" />

        <div class="flex">
            <flux:spacer />

            <flux:button type="submit" variant="primary" wire:click="store">Guardar</flux:button>

            <p wire:loading class="ml-2 self-end">Guardando...</p>
        </div>
    </div>
</div>
