@use('App\Models\Presentation')

<div class="space-y-3">
    <flux:heading size="xl">
        Registrar Compra
    </flux:heading>

    <form wire:submit="store" class="space-y-3">
        <flux:input 
            label="Número de Factura"
            wire:model="form.invoice_number" 
            placeholder="000-000-000001"
        />

        <flux:select label="Proveedor" wire:model="form.provider_id">
            <flux:select.option value="">Seleccione...</flux:select.option>
            @foreach($providers as $provider)
                <flux:select.option value="{{$provider->id}}">{{$provider->name}}</flux:select.option>
            @endforeach
        </flux:select>

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
                            <td class="p-1">
                                <flux:button icon="plus" wire:click="addPresentation({{$presentation->id}})" />
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
    </form>
</div>
