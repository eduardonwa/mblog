<script setup lang="ts">
interface MARelease {
  albumUrl: string;
  genre: string;
  cover?: string;
  releaseTitle: string;
  band: string;
  type: string;
  releaseDate: string;
}

const props = defineProps<{
  albums: MARelease[];
}>();
</script>

<template>
    <section class="new-albums">
        <div class="new-albums-header">
            <h2 class="clr-accent-200 fw-semibold uppercase">new releases</h2>
            <p class="powered-string">by <a href="https://www.metal-archives.com" target="_blank" rel="noopener noreferrer" class="no-decor uppercase">metal-archives</a></p>
        </div>

        <ul class="new-albums__scroll-container">
            <li class="album" v-for="(album, index) in albums" :key="index">
                <a
                    :href="album.albumUrl"
                    rel="noopener noreferrer"
                    target="_blank"
                    class="album__info"
                >
                    <div class="album__header">
                        <div class="genres">
                            <p class="genre">{{ album.genre }}</p>
                            <hr>
                        </div>

                        <div class="album-cover" v-if="album.cover">
                            <img :src="album.cover" :alt="album.releaseTitle" />
                        </div>
                    </div>

                    <div class="album__content">
                        <p class="band">{{ album.band }}</p>
                        <p class="title">{{ album.releaseTitle }} ({{ album.type }})</p>
                        <p class="date">{{ album.releaseDate }}</p>
                    </div>
                </a>
            </li>
        </ul>
    </section>
</template>