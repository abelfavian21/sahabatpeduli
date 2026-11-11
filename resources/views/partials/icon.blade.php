@php $n = $name ?? 'heart'; @endphp
<svg viewBox="0 0 100 100" fill="none" aria-hidden="true" class="{{ $class ?? 'h-20 w-20' }}">
@switch($n)
@case('wave')
    <path d="M14 52 50 24l36 28v30H14z" fill="#FFB547"/>
    <path d="M14 52 50 24l36 28" stroke="#E8891A" stroke-width="5" stroke-linejoin="round"/>
    <rect x="42" y="58" width="16" height="24" rx="3" fill="#fff"/>
    <path d="M4 72c8-8 14-8 23 0s15 8 23 0 15-8 23 0 15 8 23 0v22H4z" fill="#3B8EFF"/>
    <path d="M4 82c8-6 14-6 23 0s15 6 23 0 15-6 23 0 15 6 23 0v12H4z" fill="#1F6FE0"/>
    @break
@case('book')
    <path d="M10 28c14-6 28-6 40 4v52c-12-10-26-10-40-4z" fill="#3B8EFF"/>
    <path d="M90 28c-14-6-28-6-40 4v52c12-10 26-10 40-4z" fill="#7DB6FF"/>
    <path d="M18 38c9-3 18-2 24 3M18 50c9-3 18-2 24 3M18 62c9-3 18-2 24 3" stroke="#fff" stroke-width="4" stroke-linecap="round"/>
    <circle cx="80" cy="20" r="10" fill="#C4EE2F"/>
    @break
@case('people')
    <circle cx="50" cy="30" r="13" fill="#FFB547"/>
    <path d="M26 88c0-20 10-32 24-32s24 12 24 32z" fill="#3B8EFF"/>
    <circle cx="20" cy="44" r="10" fill="#C4EE2F"/>
    <path d="M3 88c0-16 7-26 17-26 5 0 8 2 10 4-6 6-9 14-9 22z" fill="#8FCB10"/>
    <circle cx="80" cy="44" r="10" fill="#FF6B81"/>
    <path d="M97 88c0-16-7-26-17-26-5 0-8 2-10 4 6 6 9 14 9 22z" fill="#E0445D"/>
    @break
@default
    <path d="M50 88C14 63 6 42 17 27c8-11 24-9 33 4 9-13 25-15 33-4 11 15 3 36-33 61z" fill="#FF6B81"/>
    <path d="M50 40v26M37 53h26" stroke="#fff" stroke-width="8" stroke-linecap="round"/>
@endswitch
</svg>
