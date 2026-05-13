<div>
    <flux:modal name="edit-provider">
        <div class="py-6">
            <form wire:submit="$parent.$refresh; update()" class="space-y-3">
                <flux:heading>Editar Proveedor</flux:heading>

                <flux:input label="Nombre" wire:model="name" placeholder="Proveedor..." />

                <flux:button type="submit" variant="primary">
                    Guardar
                </flux:button>
            </form>
        </div>
    </flux:modal>
</div>
