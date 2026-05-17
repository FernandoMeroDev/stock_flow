<div>
    <flux:modal name="create-client">
        <div class="py-6">
            <form wire:submit="$parent.$refresh; store();" class="space-y-3">
                <flux:heading>Crear Cliente</flux:heading>

                <flux:input label="Nombre" wire:model="name" placeholder="Cliente..." />

                <flux:button type="submit" variant="primary">
                    Guardar
                </flux:button>
            </form>
        </div>
    </flux:modal>
</div>
