<div>
    <x-table class="w-full">
        <x-slot:thead>
            <x-table.th class="flex justify-between items-center">
                Presentaciones <flux:button icon="plus" wire:click="generateNew"></flux:button>
            </x-table.th>
        </x-slot:thead>
        @foreach($product->presentations as $presentation)
            <x-table.tr wire:key="{{$presentation->id}}">
                <livewire:products.presentations.edit
                    @presentation-deleted="$refresh" 
                    :$presentation 
                    :key="$presentation->id" 
                />
            </x-table.tr>
        @endforeach
    </x-table>
</div>
