<div class="space-y-3">
    <flux:heading size="xl">
        Clientes
    </flux:heading>

    <div>
        <flux:modal.trigger name="create-client">
            <flux:button>
                Agregar Nuevo
            </flux:button>
        </flux:modal.trigger>
    </div>

    <div wire:ignore class="m-0">
        <livewire:clients.create />

        <livewire:clients.edit />
    </div>

    <x-table class="w-full">
        <x-slot:thead>
            <x-table.th>
                Nombre
            </x-table.th>
            <x-table.th></x-table.th>
        </x-slot:thead>
        @forelse($clients as $client)
            <x-table.tr wire:key="{{$client->id}}">
                <td class="p-3 break-words">
                    {{$client->name}}
                </td>
                <td>
                    <div x-data="{ deleting: false }" class="p-1">
                        <div class="flex items-center">
                            <flux:button icon="pencil" wire:click="$dispatch('edit-client', { id: {{$client->id}} } )" size="sm" class="mr-1" />
                            <flux:button icon="trash" x-on:click="deleting = true" size="sm" variant="danger"/>
                        </div>
                        <div x-show="deleting" class="mt-1">
                            <div>¿Eliminar?</div>
                            <flux:button x-on:click="deleting = false" size="xs">
                                No
                            </flux:button>
                            <flux:button wire:click="destroy({{$client->id}})" size="xs" variant="danger">
                                Si
                            </flux:button>
                        </div>
                    </div>
                </td>
            </x-table.tr>
        @empty
            <x-table.tr wire:key="0">
                <td class="p-3">
                    No se encontraron resultados...
                </td>
                <td></td>
            </x-table.tr>
        @endforelse
    </x-table>

    <x-pagination :paginator="$clients" :$perPage />
</div>
