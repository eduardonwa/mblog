<x-filament::page>
    <div class="dashboard-user">
        @if ($user)
            <section class="dashboard-user__info">
                <a href="{{ route('profile.edit') }}" class="no-decor">
                    <img class="avatar" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" />
                </a>
                <h2>{{ $user->slug }}</h2>
            </section>
        @endif

        <section class="dashboard-user__stats">
            <div class="card">
                <h2>{{ $user_stats['likes_received_count'] }}</h2>
                <p>Total uphails</p>
            </div>
            <div class="card">
                <h2>{{ $user_stats['comments_count'] }}</h2>
                <p>Total comments</p>
            </div>
        </section>

        <section class="dashboard-user__posts">
            @foreach ($posts as $post)
                <a href="{{ route('channel.post.show', [
                    'channel' => $post->channel->slug,
                    'post' => $post->slug
                    ])}}"
                    class="dashboard-user__posts__wrapper no-decor"
                >
                    <h2>{{ $post->title }}</h2>
                    <div>{{ $post->likes_count }} likes</div>
                    <div>{{ $post->comments_count }} comments</div>
                </a>
            @endforeach
        </section>
    </div>
</x-filament::page>
