<div class="space-y-3">
    <div>
        <flux:modal.trigger name="create-provider">
            <flux:button>
                Agregar Nuevo
            </flux:button>
        </flux:modal.trigger>
    </div>

    <div wire:ignore class="m-0">
        <flux:modal name="create-provider">
            <div class="py-6">
                <livewire:providers.create
                    @created-provider="$refresh; $flux.modal('create-provider').close()" 
                />
            </div>
        </flux:modal>
    </div>

    <x-table class="w-full">
        <x-slot:thead>
            <x-table.th>
                Proveedores
            </x-table.th>
            <x-table.th></x-table.th>
        </x-slot:thead>
        @forelse($providers as $provider)
            <x-table.tr wire:key="{{$provider->id}}">
                <td class="p-3">
                    {{$provider->name}}
                </td>
                <td class="text-center">
                    <flux:button icon="pencil" size="sm" class="mr-1" />
                    <flux:button icon="trash" wire:click="destroy({{$provider->id}})" size="sm" variant="danger" />
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

    <x-pagination :paginator="$providers" :$perPage />
</div>
