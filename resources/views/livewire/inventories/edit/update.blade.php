<div class="space-y-3">
    <div>
        <flux:heading size="lg">Editar Fecha</flux:heading>
        <flux:text class="mt-2">Cambie la fecha del Inventario.</flux:text>
    </div>

    <div class="flex space-x-1">
        <flux:input wire:model="form.saved_at" placeholder="Fecha de inventario..." />
        <flux:button wire:click="update">
            Guardar
        </flux:button>
        <p wire:loading class="ml-2 self-end">
            Guardando...
        </p>
    </div>

    <flux:error name="form.saved_at" />
</div>
