<div>
    <flux:modal name="payments-index" class="md:w-96">
        <div class="space-y-6">
            <p class="mb-3 text-xl font-bold">
                Valor por pagar: ${{$loan->remaining_value()}}
            </p>
            <x-table class="w-full">
                <x-slot:thead>
                    <x-table.th>Fecha</x-table.th>
                    <x-table.th>Valor</x-table.th>
                    <x-table.th>Registrado por</x-table.th>
                </x-slot:thead>

                @forelse ($loan->payments as $payment)
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
                @empty
                    <x-table.tr>
                        <td class="p-3">
                            No hay resultados...
                        </td>
                        <td></td>
                        <td></td>
                    </x-table.tr>
                @endforelse
            </x-table>

            <div class="flex space-x-2">
                <p>Nuevo Pago</p>
                <flux:input wire:model="new_payment_value" type="number" placeholder="$0.00" />
                <flux:button icon="plus" wire:click="addPayment" class="min-w-12" variant="primary" />
            </div>

            <flux:error name="*" />

            <div x-data="{ confirm: false }">
                <flux:button x-on:click="confirm = true">
                    Pago Total
                </flux:button>

                <div x-show="confirm">
                    <p>¿Seguro quieres registrar el pago TOTAL del prestamo?</p>
                    <flux:button wire:click="completePay" variant="danger">
                        Registrar Pago TOTAL
                    </flux:button>
                </div>
            </div>
        </div>
    </flux:modal>
</div>
