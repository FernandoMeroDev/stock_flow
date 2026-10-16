@use('App\Models\Presentation')

<div>
    <p class="mb-3 text-xl font-bold">
        ${{$loan->paid_value()}} Pagados / ${{$loan->total_value()}} Valor total
    </p>
    
    @if($loan->finished)
        <p class="mb-3 text-xl text-green-500">
            Préstamo Pagado
        </p>
        <x-table class="w-full">
            <x-slot:thead>
                <x-table.th>Fecha de Pagos</x-table.th>
                <x-table.th>Valor</x-table.th>
                <x-table.th>Registrado por</x-table.th>
            </x-slot:thead>

            @foreach ($loan->payments as $payment)
                <x-table.tr wire:key="{{$payment->id}}">
                    <td class="p-3">
                        {{$payment->created_at}}
                    </td>
                    <td class="p-3">
                        {{$payment->value}}
                    </td>
                    <td class="p-3">
                        {{$payment->created_by->name}}
                    </td>
                </x-table.tr>
            @endforeach
        </x-table>
    @else
        <flux:modal.trigger name="payments-index">
            <flux:button variant="primary">Registrar Pago</flux:button>
        </flux:modal.trigger>

        <livewire:loans.payments.index :$loan />
    @endif
    
    <div class="my-6"></div>
    
    <flux:input label='Préstamista' :value="$loan->lender->name" disabled />

    <flux:select label="Bodega" readonly>
        <flux:select.option :selected="true">
            {{$loan->warehouse->name}}
        </flux:select.option>
    </flux:select>

    <flux:select label="Cliente" readonly>
        <flux:select.option :selected="true">
            {{$loan->client->name}}
        </flux:select.option>
    </flux:select>

    <flux:select label="Garante" readonly>
        <flux:select.option :selected="true">
            {{$loan->guarantor->name}}
        </flux:select.option>
    </flux:select>

    <flux:input label='Límite de Pago' value="{{$loan->dead_line}}" type="date" readonly />

    <div>
        <flux:header>Productos</flux:header>

        <x-table class="w-full mb-3">
            <x-slot:thead>
                <x-table.tr>
                    <x-table.th>Nombre</x-table.th>
                    <x-table.th>Cantidad</x-table.th>
                    <x-table.th>Precio Unitario</x-table.th>
                    <x-table.th>Precio Total</x-table.th>
                </x-table.tr>
            </x-slot:thead>

            @forelse($loan->loanPresentation as $loanPresentation)
                <x-table.tr wire:key="{{$loanPresentation->id}}">
                    <td class="p-3">
                        {{$loanPresentation->presentation->complete_name()}}
                    </td>
                    <td class="p-3">
                        <flux:input value="{{$loanPresentation->count}}" readonly />
                    </td>
                    <td class="p-3">
                        <flux:input value="{{$loanPresentation->presentation->price}}" readonly />
                    </td>
                    <td class="p-3">
                        <flux:input value="{{$loanPresentation->count * $loanPresentation->presentation->price}}" readonly />
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

    <div class="my-6 flex space-x-1">
        <flux:input label="Creado el" value="{{date('Y-m-d H:s:i', strtotime($loan->created_at))}}" type="datetime" readonly />

        <flux:input label="Creado por" value="{{$loan->created_by->name}}" readonly />
    </div>
</div>
