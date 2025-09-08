<div>
    <div x-data="{ render: true }" x-on:enabled-drag-and-drop.window="render = false">
        <div x-show="render">

            <flux:input 
                label="Agregar Productos" placeholder="Buscar por nombre..."
                wire:model.live.debounce.300ms="search"
            />

            <x-products.search.table :products="$searchedProducts" />

            <x-pagination :paginator="$searchedProducts" />
        </div>
    </div>
</div>
