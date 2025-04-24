<table {{$attributes}}>
    @if (isset($thead))
        <thead class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-900 text-sm font-medium text-black dark:text-white">
            <tr>
                {{$thead}}
            </tr>
        </thead>
    @endif
    <tbody class="group text-sm text-stone-800">
        {{$slot}}
    </tbody>
</table>