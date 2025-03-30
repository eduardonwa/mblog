<x-layout>
    <x-slot name="meta">
        <!-- post metadata -->
        <meta name="title" content="{{ $post->meta_title ?? $post->title }}">
        <meta name="description" content="{{ $post->meta_description ?? $post->description }}">
        <meta name="author" content="{{ $post->author?->name }}">
    </x-slot>

    <section>
        <header>
            <span>categoria: {{ $post->category->name }}</span>
            @if ($post->featured == true)
                <h3 class="bg-pink-500 text-white p-4">
                    FEATURED
                </h3>
            @endif

            @foreach ($post->tags as $tag)
                <a href="{{ route('tag.show', $tag->slug) }}">#{{ $tag->name }}</a>
            @endforeach

            <h1>{{ $post->title }}</h1>
            <p>by {{ $post->author?->name ?? 'Rattlehead' }}</p>
            <p>
                @if ($post->created_at?->diffInWeeks(now()) >= 1)
                    {{ $post->getFormattedDate() }}
                @else
                    {{ $post->created_at?->diffForHumans() ?? 'Somewhere in Time' }}
                @endif
            </p>
        </header>

        <article>
            <img src="{{ $post->getFirstMediaUrl('thumbnails', 'lg_thumb') }}" alt="{{ $post->title }}">
        </article>

        <article>
            <span>{{ $post->language }}</span>
            <p>{{ $post->extract }}</p>
        </article>
    </section>
        
    <section>
        <p>{!! $post->body !!}</p>
    </section>
</x-layout>