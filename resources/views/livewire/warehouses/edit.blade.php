<div class="space-y-6">
    <div>
        <flux:heading size="lg">Editar Bodega</flux:heading>
        <flux:text class="mt-2">Haga cambios a la información de la Bodega.</flux:text>
        <div class="flex mt-3">
            <flux:input wire:model="form.name" placeholder="Nombre de la Bodega" class="mr-2" />
            <flux:button 
                type="submit" 
                x-on:click="$wire.update()"
            >Guardar cambios</flux:button>
            <p wire:loading class="ml-2 self-end">
                Guardando...
            </p>
        </div>
    </div>
    <x-table class="w-full mb-3">
        <x-slot:thead>
            <x-table.th></x-table.th>
            <x-table.th>
                Perchas
            </x-table.th>
        </x-slot:thead>

        @forelse ($shelves as $shelf)
            <x-table.tr>
                <td class="w-5 px-3 py-1">
                    <a href="#">
                        <flux:button icon="pencil"></flux:button>
                    </a>
                </td>
                <td class="p-3">
                    {{$shelf->name}}
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

    {{-- <x-pagination :paginator="$shelves" /> --}}
</div>
