<div>
    <flux:modal name="create-product" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Crear Producto</flux:heading>
                <flux:text class="mt-2">Ingrese a la información del producto.</flux:text>
            </div>

            <flux:input wire:model="form.name" label="Nombre" placeholder="Nombre del producto" />
            <flux:input wire:model="form.barcode" label="Código" placeholder="Código de barras" />
            <flux:input wire:model="form.price" label="Precio" placeholder="Precio" type="number" min="0.001" step="0.001" max="9999.999" />
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
                    <img src="{{ $form->img->temporaryUrl() }}" alt="Previsualización de la Imagen">
                </x-products.img>
            @endif

            <div class="flex">
                <flux:spacer />

                <flux:button type="submit" variant="primary" wire:click="store">Guardar cambios</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
