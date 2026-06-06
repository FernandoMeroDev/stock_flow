@use('App\Models\Presentation')

<div class="space-y-3">
    <flux:heading size="xl">
        Editar Devolución de Venta
    </flux:heading>

    <form wire:submit="store" class="space-y-3">
        <flux:input 
            label="Creado Por"
            value="{{$form->disposalDevolution->user->name}}"
            readonly
        />

        <flux:input 
            label="Fecha de creación"
            value="{{$form->disposalDevolution->created_at}}"
            readonly
        />

        <div class="space-y-3">
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
                    @php $presentation = Presentation::withTrashed()->find($movement['presentation_id']); @endphp
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
                                size="sm" readonly
                            />
                        </td>
                        <td class="p-1">
                            <flux:input
                                wire:model="form.movements.{{$key}}.unitary_price"
                                size="sm" readonly
                            />
                        </td>
                        <td
                            x-data="{}" class="p-3"
                            x-text="$wire.form.movements[{{$key}}].count * $wire.form.movements[{{$key}}].unitary_price"
                        >
                        </td>
                        <td class="p-1">
                            {{-- <flux:button icon="trash" wire:click="removePresentation({{$key}})" variant="danger" /> --}}
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

        {{-- <flux:button type="submit" variant="primary">
            Guardar
        </flux:button> --}}

        <flux:error name="*" />

        <flux:toast />
    </form>
</div>
