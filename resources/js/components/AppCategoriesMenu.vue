<script setup lang="ts">
import SimpleDropdown from './ui/simple-dropdown/SimpleDropdown.vue';
import { reactive, watch } from 'vue'
import { Link } from '@inertiajs/vue3';
import UpArrow from './ui/icons/UpArrow.vue';
import DownArrow from './ui/icons/DownArrow.vue';

const props = defineProps<{
    categories: Array<any>
    variant?: 'sidebar' | 'navbar' | 'topbar'
}>()

const categories = props.categories

const openStates = reactive<Record<number, boolean>>({})

watch(
  () => categories,
  (newCategories) => {
    newCategories.forEach(cat => {
      if (openStates[cat.id] === undefined) {
        openStates[cat.id] = false;
      }
    });
  },
  { immediate: true }
);
</script>

<template>
    <div class="categories-menu" :class="`categories-menu--${variant}`">
        <SimpleDropdown
            v-for="category in categories"
            class="category"
            :key="category.id"
            :category-id="category.id"
            v-model="openStates[category.id]"
            v-click-away="() => (openStates[category.id] = false)"
        >
            <template #header="{ isOpen, toggle }">
                <div class="category__header">
                    <Link :href="route('category.index', { slug: category.slug })">
                        {{ category.name }}
                    </Link>
                    <span @click.stop="toggle">
                        <component :is="isOpen ? UpArrow : DownArrow" size="18" />
                    </span>
                </div>
            </template>

            <template #content>
                <ul class="category__submenu">
                    <li
                        v-for="child in category.children"
                        :key="child.id"
                        class="category__submenu-item"
                    >
                        <Link :href="route('category.index', { slug: child.slug })">
                            {{ child.name }}
                        </Link>
                    </li>
                </ul>
            </template>
        </SimpleDropdown>
    </div>
</template>
