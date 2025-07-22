@extends('layouts.styles')

@section('title', $post->title)

@section('content')
    <section
        class="post-member container" data-type="blog-post"
        style="margin-block-start: 1rem;"
    >
        <div style=" position: relative; height: 40px;">
            <h2 
                class="clr-primary-400 fs-600 uppercase text-center margin-block-4"
                style="
                    background-color: #404068;
                    position: fixed;
                    top: -16px;
                    left: 0;
                    width: 100%;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    padding: .5rem;
                "
            >
                preview
            </h2>
        </div>

        @php
            $media = $post->getMedia('thumbnails')->first();
        @endphp
    
        @if($media)
            <picture>
                <source media="(min-width: 768px)" srcset="{{ $media->getUrl('max_thumb') }}">
                <source media="(max-width: 767px)" srcset="{{ $media->getUrl('lg_thumb') }}">
                <img src="{{ $media->getUrl('lg_thumb') }}" alt="{{ $post->title }}">
            </picture>
        @endif
    
        <header class="post-header">
            <h2 class="title">{{ $post->title }}</h2>
            <div class="meta-primary">
                <p class="author">by <span>{{ $post->user->username }}</span></p>
                <p>{{ $post->category->name ?? $post->channel->name }}</p>
            </div>
        </header>

        @if($post->post_template == 'list')
            {!! $post->list_data_html !!}
        @else
            <article>
                {!! $post->body !!}
            </article>
        @endif
        
    </section>
@endsection