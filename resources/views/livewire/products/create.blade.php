<div>
    <flux:modal name="create-product" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Crear Producto</flux:heading>
                <flux:text class="mt-2">Ingrese a la información del producto.</flux:text>
            </div>

            <flux:input wire:model="form.name" label="Nombre" placeholder="Nombre del producto" />

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" wire:click="store">Guardar cambios</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
