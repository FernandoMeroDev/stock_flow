@props(['textAlign' => 'start'])

<th {{$attributes->merge(['class' => "px-2.5 py-2 text-{$textAlign} font-medium"])}}>
    {{$slot}}
</th>