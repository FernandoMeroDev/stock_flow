@use('App\Models\Warehouse')

<div>
    <flux:modal name="edit-product" class="max-w-full md:max-w-auto md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Editar Producto</flux:heading>
                <flux:text class="mt-2">Haga cambios a la información del producto.</flux:text>
            </div>
            <flux:input wire:model="form.name" label="Nombre" placeholder="Nombre del producto" />
            <flux:input wire:model="form.barcode" label="Código" placeholder="Código de barras" />
            <flux:input wire:model="form.price" label="Precio" placeholder="Precio" type="number" min="0.001" step="0.001" max="9999.999" />
            <div class="overflow-x-scroll flex justify-between items-end">
                <flux:input type="file" wire:model="form.img" label="Imagen" />
                <flux:button wire:click="clearImg" icon="trash" size="xs" />
            </div>
            @if (
                $form->img
                && (
                    $this->form->img?->getMimeType() == 'image/jpeg'
                    || $this->form->img?->getMimeType() == 'image/png'
                    || $this->form->img?->getMimeType() == 'image/webp'
                )
            )
                <x-products.img>
                    <img id="productEditModalImg" src="{{ $form->img->temporaryUrl() }}" alt="Previsualización de la Imagen">
                </x-products.img>
            @elseif($form->product?->img)
                <x-products.img>
                    <img id="productEditModalImg" src="{{route('products.img', $form->product->img)}}" alt="Imagen del Producto">
                </x-products.img>
            @endif
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
            @if($form->product)
                @foreach(Warehouse::all() as $warehouse)
                    <div>
                        <flux:heading size="lg">{{$warehouse->name}}</flux:heading>
                        <x-table class="w-full mb-3">
                            <x-slot:thead>
                                <x-table.th>Percha</x-table.th>
                                <x-table.th>Piso</x-table.th>
                                <x-table.th>Cantidad</x-table.th>
                            </x-slot:thead>

                            @php $warehouse_existences = $form->product->warehouse_existences($warehouse) @endphp
                            @forelse($warehouse_existences as $level_product)
                                <x-table.tr>
                                    <td class="text-lg p-3">
                                        {{$level_product->shelf_number}}
                                    </td>
                                    <td class="text-lg p-3">
                                        {{$level_product->level_number}}
                                    </td>
                                    <td class="text-lg p-3">
                                        {{$level_product->count}}
                                    </td>
                                </x-table.tr>
                            @empty
                                <x-table.tr>
                                    <td class="p-3">
                                        No hay resultados...
                                    </td>
                                    <td></td>
                                    <td></td>
                                </x-table.tr>
                            @endforelse
                            <x-table.tr>
                                <td class="text-lg p-3">
                                    Total:
                                </td>
                                <td></td>
                                <td class="text-lg p-3">
                                    {{$warehouse_existences->sum('count')}}
                                </td>
                            </x-table.tr>
                        </x-table>
                    </div>
                @endforeach
            @endif
        </div>
    </flux:modal>
    @script
    <script>
        document.addEventListener('edit-product', () => {
            const img = document.getElementById('productEditModalImg');
            if(img) img.style.display = 'none';
            $wire.form.img = null;
        });
    </script>
    @endscript
</div>
