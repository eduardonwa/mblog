<x-layout>
    @foreach ($posts as $post)
        <div>
            <a href="{{ route('post.show', $post) }}">
                <h1>
                    Post: {{ $post->title }}
                </h1>
            </a>

            <div>
                @foreach ($post->tags as $tag)
                    <span>
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        </div>
    @endforeach
</x-layout>