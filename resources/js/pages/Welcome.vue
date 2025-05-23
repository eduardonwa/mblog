<script setup lang="ts">
  import { Head, Link, usePage } from '@inertiajs/vue3';
  import SiteLayout from '@/layouts/SiteLayout.vue';

  const { featuredPosts, staffPosts, leaderboard, recent } = defineProps({
    featuredPosts: Object,
    staffPosts: Object,
    leaderboard: Object,
    recent: Object,
  });

  const user = usePage().props.auth.user;
</script>

<template>
  <SiteLayout>
    <Head title="Welcome" />

    <div class="welcome-grid ! mx-auto">
      <hr class="grid-border" role="separator" data-type="left">
      <hr class="grid-border" role="separator" data-type="right">
      <!-- 1. Primer post "featured" -->
      <section v-if="featuredPosts?.length > 0" class="featured-post" data-type="main">
        <Link style="text-decoration: none;" :href="route('post.show', featuredPosts?.[0].slug)">
          <picture>
            <source media="(min-width: 768px)" :srcset="featuredPosts?.[0]?.thumbnail_urls?.max">
            <source media="(max-width: 767px)" :srcset="featuredPosts?.[0]?.thumbnail_urls?.lg">
            <img 
              :src="featuredPosts?.[0]?.thumbnail_urls?.lg" 
              :alt="featuredPosts?.[0]?.title"
              class="post-thumbnail"
            >
          </picture>

          <h2>{{ featuredPosts?.[0].title }}</h2>
          
          <p class="extract">{{ featuredPosts?.[0].extract }}</p>

          <div class="post-info">
            <!-- upvotes -->
            <div class="post-info__upvotes">
              <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="30" height="30" viewBox="0 0 34 34">
                <path d="M24 6c-1.7 0-3 1.3-3 3v2.2c-.3-.1-.6-.2-1-.2-.8 0-1.5.3-2 .8-.5-.5-1.2-.8-2-.8-.4 0-.7.1-1 .2V7c0-1.7-1.3-3-3-3S9 5.3 9 7v10.9c-.5-.5-1.2-.9-2-.9-.9 0-1.7.3-2.3.9-1.1 1.1-1.2 3-.1 4.2l3.2 3.6C9.6 27.8 12.2 29 15 29h3.7c4.6 0 8.4-3.8 8.4-8.4V9C27 7.3 25.7 6 24 6zm1 10v4.6c0 3.5-2.9 6.4-6.4 6.4h-3.7c-2.2 0-4.2-.9-5.7-2.6L6 20.8c-.4-.4-.4-1.1.1-1.5.2-.2.5-.3.8-.3.3 0 .6.2.8.4l1.5 1.8c.3.3.7.4 1.1.3.4-.1.7-.5.7-.9V7c0-.6.4-1 1-1s1 .4 1 1v8c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1V9c0-.6.4-1 1-1s1 .4 1 1v7z"/>
              </svg>
              <span>{{ featuredPosts?.[0].likes_count }} hailed</span>
            </div>
    
            <!-- comments -->
            <div class="post-info__comments">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 15 12">
                  <path id="Vector" d="M6.063,12.159a1.277,1.277,0,0,1-1.015-.541L4.2,10.49a.266.266,0,0,0-.113-.056H3.807C1.455,10.433,0,9.8,0,6.627V3.807C0,1.314,1.314,0,3.807,0H8.318c2.493,0,3.807,1.314,3.807,3.807v2.82c0,2.493-1.314,3.807-3.807,3.807H8.036a.147.147,0,0,0-.113.056l-.846,1.128A1.277,1.277,0,0,1,6.063,12.159ZM3.807.846C1.788.846.846,1.788.846,3.807v2.82c0,2.549.874,2.961,2.961,2.961h.282a1.058,1.058,0,0,1,.79.395l.846,1.128a.392.392,0,0,0,.677,0l.846-1.128a.988.988,0,0,1,.79-.395h.282c2.019,0,2.961-.942,2.961-2.961V3.807c0-2.019-.942-2.961-2.961-2.961Z" fill="#b4bddc"/>
                  <path id="Vector-2" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(5.499 4.935)" fill="#b4bddc"/>
                  <path id="Vector-3" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(7.755 4.935)" fill="#b4bddc"/>
                  <path id="Vector-4" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(3.243 4.935)" fill="#b4bddc"/>
                </svg>
              <span> comments </span>
            </div>

            <Link :href="route('author.posts', featuredPosts?.[0].user?.name)">
              <p>{{ featuredPosts?.[0].user?.name || 'Rattlehead' }}</p>
            </Link>
          </div>
          
        </Link>
      </section>
  
      <!-- 2. Leaderboard -->
      <section v-if="leaderboard?.length" class="leaderboard">
        <div class="header-section">
          <h2 class="uppercase">leaderboard</h2>
        </div>
        <article class="leaderboard__info" v-for="post in leaderboard" :key="post.id">      
          <Link style="text-decoration: none;" :href="route('post.show', post.slug)">
            <h3 class="leaderboard__info__title">{{ post.title }}</h3>
            <span class="leaderboard__info__author">{{ post.user?.name }}</span>          
          </Link>
          <span class="leaderboard__info__likes">{{ post.likes_count }}</span>
        </article>
      </section>
  
      <!-- 3. grupo de 2 "featured" -->
      <section v-if="featuredPosts?.length > 1" class="featured-post" data-type="secondary">
        <article v-for="(post, index) in featuredPosts?.slice(1, 3)" :key="post.id">
          <Link style="text-decoration: none;" :href="route('post.show', post.slug)">
            <picture>
                <source media="(min-width: 768px)" :srcset="post.thumbnail_urls?.max">
                <source media="(max-width: 767px)" :srcset="post.thumbnail_urls?.lg">
                <img 
                  :src="post.thumbnail_urls?.lg" 
                  :alt="post.title"
                  class="post-thumbnail"
                >
            </picture>
            <div class="post-content">
              <h2> {{ post.title }} </h2>
              <p class="extract margin-block-3">{{ post?.extract }}</p>
              <div class="post-info">
                <!-- upvotes -->
                <div class="post-info__upvotes">
                  <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="34" height="27" viewBox="0 0 34 35">
                    <path d="M24 6c-1.7 0-3 1.3-3 3v2.2c-.3-.1-.6-.2-1-.2-.8 0-1.5.3-2 .8-.5-.5-1.2-.8-2-.8-.4 0-.7.1-1 .2V7c0-1.7-1.3-3-3-3S9 5.3 9 7v10.9c-.5-.5-1.2-.9-2-.9-.9 0-1.7.3-2.3.9-1.1 1.1-1.2 3-.1 4.2l3.2 3.6C9.6 27.8 12.2 29 15 29h3.7c4.6 0 8.4-3.8 8.4-8.4V9C27 7.3 25.7 6 24 6zm1 10v4.6c0 3.5-2.9 6.4-6.4 6.4h-3.7c-2.2 0-4.2-.9-5.7-2.6L6 20.8c-.4-.4-.4-1.1.1-1.5.2-.2.5-.3.8-.3.3 0 .6.2.8.4l1.5 1.8c.3.3.7.4 1.1.3.4-.1.7-.5.7-.9V7c0-.6.4-1 1-1s1 .4 1 1v8c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1V9c0-.6.4-1 1-1s1 .4 1 1v7z"/>
                  </svg>
                  <span>{{ post.likes_count }}</span>
                </div>
        
                <!-- comments -->
                <div class="post-info__comments">
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 15 12">
                    <path id="Vector" d="M6.063,12.159a1.277,1.277,0,0,1-1.015-.541L4.2,10.49a.266.266,0,0,0-.113-.056H3.807C1.455,10.433,0,9.8,0,6.627V3.807C0,1.314,1.314,0,3.807,0H8.318c2.493,0,3.807,1.314,3.807,3.807v2.82c0,2.493-1.314,3.807-3.807,3.807H8.036a.147.147,0,0,0-.113.056l-.846,1.128A1.277,1.277,0,0,1,6.063,12.159ZM3.807.846C1.788.846.846,1.788.846,3.807v2.82c0,2.549.874,2.961,2.961,2.961h.282a1.058,1.058,0,0,1,.79.395l.846,1.128a.392.392,0,0,0,.677,0l.846-1.128a.988.988,0,0,1,.79-.395h.282c2.019,0,2.961-.942,2.961-2.961V3.807c0-2.019-.942-2.961-2.961-2.961Z" fill="#b4bddc"/>
                    <path id="Vector-2" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(5.499 4.935)" fill="#b4bddc"/>
                    <path id="Vector-3" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(7.755 4.935)" fill="#b4bddc"/>
                    <path id="Vector-4" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(3.243 4.935)" fill="#b4bddc"/>
                  </svg>
                  <span>  </span>
                </div>
        
                <Link :href="route('author.posts', featuredPosts?.[0].user?.name)">
                  <p>{{ featuredPosts?.[0].user?.name || 'Rattlehead' }}</p>
                </Link>
              </div>
            </div>
          </Link>
        </article>
      </section>

      <!-- 4. posts del staff (5 primeros) -->
      <section v-if="staffPosts?.length" class="staff-posts">  
        <article v-for="(post, index) in staffPosts?.slice(0, 5)" :key="post.id">
          <Link style="text-decoration: none;" :href="route('post.show', post.slug)">
            <h2>{{ post.title }}</h2>
            <p class="extract">{{ post?.extract }}</p>
            
            <div class="post-info">
              <!-- upvotes -->
              <div class="post-info__upvotes">
                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="34" height="27" viewBox="0 0 34 35">
                  <path d="M24 6c-1.7 0-3 1.3-3 3v2.2c-.3-.1-.6-.2-1-.2-.8 0-1.5.3-2 .8-.5-.5-1.2-.8-2-.8-.4 0-.7.1-1 .2V7c0-1.7-1.3-3-3-3S9 5.3 9 7v10.9c-.5-.5-1.2-.9-2-.9-.9 0-1.7.3-2.3.9-1.1 1.1-1.2 3-.1 4.2l3.2 3.6C9.6 27.8 12.2 29 15 29h3.7c4.6 0 8.4-3.8 8.4-8.4V9C27 7.3 25.7 6 24 6zm1 10v4.6c0 3.5-2.9 6.4-6.4 6.4h-3.7c-2.2 0-4.2-.9-5.7-2.6L6 20.8c-.4-.4-.4-1.1.1-1.5.2-.2.5-.3.8-.3.3 0 .6.2.8.4l1.5 1.8c.3.3.7.4 1.1.3.4-.1.7-.5.7-.9V7c0-.6.4-1 1-1s1 .4 1 1v8c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1V9c0-.6.4-1 1-1s1 .4 1 1v7z"/>
                </svg>
                <span>{{ post.likes_count }}</span>
              </div>
        
              <!-- comments -->
              <div class="post-info__comments">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 15 12">
                  <path id="Vector" d="M6.063,12.159a1.277,1.277,0,0,1-1.015-.541L4.2,10.49a.266.266,0,0,0-.113-.056H3.807C1.455,10.433,0,9.8,0,6.627V3.807C0,1.314,1.314,0,3.807,0H8.318c2.493,0,3.807,1.314,3.807,3.807v2.82c0,2.493-1.314,3.807-3.807,3.807H8.036a.147.147,0,0,0-.113.056l-.846,1.128A1.277,1.277,0,0,1,6.063,12.159ZM3.807.846C1.788.846.846,1.788.846,3.807v2.82c0,2.549.874,2.961,2.961,2.961h.282a1.058,1.058,0,0,1,.79.395l.846,1.128a.392.392,0,0,0,.677,0l.846-1.128a.988.988,0,0,1,.79-.395h.282c2.019,0,2.961-.942,2.961-2.961V3.807c0-2.019-.942-2.961-2.961-2.961Z" fill="#b4bddc"/>
                  <path id="Vector-2" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(5.499 4.935)" fill="#b4bddc"/>
                  <path id="Vector-3" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(7.755 4.935)" fill="#b4bddc"/>
                  <path id="Vector-4" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(3.243 4.935)" fill="#b4bddc"/>
                </svg>
                <span> </span>
              </div>
        
              <Link :href="route('author.posts', featuredPosts?.[0].user?.name)">
                <p>{{ featuredPosts?.[0].user?.name || 'Rattlehead' }}</p>
              </Link>
            </div>
          </Link>
        </article>
      </section>

      <!-- 6. Posts recientes de la comunidad -->
      <section v-if="recent?.length" class="community-posts | flow" data-type="first-batch">
        <div v-if="!user">
          <article class="cta | border-radius-2">
            <p> aren't you tired of all these posers praising the fake attitude and shit? no ones listening to the right stuff anymore, but YOU. </p>
            <a href="register" class="cta__register-btn | uppercase border-radius-1">
              
            </a>
          </article>
        </div>
        <div v-else>
          <div class="header-section">
            <h2 class="uppercase">community posts</h2>
          </div>
        </div>
        <article v-for="(post, index) in recent?.slice(0, 5)" :key="post.id" class="community-posts__wrapper">
          <Link style="text-decoration: none;" :href="route('post.show', post.slug)">
            <h3 class="community-posts__wrapper__post-title"> {{ post.title }} </h3>
            <div class="community-posts__wrapper__post-details">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="21.5" height="21.5" viewBox="0 0 21.5 21.5">
                  <path id="Vector" d="M20,10A10,10,0,1,1,10,0,10,10,0,0,1,20,10Z" transform="translate(0.75 0.75)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                  <path id="Vector-2" data-name="Vector" d="M4.08,7.67.98,5.82A2.215,2.215,0,0,1,0,4.1V0" transform="translate(10.38 6.26)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                </svg>
                
                <span>
                  {{ post?.short_date }}
                </span>
              </div>
              <Link class="author" :href="route('author.posts', post.user?.name)">
                {{ post.user?.name }}
              </Link>
            </div>
          </Link>
        </article>
      </section>

      <!-- 7. grupo de 3 "featured" -->
      <section v-if="featuredPosts?.length > 1" class="featured-post" data-type="last-three">
        <article v-for="(post, index) in featuredPosts?.slice(3, 6)" :key="post.id">
          <Link style="text-decoration: none;" :href="route('post.show', post.slug)">
            <picture>
                <source media="(min-width: 768px)" :srcset="post.thumbnail_urls?.max">
                <source media="(max-width: 767px)" :srcset="post.thumbnail_urls?.lg">
                <img 
                  :src="post.thumbnail_urls?.lg" 
                  :alt="post.title"
                  class="post-thumbnail"
                >
            </picture>
            <div class="post-content">
              <h2> {{ post.title }} </h2>
              <p class="extract margin-block-3">{{ post?.extract }}</p>
              <div class="post-info">
                <!-- upvotes -->
                <div class="post-info__upvotes">
                  <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="34" height="27" viewBox="0 0 34 35">
                    <path d="M24 6c-1.7 0-3 1.3-3 3v2.2c-.3-.1-.6-.2-1-.2-.8 0-1.5.3-2 .8-.5-.5-1.2-.8-2-.8-.4 0-.7.1-1 .2V7c0-1.7-1.3-3-3-3S9 5.3 9 7v10.9c-.5-.5-1.2-.9-2-.9-.9 0-1.7.3-2.3.9-1.1 1.1-1.2 3-.1 4.2l3.2 3.6C9.6 27.8 12.2 29 15 29h3.7c4.6 0 8.4-3.8 8.4-8.4V9C27 7.3 25.7 6 24 6zm1 10v4.6c0 3.5-2.9 6.4-6.4 6.4h-3.7c-2.2 0-4.2-.9-5.7-2.6L6 20.8c-.4-.4-.4-1.1.1-1.5.2-.2.5-.3.8-.3.3 0 .6.2.8.4l1.5 1.8c.3.3.7.4 1.1.3.4-.1.7-.5.7-.9V7c0-.6.4-1 1-1s1 .4 1 1v8c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1v-1c0-.6.4-1 1-1s1 .4 1 1v1c0 .6.4 1 1 1s1-.4 1-1V9c0-.6.4-1 1-1s1 .4 1 1v7z"/>
                  </svg>
                  <span>{{ post.likes_count }}</span>
                </div>
        
                <!-- comments -->
                <div class="post-info__comments">
                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 15 12">
                    <path id="Vector" d="M6.063,12.159a1.277,1.277,0,0,1-1.015-.541L4.2,10.49a.266.266,0,0,0-.113-.056H3.807C1.455,10.433,0,9.8,0,6.627V3.807C0,1.314,1.314,0,3.807,0H8.318c2.493,0,3.807,1.314,3.807,3.807v2.82c0,2.493-1.314,3.807-3.807,3.807H8.036a.147.147,0,0,0-.113.056l-.846,1.128A1.277,1.277,0,0,1,6.063,12.159ZM3.807.846C1.788.846.846,1.788.846,3.807v2.82c0,2.549.874,2.961,2.961,2.961h.282a1.058,1.058,0,0,1,.79.395l.846,1.128a.392.392,0,0,0,.677,0l.846-1.128a.988.988,0,0,1,.79-.395h.282c2.019,0,2.961-.942,2.961-2.961V3.807c0-2.019-.942-2.961-2.961-2.961Z" fill="#b4bddc"/>
                    <path id="Vector-2" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(5.499 4.935)" fill="#b4bddc"/>
                    <path id="Vector-3" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(7.755 4.935)" fill="#b4bddc"/>
                    <path id="Vector-4" data-name="Vector" d="M.564,1.128A.564.564,0,1,1,1.128.564.562.562,0,0,1,.564,1.128Z" transform="translate(3.243 4.935)" fill="#b4bddc"/>
                  </svg>
                  <span> </span>
                </div>
        
                <Link :href="route('author.posts', featuredPosts?.[0].user?.name)">
                  <p>{{ featuredPosts?.[0].user?.name || 'Rattlehead' }}</p>
                </Link>
              </div>
            </div>
          </Link>
        </article>
      </section>

      <!-- 8. ultimos 5 posts recientes de la comunidad -->
      <section v-if="recent?.length" class="community-posts | flow" data-type="second-batch">
        <article
          v-for="(post, index) in recent?.slice(5, 10)"
          :key="post.id"
          class="community-posts__wrapper"
        >
          <Link style="text-decoration: none;" :href="route('post.show', post.slug)">
            <h3 class="community-posts__wrapper__post-title">
                {{ post.title }}
            </h3>
            <div class="community-posts__wrapper__post-details">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="21.5" height="21.5" viewBox="0 0 21.5 21.5">
                  <path id="Vector" d="M20,10A10,10,0,1,1,10,0,10,10,0,0,1,20,10Z" transform="translate(0.75 0.75)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                  <path id="Vector-2" data-name="Vector" d="M4.08,7.67.98,5.82A2.215,2.215,0,0,1,0,4.1V0" transform="translate(10.38 6.26)" fill="none" stroke="#292d32" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"/>
                </svg>
                <span>{{ post?.short_date }}</span>
              </div>
              <Link class="author" :href="route('author.posts', post.user?.name)">
                {{ post.user?.name }}
              </Link>
            </div>
          </Link>
        </article>
      </section>
    </div>

  </SiteLayout>
</template>