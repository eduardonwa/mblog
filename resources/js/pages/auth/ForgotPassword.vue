<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <AuthLayout title="Forgot password" description="Enter your email to receive a reset link">
        <Head title="Forgot password" />

        <div v-if="status" class="status-message">
            {{ status }}
        </div>

        <div class="container text-center" data-type="blog-post">
            <form @submit.prevent="submit" class="forgot-pw-form">
                <div class="form-group padding-block-start-4">
                    <Label for="email">Email address</Label>
                    <Input id="email" type="email" name="email" autocomplete="off" v-model="form.email" autofocus placeholder="email@example.com" />
                    <InputError :message="form.errors.email" />
                </div>

                <div class="margin-block-start-8">
                    <Button class="button" data-type="reset-password" :disabled="form.processing">
                        <LoaderCircle v-if="form.processing" />
                        Email password reset link
                    </Button>
                </div>
            </form>

            <div class="fs-300 text-center margin-block-start-4">
                <span>Or, return to</span>
                <TextLink class="system-link | margin-inline-2" :href="route('login')">log in</TextLink>
            </div>
        </div>
    </AuthLayout>
</template>
