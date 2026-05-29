@use('App\Models\Presentation')

<div class="space-y-3">
    <flux:heading size="xl">
        Registrar Devolucion de Compra
    </flux:heading>

    <form x-data="{ warehouse_selected: false }" wire:submit="store" class="space-y-3">
        <flux:select wire:ignore x-on:change="$el.disabled = true; warehouse_selected = true" label="Bodega" wire:model="form.warehouse_id">
            <flux:select.option value="0">Seleccione...</flux:select.option>
            @foreach($warehouses as $warehouse)
                <flux:select.option value="{{$warehouse->id}}">{{$warehouse->name}}</flux:select.option>
            @endforeach
        </flux:select>

        <div x-show="warehouse_selected" class="space-y-3">
            <div class="space-y-3">
                <div>
                    <flux:input 
                        label="Agregar Productos"
                        wire:model.live="search" 
                        placeholder="Buscar..."
                    />
                    <x-table class="w-full">
                        @foreach($presentations as $presentation)
                            <x-table.tr wire:key="{{$presentation->id}}">
                                <td class="p-3">
                                    {{$presentation->complete_name()}}
                                </td>
                                <td class="p-3">
                                    {{$presentation->total_stock}}
                                </td>
                                <td class="p-1">
                                    <flux:button
                                        :disabled="$presentation->total_stock < $presentation->units"
                                        icon="plus" wire:click="addPresentation({{$presentation->id}})" 
                                    />
                                </td>
                            </x-table.tr>
                        @endforeach
                    </x-table>
                    <x-pagination :paginator="$presentations" :per-page="$presentationsPerPage" wire-per-page-var="presentationsPerPage" />
                </div>

                <x-table class="w-full overflow-x-scroll">
                    <x-slot:thead>
                        <x-table.th>
                            Productos
                        </x-table.th>
                        <x-table.th>
                            Cantidad
                        </x-table.th>
                        <x-table.th>
                            Precio Unitario
                        </x-table.th>
                        <x-table.th>
                            Total
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
                                <flux:input
                                    wire:model="form.movements.{{$key}}.unitary_price"
                                    size="sm"
                                />
                            </td>
                            <td
                                x-data="{}" class="p-3"
                                x-text="$wire.form.movements[{{$key}}].count * $wire.form.movements[{{$key}}].unitary_price"
                            >
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

            <flux:button type="submit" variant="primary">
                Guardar
            </flux:button>

            <flux:error name="*" />

            <flux:toast />
        </div>
    </form>
</div>
