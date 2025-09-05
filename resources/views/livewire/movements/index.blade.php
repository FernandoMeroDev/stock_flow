<div>
    <div class="flex mb-3">
        <flux:input type="text" placeholder="Buscar" wire:model.live="search" class="mr-2" />
        <a href="{{route('movements.create')}}">
            <flux:button>Nuevo</flux:button>
        </a>
    </div>

    <x-table class="w-full mb-3">
        <x-slot:thead>
            <x-table.th></x-table.th>
            <x-table.th>
                Movimiento
            </x-table.th>
            <x-table.th>
                Cantidad
            </x-table.th>
        </x-slot:thead>

        @forelse ($movements as $movement)
            <x-table.tr>
                <td class="w-5 px-3 py-1">
                    <flux:button icon="pencil" x-on:click="$dispatch('edit-movement', { id: {{$movement->id}} })"></flux:button>
                </td>
                <td class="p-3">
                    {{$movement->product_name}}
                </td>
                <td class="p-3">
                    {{$movement->count}} <br class="sm:hidden">
                    <span class="sm:ml-4">
                        ({{ match($movement->type) {'i' => 'Entradas', 'o' => 'Salidas'} }})
                    </span>
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

    <x-pagination :paginator="$movements" />

    <livewire:movements.edit @edited="$refresh" />

    <div class="mt-3">
        <flux:modal.trigger name="wipe-movements">
            <flux:button variant="danger">
                Eliminar Todos
            </flux:button>
        </flux:modal.trigger>
    </div>

    <flux:modal name="wipe-movements" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Eliminar Movimientos</flux:heading>
                <flux:text class="mt-2">
                    ¿Está seguro de <strong class="text-red-400">eliminar TODOS</strong> los movimeintos?
                </flux:text>
            </div>
            <div class="flex justify-between">
                <flux:button x-on:click="$flux.modal('wipe-movements').close()" variant="primary">Cancelar</flux:button>
                <flux:button wire:click="delete" variant="danger">Eliminar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>