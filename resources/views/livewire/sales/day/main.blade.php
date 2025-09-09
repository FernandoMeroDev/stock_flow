@use('App\Models\Warehouse')
<div>
    <div x-data="{ change: false }">
        <p class="font-bold text-center text-xl mb-1">
            {{$warehouse_name}}
            <flux:button x-on:click="change = !change" icon="arrow-path-rounded-square" size="xs"></flux:button>
        </p>
        <nav x-show="change" class="flex flex-col items-center mb-3">
            @foreach(Warehouse::where('id', '!=', $warehouse_id)->get() as $warehouse)
                <a
                    href="{{route('sales.day', ['warehouse' => $warehouse->id, 'date' => date('Y-m-d', strtotime($date))])}}" 
                    class="w-1/2"
                >
                    <flux:button class="w-full">
                        {{$warehouse->name}}
                    </flux:button>
                </a>
            @endforeach
        </nav>
    </div>

    <div class="flex justify-between mb-3">
        <flux:button wire:click="previousDay" icon="arrow-left"></flux:button>
        <p class="font-bold text-lg self-center">
            Día
        </p>
        <flux:button wire:click="nextDay" icon="arrow-right"></flux:button>
    </div>

    <div x-data="{ edit: false }" class="mb-3">
        <h1 class="font-bold text-lg sm:text-center">
            {{$date_formatted}}
            <flux:button x-on:click="edit = !edit" icon="pencil" size="xs" />
        </h1>
        <flux:input type="date" wire:model.live="date" x-show="edit"/>
        @error("date")
            <p class="text-red-400">
                {{$message}}
            </p>
        @enderror
    </div>

    <div class="text-lg mb-3">
        Total: ${{$total_cash}}
    </div>

    @error('barcode')
    <p class="text-red-400 font-bold mb-1">
        {{$message}}
    </p>
    @enderror

    <div class="mb-3">
        <livewire:products.search @add-product="addProduct($event.detail.id, 'id')" />
    </div>

    <x-table class="w-full mb-3">
        <x-slot:thead>
            <x-table.tr>
                <x-table.th>Productos</x-table.th>
            </x-table.tr>
        </x-slot:thead>

        @forelse($sales as $key => $sale)
            <x-table.tr>
                <td>
                    <div class="grid gap-1 sm:grid-cols-2">
                        <span class="pl-1 flex items-center">
                            {{$sale['name']}}
                        </span>
                        <div class="grid grid-cols-2">
                            <flux:input
                                wire:model.live.debounce.600ms="sales.{{$key}}.count"
                                icon="square-3-stack-3d"
                            />
                            <div class="flex">
                                <flux:input
                                    wire:model.live.debounce.600ms="sales.{{$key}}.cash"
                                    icon="currency-dollar"
                                />
                                <flux:button wire:click="deleteSale({{$sale['id']}})" icon="trash" />
                            </div>
                            <div class="col-span-2">
                                @error("sales.{$key}.*")
                                    <p class="text-red-400">
                                        {{$message}}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </td>
            </x-table.tr>
        @empty
            <x-table.tr>
                <td>
                    No hay resultados...
                </td>
                <td></td>
                <td></td>
            </x-table.tr>
        @endforelse
    </x-table>

    <x-pagination :$paginator />
</div>
