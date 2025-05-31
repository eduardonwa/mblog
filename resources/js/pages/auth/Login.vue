<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthBase title="Log in to your account" description="Enter your email and password below to log in">
        <Head title="Log in" />

        <div v-if="status" class="status-message">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="login-form | container" data-type="blog-post">
            <div class="form-group flow">
                <Label for="email">Email address</Label>
                <Input
                    id="email"
                    type="email"
                    required
                    autofocus
                    :tabindex="1"
                    autocomplete="email"
                    v-model="form.email"
                    placeholder="email@example.com"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="form-group flow">
                <div>
                    <Label for="password">Password</Label>
                    <TextLink class="system-link | fs-300 margin-inline-2" v-if="canResetPassword" :href="route('password.request')" :tabindex="5">
                        Forgot password?
                    </TextLink>
                </div>
                <Input
                    id="password"
                    type="password"
                    required
                    :tabindex="2"
                    autocomplete="current-password"
                    v-model="form.password"
                    placeholder="Password"
                />
                <InputError :message="form.errors.password" />
            </div>

            <div class="checkbox | form-group flow" :tabindex="3">
                <Label for="remember">
                    <Checkbox id="remember" v-model:checked="form.remember" :tabindex="4" />
                    <span>Remember me</span>
                </Label>
            </div>

            <Button
                type="submit"
                class="button"
                data-type="accent"
                :tabindex="4"
                :disabled="form.processing"
                style="margin: auto; width: 100%;"
            >
                <LoaderCircle v-if="form.processing" class="spinning-loader" />
                Log in
            </Button>

            <div class="fs-300 text-center padding-4 margin-block-10">
                <span>Don't have an account?</span>
                <TextLink class="system-link | margin-inline-2" :href="route('register')" :tabindex="5">Sign up</TextLink>
            </div>
        </form>
    </AuthBase>
</template>