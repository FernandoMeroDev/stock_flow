<div>
    <flux:modal name="edit-product" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Editar Producto</flux:heading>
                <flux:text class="mt-2">Haga cambios a la información del producto.</flux:text>
            </div>
            <flux:input wire:model="form.name" label="Nombre" placeholder="Nombre del producto" />
            <flux:input wire:model="form.barcode" label="Código" placeholder="Código de barras" />
            <div class="flex justify-between items-end">
                <flux:input type="file" wire:model="form.img" label="Imagen" />
                <flux:button wire:click="$set('form.img', null)" icon="trash" size="xs" />
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
                    <img id="productImg" src="{{ $form->img->temporaryUrl() }}" alt="Previsualización de la Imagen">
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
        </div>
    </flux:modal>
    @script
    <script>
        document.addEventListener('edit-product', () => {
            const img = document.getElementById('productEditModalImg');
            if(img) {
                img.style.display = 'none';
            }
            $wire.form.img = null;
        });
    </script>
    @endscript
</div>
