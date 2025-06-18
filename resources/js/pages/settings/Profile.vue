<script setup lang="ts">
import { TransitionRoot } from '@headlessui/vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import Avatar from '@/components/ui/avatar/Avatar.vue';
import DeleteUser from '@/components/DeleteUser.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type ProfileForm, type BreadcrumbItem, type SharedData, type User } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    className?: string;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const form = useForm<ProfileForm>({
    name: user.name,
    email: user.email,
    avatar: null,
    avatarPreview: String(page.props.avatarUrl || ''),
});

function handleAvatarChange(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files && input.files[0]) {
        form.avatar = input.files[0];
        form.avatarPreview = URL.createObjectURL(input.files[0]);
    }
}

const submit = () => {
    form.post(route('profile.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="card margin-block-end-9">
                <div>
                    <p class="card__heading | clr-primary-200">Profile information</p>
                    <p class="fs-300 padding-block-2">Update your name and email address</p>
                </div>

                <form class="profile-form | margin-block-start-4" @submit.prevent="submit">
                    <div class="form-group">
                        <label class="avatar-upload-label">
                            <input
                                type="file"
                                name="avatar"
                                id="avatar"
                                accept="image/*"
                                @change="handleAvatarChange"
                                class="hidden-input"
                                key="file-input"
                            />
                            <div class="avatar-container">
                                <Avatar
                                    :src="form.avatarPreview || $page.props.avatarUrl"
                                    :name="form.name"
                                    size="lg"
                                    class="avatar-image"
                                />
                                <div class="avatar-overlay">
                                    {{ form.avatarPreview ? 'Change' : 'Select' }}
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="form-group">
                        <Label for="name">Name</Label>
                        <Input id="name" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="form-group">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError :message="form.errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="fs-300 margin-block-end-4">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="button clr-accent-200"
                                data-type="ghost"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="flex-group" style="align-items: center;">
                        <Button class="button" data-type="secondary" :disabled="form.processing">Save
                            <TransitionRoot
                                :show="form.recentlySuccessful"
                                enter="transition ease-in-out"
                                enter-from="opacity-0"
                                leave="transition ease-in-out"
                                leave-to="opacity-0"
                            >
                                <div class="padding-inline-start-4 clr-accent-400">
                                    <svg viewBox="0 0 24 24" width="18" height="18">
                                        <path fill="currentColor" d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
                                    </svg>
                                </div>
                            </TransitionRoot>
                        </Button>
                    </div>
                </form>
            </div>

            <DeleteUser />
        </SettingsLayout>
    </AppLayout>
</template>