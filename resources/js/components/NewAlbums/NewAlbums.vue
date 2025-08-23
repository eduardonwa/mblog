<script setup lang="ts">
import type { MbzAlbum } from '@/types';

defineProps<{
  albums: MbzAlbum[]; // 👈 igual que arriba
}>();
</script>

<template>
  <section class="new-albums">
    <div class="new-albums-header">
      <h2 class="clr-accent-200 fw-semibold uppercase">new releases</h2>
      <p class="powered-string">
        by <a href="https://musicbrainz.org" target="_blank" rel="noopener noreferrer" class="no-decor uppercase">MusicBrainz</a>
      </p>
    </div>

    <ul class="new-albums__scroll-container">
      <li class="album" v-for="album in albums" :key="album.rgid">
        <a :href="`https://musicbrainz.org/release-group/${album.rgid}`" target="_blank" rel="noopener noreferrer" class="album__info">
          <div class="album__header">
            <div class="genres">
                <p class="genre">{{ album.genre }}</p>
              <hr>
            </div>
            <div class="album-cover" v-if="album.cover_url">
              <img :src="album.cover_url" :alt="album.title" />
            </div>
          </div>
          <div class="album__content">
            <p class="band">{{ album.band }}</p>
            <p class="title">{{ album.title }} ({{ album.type }})</p>
            <p class="date">{{ album.date_text ?? album.date }}</p>
          </div>
        </a>
      </li>
    </ul>
  </section>
</template>
