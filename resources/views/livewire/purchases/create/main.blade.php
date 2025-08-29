<form wire:submit="store" class="space-y-2">
    <flux:heading>Compra</flux:heading>

    <x-table class="w-full mb-3">
        <x-slot:thead>
            <x-table.th>
                Productos
            </x-table.th>
            <x-table.th>
                Cantidad
            </x-table.th>
            <x-table.th></x-table.th>
        </x-slot:thead>

        @forelse ($form->products->keys() as $i)
            @php $product = $form->products->get($i); @endphp
            <x-table.tr wire:key="{{$product->id}}">
                <td class="p-3 max-w-60" x-on:click="$dispatch('edit-product', { product_id: {{$product->id}} })">
                    {{$product->name}}
                    <flux:input hidden wire:model="form.ids.{{$i}}" id="productIdInput{{$product->id}}" />
                </td>
                <td class="p-1">
                    <flux:input wire:model="form.amounts.{{$i}}" type="number" id="productAmountInput{{$product->id}}" />
                </td>
                <td>
                    <flux:button icon="trash" wire:click="removeProduct({{$product->id}})"  />
                </td>
            </x-table.tr>
        @empty
            <x-table.tr>
                <td class="p-3">
                    No hay productos...
                </td>
            </x-table.tr>
        @endforelse
    </x-table>

    <livewire:purchases.create.search-products />

    <div class="flex justify-between mt-4">
        <flux:button type="submit" variant="primary">Guardar</flux:button>

        <a href="{{route('purchases.index')}}">
            <flux:button>Todas las Compras</flux:button>
        </a>
    </div>

    @error('*')
        <p class="text-red-400 mt-4">
            {{$message}}
        </p>
    @enderror
</form>
