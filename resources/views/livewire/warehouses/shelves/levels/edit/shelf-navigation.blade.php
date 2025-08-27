<div>
    <x-table class="w-full mt-6">
        <x-slot:thead>
            <x-table.th>
                Percha {{$current_level->shelf->number}}
            </x-table.th>
            <x-table.th></x-table.th>
        </x-slot:thead>

        @foreach ($current_level->shelf->levels()->orderBy('number', 'desc')->get() as $level)
            <x-table.tr wire:key="level-{{$level->id}}">
                @if($level->id == $current_level->id)
                    <td class="p-3">
                        <span class="font-bold">
                        Piso {{$level->number}}
                        </span>
                    </td>
                    <td>
                        <span class="font-bold">[Actual]</span>
                    </td>
                @else
                    <td class="p-3">
                        <a href="{{route('levels.edit', $level->id)}}">
                        Piso {{$level->number}}
                        </a>
                    </td>
                    <td>
                        {{$level->calc_products_total_count()->products_total_count}} productos
                    </td>
                @endif
            </x-table.tr>
        @endforeach
    </x-table>
</div>
