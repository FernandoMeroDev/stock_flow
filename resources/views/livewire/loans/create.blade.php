@use('App\Models\Presentation')

<div>
    {{-- Always remember that you are absolutely unique. Just like everyone else. - Margaret Mead --}}
    
    <flux:input label='Préstamista' :value="Auth::user()->name" disabled />

    <flux:select label="Bodega" wire:model.live="warehouse_id" :disabled="$warehouse_id ? true : false" x-on:change="$el.disabled = true">
        <flux:select.option value="">Elije una bodega...</flux:select.option>
        @foreach($warehouses as $warehouse)
            <flux:select.option value="{{$warehouse->id}}">
                {{$warehouse->name}}
            </flux:select.option>
        @endforeach
    </flux:select>

    @if($warehouse_id)
        <flux:select label="Cliente" wire:model="client_id">
            <flux:select.option value="">Elije un cliente...</flux:select.option>
            @foreach($clients as $client)
                <flux:select.option value="{{$client->id}}">
                    {{$client->name}}
                </flux:select.option>
            @endforeach
        </flux:select>

        <flux:select label="Garante" wire:model="guarantor_id">
            <flux:select.option value="">Elije un usuario...</flux:select.option>
            @foreach($users as $user)
                <flux:select.option value="{{$user->id}}">
                    {{$user->name}}
                </flux:select.option>
            @endforeach
        </flux:select>

        <flux:input label='Límite de Pago' wire:model="dead_line" type="date" />

        <div>
            <flux:header>Productos</flux:header>

            <livewire:products.presentations.search 
                @add-presentation="addPresentation($event.detail.id, 'id')" 
                :warehouse-id="$warehouse_id"
            />

            <x-table class="w-full mb-3">
                <x-slot:thead>
                    <x-table.tr>
                        <x-table.th>Nombre</x-table.th>
                        <x-table.th>Cantidad</x-table.th>
                        <x-table.th>Precio Unitario</x-table.th>
                        <x-table.th>Precio Total</x-table.th>
                        <x-table.th></x-table.th>
                    </x-table.tr>
                </x-slot:thead>

                @forelse($presentations as $key => $presentation_data)
                    @php $presentation = Presentation::find($presentation_data['presentation_id']) @endphp
                    <x-table.tr wire:key="{{$key}}">
                        <td class="p-3">
                            {{$presentation->complete_name()}}
                        </td>
                        <td class="p-3">
                            <flux:input 
                                wire:model="presentations.{{$key}}.count" type="number" 
                                x-on:change="$wire.presentations[{{$key}}]['total_price'] = $el.value * {{$presentation->price}}"
                            />
                        </td>
                        <td class="p-3">
                            <flux:input 
                                readonly type="number" 
                                value="{{$presentation->price}}"
                            />
                        </td>
                        <td class="p-3">
                            <flux:input 
                                readonly type="number" 
                                wire:model="presentations.{{$key}}.total_price"
                            />
                        </td>
                        <td class="p-3">
                            <flux:button wire:click="removePresentation({{$key}})" icon="trash" />
                        </td>
                    </x-table.tr>
                @empty
                    <x-table.tr>
                        <td>
                            No hay resultados...
                        </td>
                    </x-table.tr>
                @endforelse
            </x-table>
        </div>

        <flux:button wire:click="store" variant="primary">Guardar</flux:button>
        
        <flux:error name="*" />
    @endif
</div>
