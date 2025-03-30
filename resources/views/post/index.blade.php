<x-layout>
    @foreach ($posts as $post)
        <a href="{{ route('post.show', $post->slug) }}">
            <h1>{{ $post->title }}</h1>    
        </a>
        <p>{{ $post->extract }}</p>
        <p>{{ $post->author->name }}</p>
        <span>{{ $post->category->name }}</span>
    @endforeach
</x-layout>