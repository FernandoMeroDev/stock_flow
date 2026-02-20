<div>
    <x-table class="w-full mb-3">
        <x-slot:thead>
            <x-table.th>
                <div class="flex justify-between items-center">
                    Cajas <flux:button wire:click="createNew" icon="plus" size="sm" />
                </div>
            </x-table.th>
        </x-slot:thead>

        @forelse ($cash_boxes as $cash_box)
            <x-table.tr wire:key="{{$cash_box->id}}">
                <td class="p-3">
                    <livewire:cash-boxes.edit 
                        :key="$cash_box->id" 
                        :cash-box="$cash_box" 
                        @cash-box-deleted="$refresh" 
                    />
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
</div>
