<div>
    <flux:modal name="edit-movement" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Editar Compra</flux:heading>
                <flux:text class="mt-2">Haga cambios a la información de la compra.</flux:text>
            </div>
            <flux:input wire:model="form.product_name" label="Producto" readonly />
            <flux:input wire:model="form.count" label="Cantidad" placeholder="Cantidad de unidades..." />
            <flux:select disabled wire:model="form.movement_type">
                <flux:select.option value="i">Entrada</flux:select.option>
                <flux:select.option value="o">Salida</flux:select.option>
            </flux:select>
            <div class="flex justify-between">
                <flux:button 
                    type="submit" 
                    variant="primary" 
                    x-on:click="$wire.update({{$movement_id}})"
                >Guardar cambios</flux:button>
                <flux:button 
                    variant="danger" 
                    x-on:click="$wire.delete({{$movement_id}})"
                >Eliminar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
