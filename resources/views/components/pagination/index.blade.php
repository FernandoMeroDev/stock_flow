@props([
    'paginator'
])

<div class="flex items-center">
    @if ($paginator->onFirstPage())
        <flux:button class="mr-2" disabled><</flux:button>
    @else
        <flux:button x-on:click="$wire.previousPage('{{$paginator->getPageName()}}'); $el.disabled = true" class="mr-2"><</flux:button>
    @endif

    <span class="mr-1">Página: </span>
    <flux:select
        x-data="selectPage({{$paginator->currentPage()}})"
        x-on:change="$wire.setPage($el.value, '{{$paginator->getPageName()}}')"
        id="{{$paginator->getPageName()}}"
    >
        @for ($i = 1; $i <= $paginator->lastPage(); $i++)
            <flux:select.option value="{{$i}}" wire:key="{{$i}}">
                {{$i}}
            </flux:select.option>
        @endfor
    </flux:select>

    @if ($paginator->onLastPage())
        <flux:button class="ml-2" disabled>></flux:button>    
    @else
        <flux:button x-on:click="$wire.nextPage('{{$paginator->getPageName()}}'); $el.disabled = true" class="ml-2">></flux:button>
    @endif

    @script
    <script>
        Alpine.data('selectPage', (currentPage) => ({
            // Select the option that match with the current page
            init() {
                const element = document.getElementById('{{$paginator->getPageName()}}');
                for(let option of element.options)
                    if(option.value == currentPage) option.selected = true;
            }
        }));
    </script>
    @endscript
</div>
