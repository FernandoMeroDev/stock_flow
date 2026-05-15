@props([
    'paginator',
    'perPage' => null,
    'onlySimple' => false,
    'wirePerPageVar' => 'perPage'
])

<div class="mt-2">
    @if(
        $paginator->isNotEmpty()
        && $paginator->hasPages()
    )
        <div class="flex @if( ! $onlySimple)  md:hidden @endif items-center">
            @if ($paginator->onFirstPage())
                <flux:button class="mr-2" disabled><</flux:button>
            @else
                <flux:button x-on:click="$wire.previousPage('{{$paginator->getPageName()}}'); $el.disabled = true" class="mr-2"><</flux:button>
            @endif

            <span class="mr-1">Página:&nbsp;&nbsp;</span>
            <flux:select
                x-data="selectPage({{$paginator->currentPage()}})"
                x-on:change="$wire.setPage($el.value, '{{$paginator->getPageName()}}')"
                id="{{$paginator->getPageName()}}"
            >
                @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                    <flux:select.option :selected="$i === $paginator->currentPage()" value="{{$i}}" wire:key="{{$i}}">
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
    @endif

    <div class="hidden @if( ! $onlySimple) md:block @endif">
        {{$paginator->links(data: ['scrollTo' => false])}}
    </div>

    @if( ! is_null($perPage))
    <div class="mt-2 flex w-36">
        <span class="mr-1">Mostrar: </span>
        <flux:select wire:model.change.live="{{$wirePerPageVar}}" size="xs" required>
            <flux:select.option value="5">5</flux:select.option>
            <flux:select.option value="15">15</flux:select.option>
            <flux:select.option value="30">30</flux:select.option>
            <flux:select.option value="50">50</flux:select.option>
            <flux:select.option value="100">100</flux:select.option>
        </flux:select>
    </div>
    @endif
</div>