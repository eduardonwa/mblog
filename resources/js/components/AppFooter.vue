<script setup lang="ts">
import { computed } from 'vue';
import { Category } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import AppLogoIcon from './AppLogoIcon.vue';
import YoutubeIcon from './ui/icons/social-media/YoutubeIcon.vue';
import InstagramIcon from './ui/icons/social-media/InstagramIcon.vue';
import ThreadsIcon from './ui/icons/social-media/ThreadsIcon.vue';
import TikTokIcon from './ui/icons/social-media/TikTokIcon.vue';

const categories = computed<Category[]>(() => usePage().props.categories as Category[]);

</script>

<template>
    <footer class="site-footer">
        <section class="site-footer__container">
            <header class="site-footer__container__logo">
                <Link href="/">
                    <AppLogoIcon />
                </Link>
                <div class="icons-group">
                    <YoutubeIcon size="28" />

                    <InstagramIcon size="28" />

                    <ThreadsIcon size="28" />

                    <TikTokIcon size="28" />
                </div>
            </header>

            <article class="site-footer__container__groups">
                <div
                    class="footer-group"
                    v-if="categories?.length"
                >
                    <h2 class="uppercase">blog</h2>
                    <ul class="no-list-style">
                        <li
                            v-for="category in categories"
                            :key="category.id"
                        >
                            <Link :href="route('category.index', { slug: category.slug })">
                                {{ category.name }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <div class="footer-group">
                    <h2 class="uppercase">links</h2>
                    <ul class="no-list-style">
                        <li>
                            <a
                                href="https://www.invisibleoranges.com"
                                target="_blank"
                                rel="noopener noreferrer"
                            >invisibleoranges.com</a>
                        </li>
                        <li>
                            <a
                                href="https://metal-archives.com"
                                target="_blank"
                                rel="noopener noreferrer"
                            >metal-archives.com</a>
                        </li>
                        <li>
                            <a
                                href="https://metalinjection.net"
                                target="_blank"
                                rel="noopener noreferrer"
                            >metalinjection.net</a>
                        </li>
                        <li>
                            <a
                                href="https://www.angrymetalguy.com/"
                                target="_blank"
                                rel="noopener noreferrer"
                            >angrymetalguy.com</a>
                        </li>
                    </ul>
                </div>

                <div class="footer-group">
                    <h2 class="uppercase">support</h2>
                    <div>
                        <form
                            action="https://www.paypal.com/donate"
                            method="post"
                            target="_top"
                        >
                            <input
                                type="hidden"
                                name="hosted_button_id"
                                value="9VHZ8DVKJ6W24"
                            />
                            <input
                                type="image"
                                src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif"
                                border="0"
                                name="submit"
                                title="PayPal - The safer, easier way to pay online!"
                                alt="Donate with PayPal button"
                            />
                            <img
                                alt=""
                                border="0"
                                src="https://www.paypal.com/en_MX/i/scr/pixel.gif"
                                width="1"
                                height="1"
                            />
                        </form>
                    </div>
                </div>
            </article>
        </section>

        <span class="thank-you">Thanks for stopping by!</span>
    </footer>
</template>