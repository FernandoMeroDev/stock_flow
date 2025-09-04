@props(['href'])

<a href="{{$href}}" class="block mb-2 p-5 w-45 rounded-xl border border-neutral-200 dark:border-neutral-700">
    {{$slot}}
</a>