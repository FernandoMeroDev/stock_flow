<div>
    <flux:input 
        placeholder="Codigo de barras..." class="mb-1"
        x-data="searchByBarcodeInput" name="barcode" x-on:change="addProduct($event)"
    />

    <div class="flex">
        <flux:input 
            placeholder="Buscar por nombre..."
            wire:model.live.debounce.250ms="search" id="searchByNameInput" class="mr-1" 
        />
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

    @if($products->isNotEmpty())
        <x-products.search.table :$products />

        <x-pagination :paginator="$products" />
    @endif

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