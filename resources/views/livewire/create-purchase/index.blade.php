<div class="space-y-2">
    <flux:heading>Compra</flux:heading>

    <flux:input type="date" label="Fecha de emisión" required value="{{date('Y-m-d')}}" name="issuance_date" />

    <flux:input label="Número" x-mask="999-999-999999999" name="number" />

    <x-table class="w-full mb-3">
        <x-slot:thead>
            <x-table.th>
                Productos
            </x-table.th>
            <x-table.th>
                Precio
            </x-table.th>
            <x-table.th>
                Cantidad
            </x-table.th>
            <x-table.th></x-table.th>
        </x-slot:thead>

        @if($products)
            @foreach ($products->keys() as $i)
                @php $product = $products->get($i); @endphp
                <x-table.tr wire:key="{{$product->id}}">
                    <td class="p-3 max-w-60">
                        {{$product->name}}
                        <flux:input hidden value="{{$product->id}}" id="productIdInput{{$product->id}}" />
                    </td>
                    <td class="p-1">
                        <flux:input value="{{$prices[$i] ?? 0}}" type="number" id="productPriceInput{{$product->id}}" />
                    </td>
                    <td class="p-1">
                        <flux:input value="{{$amounts[$i] ?? 0}}" type="number" id="productAmountInput{{$product->id}}" />
                    </td>
                    <td>
                        <flux:button icon="x-circle" wire:click="removeProduct({{$product->id}})"  />
                    </td>
                </x-table.tr>
            @endforeach
        @else
            <x-table.tr>
                <td class="p-3">
                    No hay productos...
                </td>
            </x-table.tr>
        @endif
    </x-table>

    <livewire:create-purchase.search-product />
</div>