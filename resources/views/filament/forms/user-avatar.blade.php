@php
    $user = auth()->user();
    $avatar = $user?->getFirstMediaUrl('user_avatar') ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name);
@endphp

<div class="flex justify-center mb-4">
    <x-filament::avatar
        src="{{ $avatar }}"
        alt="{{ $user->name }}"
        class="w-24 h-24"
    />
</div>