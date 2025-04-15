<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle } from 'lucide-vue-next';
import { ref, onMounted, watch } from 'vue';
import List from '@/components/ui/list/List.vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import AuthBase from '@/layouts/AuthLayout.vue';
import axios from 'axios';

// variables reactivas para el formulario de captcha
const captchaImage = ref<string>('');
const captchaAnswer = ref<string>('');
const captchaError = ref<string>('');
const showCaptcha = ref<boolean>(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = async () => {
    if (currentStep.value === 1) {
        const isValid = await validateCaptcha();
        if (isValid) currentStep.value++;
        return;
    }

    if (currentStep.value === totalSteps) {
        form.post(route('register'));
    } else {
        nextStep();
    }
};

// generar nuevo captcha
const generateNewCaptcha = async () => {
    captchaError.value = '';
    captchaAnswer.value = '';
    try {
        const response = await axios.get('/captcha/generate', {
            responseType: 'blob'
        });
        
        const reader = new FileReader();
        reader.onload = (e: ProgressEvent<FileReader>) => {
            if (e.target?.result) {
                captchaImage.value = e.target.result.toString();
            }
        };
        reader.readAsDataURL(response.data);
    } catch (error: unknown) {
        captchaError.value = 'Failed to load CAPTCHA. Please try again.';
        console.error('CAPTCHA error:', error);
    }
};

/* multistep form */
// estado para controlar el paso actual
const currentStep = ref(1);
const totalSteps = 4;

// mostrar captcha en el primer paso
onMounted(() => {
    generateNewCaptcha();
});

// funcion para avanzar al siguiente paso
const nextStep = () => {
  if (currentStep.value === 2 && !form.name) {
    alert('Need username');
    return;
  }
  if (currentStep.value === 3 && !form.email) {
    alert('Need email');
    return;
  }
  if (currentStep.value < totalSteps) {
    currentStep.value++;
  }
};

// Nueva función para validar el CAPTCHA
const validateCaptcha = async () => {
    captchaError.value = '';

    if (!captchaAnswer.value) {
        captchaError.value = 'You must identify the band';
        return false;
    }
    
    try {
        const { data } = await axios.post('/captcha/validate', {
            captcha_answer: captchaAnswer.value
        });
        
        if (!data.success) {
            captchaError.value = data.error || 'What!? Those are like the easiest albums wtf... *cough*poser*cough*';
            generateNewCaptcha();
            return false;
        }
        return true;

    } catch (error) {
        captchaError.value = "Server error. Please try again.";
        generateNewCaptcha();
        return false;
    }
};

// validación para enviar el captcha
watch(captchaError, (newVal) => {
    console.log('Error cambiado:', newVal);
});

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
                <!-- CAPTCHA verification -->
                <section v-show="currentStep === 1">
                    <div class="captcha-container">
                        <div class="text-center">
                            <h3>what band does this album belong to?</h3>
                            <img
                                :src="captchaImage"
                                alt="CAPTCHA Album Cover"
                                class="margin-block-4"
                            />
                        </div>

                        <div class="captcha-container__ui">
                            <Input
                                v-model="captchaAnswer"
                                type="text"
                                placeholder="Enter band name"
                                class="text-center"
                                :tabIndex="1"
                            />
                            
                            <Button
                                type="button"
                                @click="submit"
                                class="fw-base button mx-auto"
                                data-type="verify-captcha"
                                :disabled="!captchaAnswer.valueOf()"
                                :class="{ 'disabled': !captchaAnswer.valueOf() }"
                            >
                                Verify
                            </Button>
                            
                            <InputError 
                                :message="captchaError" 
                                class="clr-accent-100"
                                v-if="captchaError"
                            />
                            
                            <button
                                type="button"
                                @click="generateNewCaptcha"
                                class="button"
                                data-type="try-again"
                            >
                                ↻ Try another album?
                            </button>
                        </div>
                    </div>
                </section>

                <!-- usuario  -->
                <section v-show="currentStep === 2">
                    <div class="form-group flow">
                        <Label for="name">User name</Label>
                        <Input id="name" type="text" required autofocus :tabindex="1" autocomplete="name" v-model="form.name" placeholder="kill_the_kardashians_69" />
                        <InputError :message="form.errors.name" />
                    </div>
                </section>
    
                <!-- email  -->
                <section v-show="currentStep === 3">
                    <div class="form-group flow">
                        <Label for="email">Email address</Label>
                        <Input id="email" type="email" required :tabindex="2" autocomplete="email" v-model="form.email" placeholder="email@example.com" />
                        <InputError :message="form.errors.email" />
                    </div>
                </section>
    
                <!-- contraseña  -->
                <section v-show="currentStep === 4">
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
                        v-if="currentStep > 1 && currentStep !== 2"
                        type="button"
                        @click="prevStep"
                        class="fw-base button mx-auto"
                        data-type="form-step-prev"
                    >
                        Previous
                    </Button>
    
                    <Button
                        v-if="currentStep < totalSteps && currentStep !== 1"
                        type="button"
                        @click="nextStep"
                        class="fw-base button mx-auto"
                        data-type="form-step-next"
                        :disabled="currentStep === 1 && !captchaAnswer.valueOf()"
                    >
                        Next
                    </Button>
    
                    <Button
                        v-if="currentStep === totalSteps"
                        type="submit"
                        class="fw-base button mx-auto"
                        :disabled="form.processing"
                        data-type="form-login"
                        tabindex="5"
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