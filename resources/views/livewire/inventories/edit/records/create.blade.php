@use('App\Models\Warehouse')
<div>
    <flux:modal name="create-inventory-record" class="md:w-96">
        <form wire:submit="store" class="space-y-6">
            <div>
                <flux:heading size="lg">Crear Registro</flux:heading>
                <flux:text class="mt-2">Ingrese a la información del Registro de Inventario.</flux:text>
            </div>

            <x-inventories.edit.records.search-products :$products />

            <div class="space-y-2">
                <flux:input wire:model="form.product_id" label="IDENTIFICADOR" required type="number" min="1" step="1" />

                <flux:input wire:model="form.name" label="Nombre" required maxlength="500" />

                <flux:input wire:model="form.price" label="Precio" required type="number" min="0" step="0.001" max="9999.999" />

                <flux:input wire:model="form.incoming_count" label="Entradas" type="number" required  min="0" step="1" max="9999" />

                <flux:input wire:model="form.outgoing_count" label="Salidas" type="number" required min="0" step="1" max="9999" />

                @foreach(Warehouse::all() as $warehouse)
                    <flux:input 
                        wire:model="form.warehouse_existences.{{$warehouse->id}}" 
                        label="{{$warehouse->name}}" required type="number" min="0" step="1" max="9999.999"
                    />
                @endforeach
            </div>

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary">Guardar cambios</flux:button>
            </div>
        </form>
    </flux:modal>
</div>