<div class="mt-3 space-y-2">
    <flux:input 
        label="Buscar Productos"
        x-data="searchByBarcodeInput" name="barcode" x-on:change="addProduct($event)" placeholder="Codigo de barras..." 
    />

    <div class="flex">
        <flux:input wire:model.live.debounce.250ms="search" id="searchByNameInput" placeholder="Buscar por nombre..." class="mr-2" />
        <flux:modal.trigger name="create-product">
            <flux:button>Nuevo</flux:button>
        </flux:modal.trigger>
    </div>

    <livewire:products.create @created="$refresh" />

    @script
    <script>
        Alpine.data('searchByBarcodeInput', () => ({
            addProduct(event) {
                event.target.focus();
                const barcode = event.target.value;
                event.target.value = null;
                $wire.$parent.addProduct(barcode, 'barcode');
            }
        }));
    </script>
    @endscript

    <x-table class="w-full mb-3">
        @forelse ($products as $product)
            <x-table.tr>
                <td class="p-3 max-w-60" x-on:click="$dispatch('edit-product', { product_id: {{$product->id}} })">
                    {{$product->name}}
                </td>
                <td class="flex justify-end">
                    <flux:button icon="plus" x-on:click="$dispatch('add-product', {id: {{$product->id}}})" />
                </td>
            </x-table.tr>
        @empty
            <x-table.tr>
                <td class="p-3">
                    No hay resultados...
                </td>
            </x-table.tr>
        @endforelse
    </x-table>

    <x-pagination :paginator="$products" />

    <livewire:products.edit @edited="$js.refreshWithParent" />

    @script
    <script>
        $js('refreshWithParent', () => {
            $wire.$refresh();
            $wire.$parent.$refresh();
        });
    </script>
    @endscript
</div>
