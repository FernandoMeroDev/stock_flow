<div>
    <div class="flex mb-3">
        <flux:input type="text" placeholder="Buscar" wire:model.live="search" class="mr-2" />
        <flux:modal.trigger name="create-product">
            <flux:button>Nuevo</flux:button>
        </flux:modal.trigger>
    </div>

    <div wire:ignore>
        <livewire:products.create @created="$refresh" />

        <livewire:products.edit @edited="$refresh" />
    </div>

    <x-table class="w-full mb-3">
        <x-slot:thead>
            <x-table.th></x-table.th>
            <x-table.th>
                Productos
            </x-table.th>
            <x-table.th></x-table.th>
        </x-slot:thead>

        @forelse ($products as $product)
            <x-table.tr wire:key="{{$product->id}}">
                <td class="w-5 px-3 py-1">
                    <flux:button icon="pencil" x-on:click="$dispatch('edit-product', { product_id: {{$product->id}} })"></flux:button>
                </td>
                <td class="p-3">
                    {{$product->name}}
                </td>
                <td class="p-3">
                    <flux:button icon="table-cells" href="{{route('products.kardex', $product->id)}}" />
                </td>
            </x-table.tr>
        @empty
            <x-table.tr>
                <td></td>
                <td class="p-3">
                    No hay resultados...
                </td>
            </x-table.tr>
        @endforelse
    </x-table>

    <x-pagination :paginator="$products" />
</div>