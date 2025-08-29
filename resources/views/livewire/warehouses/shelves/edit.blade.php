<div>
    <form wire:submit="update" class="space-y-6">
        <div>
            <flux:heading size="lg" class="flex justify-between items-end">
                Editar Percha
                <flux:button wire:click="delete" variant="danger">
                    Eliminar
                </flux:button>
            </flux:heading>
            <flux:text class="mt-2">
                Ingrese a la información de la percha. La percha editando actualmente es la <strong>número {{$form->shelf->number}}</strong>.
            </flux:text>
            <flux:text class="mt-2">
                Reducir el nivel de pisos <strong class="text-red-400">eliminará los pisos sobrantes</strong> y toda su información.
            </flux:text>
        </div>
        
        <flux:input wire:model="form.number" label="Número" placeholder="Número de la percha" min="1" />
        <flux:input wire:model="form.levels_count" label="Pisos" placeholder="Cantidad de pisos" min="1" />

        <div class="flex justify-between">
            <flux:button type="submit" variant="primary">
                Guardar
            </flux:button>

            <a href="{{route('levels.edit', $form->shelf->levels->get(1)->id)}}">
                <flux:button>Cancelar</flux:button>
            </a>
        </div>
    </form>
</div>
