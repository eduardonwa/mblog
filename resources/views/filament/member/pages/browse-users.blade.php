<x-filament::page>
    <form method="GET" class="flex gap-4 items-center justify-end mb-6">
        <select name="platform" class="filter">
            <option value="">Filter by links</option>
            <option value="instagram" {{ request('platform') == 'instagram' ? 'selected' : '' }}>Instagram</option>
            <option value="youtube" {{ request('platform') == 'youtube' ? 'selected' : '' }}>YouTube</option>
            <option value="bandcamp" {{ request('platform') == 'bandcamp' ? 'selected' : '' }}>Bandcamp</option>
        </select>
        <button type="submit" class="search-btn">Search</button>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach ($users as $user)
            <div class="bg-morado-950 p-4 rounded-lg shadow">
                <div class="flex items-center gap-3">
                    <img src="{{ $user->avatar_url }}"
                         class="w-12 h-12 rounded-full object-cover" />
                    <div>
                        <a
                            href="{{ route('author.posts', $user->username) }}"
                            target="_blank"
                            class="text-white font-bold"
                        >
                            {{ $user->username }}
                        </a>

                        @if ($user->bio)
                            <p class="text-sm text-gray-400">{{ Str::limit($user->bio, 80) }}</p>
                        @endif

                        <div class="flex gap-2 mt-2">
                            @foreach ($user->social_links ?? [] as $platform => $url)
                                <a href="{{ $url ? $url : '#' }}" target="_blank" class="social-link">
                                    <x-dynamic-component :component="'icon.' . $platform" class="w-5 h-5" />
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $users->links() }}
    </div>
</x-filament::page>