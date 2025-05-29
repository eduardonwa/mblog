<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const { post } = defineProps({
  post: Object,
});

const form = useForm({
  comment: '',
});

const submitComment = () => {
  form.post(route('posts.comments.store', { post: post }), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
    onError: () => {
      if (form.errors.comment) {
        document.getElementById('comment')?.focus();
      }
    }
  });
}
</script>

<template>
    <form class="blog-post__comments__form | flow" @submit.prevent="submitComment">
        <label for="comments" class="uppercase clr-secondary-100">comments</label>
        <textarea
            v-model="form.comment"
            id="comment"
            name="comment"
            rows="4"
            placeholder="What's on your mind?"
            class="textarea"
            :class="{'is-invalid': form.errors.comment }"
        ></textarea>

        <div v-if="form.errors.comment">{{ form.errors.comment }}</div>

        <button type="submit" class="button" data-type="accent">
            <span
            v-if="form.processing"
            :disabled="form.processing"
            >Posting...</span>
            <span v-else>post comment</span>
        </button>
    </form>
</template>