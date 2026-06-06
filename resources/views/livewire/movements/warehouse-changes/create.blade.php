@use('App\Models\Presentation')

<div class="space-y-3">
    <flux:heading size="xl">
        Registrar Cambio de Bodega
    </flux:heading>

    <div x-data="{ warehouse_selected: false }" class="space-y-3">
        <flux:select wire:ignore x-on:change="$el.disabled = true; warehouse_selected = true" label="Desde la Bodega" wire:model.live="form.warehouse_id">
            <flux:select.option value="0">Seleccione...</flux:select.option>
            @foreach($warehouses as $warehouse)
                <flux:select.option value="{{$warehouse->id}}">{{$warehouse->name}}</flux:select.option>
            @endforeach
        </flux:select>

        <div x-show="warehouse_selected">
            <flux:select label="Hasta la Bodega" wire:model="form.warehouse_to_id">
                <flux:select.option value="0">Seleccione...</flux:select.option>
                @foreach($warehouses_to as $warehouse)
                    <flux:select.option value="{{$warehouse->id}}">{{$warehouse->name}}</flux:select.option>
                @endforeach
            </flux:select>

            <div class="mb-3">
                <flux:label>
                    Buscar Productos
                </flux:label>
                @error('barcode')
                <p class="text-red-400 font-bold mb-1">
                    {{$message}}
                </p>
                @enderror

                @if($form->warehouse_id !== 0)
                    <livewire:products.presentations.search 
                        @add-presentation="addPresentation($event.detail.id, 'id')" 
                        :warehouse-id="$form->warehouse_id"
                    />
                @endif

                <x-table class="w-full overflow-x-scroll">
                    <x-slot:thead>
                        <x-table.th>
                            Productos
                        </x-table.th>
                        <x-table.th>
                            Cantidad
                        </x-table.th>
                        <x-table.th></x-table.th>
                    </x-slot:thead>
                    @forelse($form->movements as $key => $movement)
                        @php $presentation = Presentation::find($movement['presentation_id']); @endphp
                        <x-table.tr 
                            wire:key="{{$presentation->id . '-' . $key}}"
                        >
                            <td class="p-3">
                                {{$presentation->complete_name()}}
                                <flux:error name="form.movements.{{$key}}.presentation_id" />
                            </td>
                            <td class="p-1">
                                <flux:input
                                    wire:model="form.movements.{{$key}}.count"
                                    size="sm"
                                />
                            </td>
                            <td class="p-1">
                                <flux:button icon="trash" wire:click="removePresentation({{$key}})" variant="danger" />
                            </td>
                        </x-table.tr>
                    @empty
                        <x-table.tr wire:key="0-0">
                            <td class="p-3">
                                Ningun producto seleccionado...
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </x-table.tr>
                    @endforelse
                </x-table>
            </div>

            <flux:button wire:click="store" variant="primary">
                Guardar
            </flux:button>

            <flux:error name="*" />

            <flux:toast />
        </div>
    </div>
</div>
