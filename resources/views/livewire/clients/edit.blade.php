<div>
    <flux:modal name="edit-client">
        <div class="py-6">
            <form wire:submit="$parent.$refresh; update()" class="space-y-3">
                <flux:heading>Editar Cliente</flux:heading>

                <flux:input label="Nombre" wire:model="name" placeholder="Cliente..." />

                <flux:button type="submit" variant="primary">
                    Guardar
                </flux:button>
            </form>
        </div>
    </flux:modal>
</div>
