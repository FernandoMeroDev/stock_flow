<div>
    <flux:modal name="edit-product" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Editar Producto</flux:heading>
                <flux:text class="mt-2">Haga cambios a la información del producto.</flux:text>
            </div>
            <flux:input wire:model="form.name" label="Nombre" placeholder="Nombre del producto" />
            <div class="flex justify-between">
                <flux:button 
                    type="submit" 
                    variant="primary" 
                    x-on:click="$wire.update()"
                >Guardar cambios</flux:button>
                <flux:button 
                    variant="danger" 
                    x-on:click="$wire.delete()"
                >Eliminar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
