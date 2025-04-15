<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import List from '@/components/ui/list/List.vue';
import AuthBase from '@/layouts/AuthLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

/* multistep form */
// estado para controlar el paso actual
const currentStep = ref(1);
const totalSteps = 3;

// funcion para avanzar al siguiente paso
const nextStep = () => {
    // validación básica del paso actual antes de avanzar
    if (currentStep.value === 1 && (!form.name)) {
        alert('Just pick a username. Doesn\'t have to be the most original shit ever.');
        return;
    }
    if (currentStep.value === 2 && (!form.email)) {
        alert('Please fill out the email field. Hmkay?');
        return;
    }
    if (currentStep.value < totalSteps) {
        currentStep.value++;
    }
};

// función para retroceder al paso anterior
const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};
</script>

<template>
    <AuthBase title="Create an account" description="Join a thriving community of opiniated metalheads">
        <Head title="Register" />
        
        <section class="even-columns container padding-inline-4 margin-block-start-12">
            <List class="flow">
                <div class="margin-block-end-6">
                    <h2 class="ff-accent clr-extra-300">Write rants that draw blood</h2>
                    <ul>
                        <li style="list-style-type: none;">Publish unfiltered reviews, rants and manifestos.</li>
                    </ul>
                </div>

                <div class="margin-block-end-6">
                    <h2 class="ff-accent clr-extra-300">Ride with the Doom Cult</h2>
                    <ul>
                        <li style="list-style-type: none;">Join a group: <em>"Thrash Purists", </em> <em>"Doom Cultists"</em>, etc. </li>
                    </ul>
                </div>

                <div class="margin-block-end-6">
                    <h2 class="ff-accent clr-extra-300">Smash the "Hail" button</h2>
                    <ul>
                        <li style="list-style-type: none;">Approve posts with a 🤘 instead of a lame "like."</li>
                    </ul>
                </div>
            </List>

            <form @submit.prevent="submit" class="register-form | container">
                <section v-show="currentStep === 1">
                    <div class="form-group flow">
                        <Label for="name">User name</Label>
                        <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="kill_the_kardashians_69" />
                        <InputError :message="form.errors.name" />
                    </div>
                </section>
    
                <section v-show="currentStep === 2">
                    <div class="form-group flow">
                        <Label for="email">Email address</Label>
                        <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" />
                        <InputError :message="form.errors.email" />
                    </div>
                </section>
    
                <section v-show="currentStep === 3">
                    <div class="form-group flow">
                        <Label for="password">Password</Label>
                        <Input
                            id="password"
                            type="password"
                            required
                            :tabindex="3"
                            autocomplete="new-password"
                            v-model="form.password"
                            placeholder="Password"
                        />
                        <InputError :message="form.errors.password" />
                    </div>
        
                    <div class="form-group flow">
                        <Label for="password_confirmation">Confirm password</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            required
                            :tabindex="4"
                            autocomplete="new-password"
                            v-model="form.password_confirmation"
                            placeholder="Confirm password"
                        />
                        <InputError :message="form.errors.password_confirmation" />
                    </div>
                </section>
    
                <!-- controles de navegación -->
                <section class="flex-group mx-auto">
                    <Button
                        v-if="currentStep > 1"
                        type="button"
                        @click="prevStep"
                        class="fw-base button mx-auto"
                        data-type="form-step-prev"
                    >
                        Previous
                    </Button>
    
                    <Button
                        v-if="currentStep < totalSteps"
                        type="button"
                        @click="nextStep"
                        class="fw-base button mx-auto"
                        data-type="form-step-next"
                    >
                        Next
                    </Button>
    
                    <Button
                        v-if="currentStep === totalSteps"
                        type="submit"
                        class="fw-base button mx-auto"
                        tabindex="5" :disabled="form.processing"
                        data-type="form-login"
                    >
                        <LoaderCircle v-if="form.processing" class="spinning-loader" />
                        Create account
                    </Button>
                </section>
                <div style="font-size: 14px;" class="text-center padding-4 margin-block-10">
                    Already have an account? <br>
                    <TextLink class="clr-extra-400" :href="route('login')" :tabindex="6">
                        Log in
                    </TextLink>
                </div>
            </form>
        </section>

    </AuthBase>
</template>
