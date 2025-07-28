<script setup lang="ts">
import SimpleDropdown from './ui/simple-dropdown/SimpleDropdown.vue';
import { reactive, watch } from 'vue'
import { Link } from '@inertiajs/vue3';
import UpArrow from './ui/icons/UpArrow.vue';
import DownArrow from './ui/icons/DownArrow.vue';
import SimpleDropdownSubmenuItem from './ui/simple-dropdown/SimpleDropdownSubmenuItem.vue';

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
    <div class="categories-menu" :class="`dropdown--${variant}`">
        <SimpleDropdown
            v-for="category in categories"
            :key="category.id"
            :category-id="category.id"
            v-model="openStates[category.id]"
            v-click-away="() => (openStates[category.id] = false)"
        >
            <template #header="{ isOpen, toggle }">
                <Link :href="route('category.index', { slug: category.slug })">
                    {{ category.name }}
                </Link>
                <span @click.stop="toggle" v-if="category.children && category.children.length > 0">
                    <component :is="isOpen ? UpArrow : DownArrow" size="18" />
                </span>
            </template>

            <template #content v-if="category.children && category.children.length > 0">
                <SimpleDropdownSubmenuItem
                    v-for="child in category.children"
                    :key="child.id"
                    :href="route('category.index', { slug: child.slug })"
                    :text="child.name"
                />
            </template>
        </SimpleDropdown>
    </div>
</template>
