@vite('resources/styles/components/filament-dashboard.scss')

<x-filament::page>
    <div class="dashboard-user">
        @if ($user)
            <section class="dashboard-user__info">
                <a href="member/profile" class="no-decor">
                    <img class="avatar" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" />
                </a>
                <h2 class="username">{{ $user->slug }}</h2>
            </section>
        @endif

        <section class="dashboard-user__stats">
            <div class="card">
                <h2 class="number">{{ $user_stats['likes_received_count'] }}</h2>
                <p>Total uphails</p>
            </div>
            <div class="card">
                <h2 class="number">{{ $user_stats['comments_count'] }}</h2>
                <p>Total comments</p>
            </div>
        </section>
    </div>
</x-filament::page>
