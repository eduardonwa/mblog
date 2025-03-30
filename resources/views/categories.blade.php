<x-layout>
    @foreach ($posts as $post)
        <div>
            <a href="{{ route('post.show', $post) }}">
                <h1>
                    {{ $post->title }}
                </h1>
            </a>
        </div>
    @endforeach
</x-layout>