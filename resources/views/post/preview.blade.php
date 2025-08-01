<script src="//unpkg.com/alpinejs" defer></script>

@extends('layouts.styles')

@section('title', $post->title)

@section('content')
    <section x-data="{loaded: false}" x-init="setTimeout(() => loaded = true, 1000)">
        <div x-show="!loaded" x-cloak>
            <div
                id="loader"
                style="
                    position:fixed;
                    top:0;
                    left:0;
                    right:0;
                    bottom:0;
                    background:#222;
                    z-index:10000;
                    color:#fff;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                "
            >
                Loading preview...
            </div>
        </div>

        <div
            class="post-member container" data-type="blog-post"
            style="margin-block-start: 1rem;"
            x-show="loaded" x-cloak
        >    
            <header style="position: relative; height: 40px;">
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
                        box-shadow: 0 6px 16px -4px rgba(0, 0, 0, 0.14);
                    "
                >
                    preview
                </h2>
            </header>
    
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
        
            <article class="post-header">
                <h2 class="title">{{ $post->title }}</h2>
                <div class="meta-primary">
                    <p class="author">by <span>{{ $post->user->username }}</span></p>
                    <p>{{ $post->category->name ?? $post->channel->name }}</p>
                </div>
            </article>
    
            @if($post->post_template == 'list')
                {!! $post->list_data_html !!}
            @else
                <div class="post-body">
                    {!! $post->body !!}
                </div>
            @endif
        </div>
    </section>
@endsection

<style>
    iframe {
        border: none;
    }
    [x-cloak] { display: none !important; }
</style>