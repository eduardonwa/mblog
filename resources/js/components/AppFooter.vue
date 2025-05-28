<script setup lang="ts">
import { computed } from 'vue';
import AppLogoIcon from './AppLogoIcon.vue';
import { Category } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import YoutubeIcon from './ui/icons/social-media/YoutubeIcon.vue';
import InstagramIcon from './ui/icons/social-media/InstagramIcon.vue';
import ThreadsIcon from './ui/icons/social-media/ThreadsIcon.vue';
import TikTokIcon from './ui/icons/social-media/TikTokIcon.vue';

const categories = computed<Category[]>(() => usePage().props.categories as Category[]);

</script>

<template>
    <footer
        class="site-footer"
    >
        <section
            v-if="categories?.length"
            class="site-footer__container"
        >
            <header class="site-footer__container__logo">
                <Link href="/"><AppLogoIcon /></Link>
                <div class="icons-group">
                    <YoutubeIcon size="28" />

                    <InstagramIcon size="28" />

                    <ThreadsIcon size="28" />

                    <TikTokIcon size="28" />
                </div>
            </header>

            <article class="site-footer__container__groups">
                <div class="footer-group">
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
                            <Link href="https://metalstudies.org">metalstudies.org</Link>
                        </li>
                        <li>
                            <Link href="https://metal-archives.com">metal-archives.com</Link>
                        </li>
                        <li>
                            <Link href="https://metalinjection.net">metalinjection.net</Link>
                        </li>
                        <li>
                            <Link href="https://thisdayinmetal.com">thisdayinmetal.com</Link>
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

        <span class="thank-you">Thank you for your visit!</span>
    </footer>
</template>