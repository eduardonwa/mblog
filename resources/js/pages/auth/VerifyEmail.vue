<script setup lang="ts">
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};
</script>

<template>
    <AuthLayout title="Verify email" description="Please verify your email address by clicking on the link we just emailed to you.">
        <Head title="Email verification" />

        <div v-if="status === 'verification-link-sent'" class="text-center">
            A new verification link has been sent to the email address you provided during registration.
        </div>

        <form @submit.prevent="submit" class="text-center container" data-type="narrow">
            <Button class="button margin-inline-end-4" :disabled="form.processing" variant="secondary">
                <LoaderCircle v-if="form.processing" class="" />
                Resend verification email
            </Button>

            <TextLink :href="route('logout')" method="post" as="button" class="button"> Log out </TextLink>
        </form>
    </AuthLayout>
</template>
