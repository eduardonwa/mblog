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
import axios, { AxiosError } from 'axios';

// variables reactivas para el formulario de captcha
const captchaImage = ref<string>('');
const captchaAnswer = ref<string>('');
const captchaError = ref<string>('');
const isLoading = ref(false);
const captchaSuccess = ref(false);

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
    isLoading.value = true;

    try {
        const cacheBuster = Date.now();
        const { data } = await axios.get(`/captcha/generate?_=${cacheBuster}`, {
            responseType: 'blob',
            timeout: 3000 // Timeout de 3 segundos
        });

        // libera memoria de la imagen anterior
        if (captchaImage.value) {
            URL.revokeObjectURL(captchaImage.value);
        }
        // crea un nuevo objeto URL para la nueva imagen
        captchaImage.value = URL.createObjectURL(data);
        
    } catch (error) {
        captchaError.value = 'Error loading challenge';
        console.error('CAPTCHA load failed:', error);
    } finally {
        isLoading.value = false;
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

const validateCaptcha = async (): Promise<boolean> => {
    // Validación básica del frontend
    // muestra mensjae si el campo fue enviado vacío
    captchaError.value = '';
    captchaSuccess.value = false;

    if (!captchaAnswer.value?.trim()) {
        captchaError.value = 'You must enter the band\'s name, unless you\'re a bot or a poser.';
        return false;
    }

    try {
        const { data } = await axios.post('/captcha/validate', {
            captcha_answer: captchaAnswer.value.trim()
        });

        if (data.success) {
            captchaSuccess.value = true;
            await new Promise(resolve => setTimeout(resolve, 1500));
            return true;
        } else {
            captchaError.value = data.error;
            await generateNewCaptcha();
            return false;
        }
    } catch (error) {
        captchaError.value = axios.isAxiosError(error) 
            ? error.response?.data?.error || 'Server error'
            : 'Verification failed';
        
        await generateNewCaptcha();
        return false;
    }
};

// Helper para errores
const getErrorMessage = (error: unknown): string => {
    if (axios.isAxiosError(error)) {
        const data = error.response?.data;
        return data?.error 
            || data?.message
            || 'Server error. Please try again.';
    }
    return 'Verification failed. Please reload and try again.';
};

// validación para enviar el captcha
watch(captchaError, (newVal) => {
    if (newVal) {
        console.warn('CAPTCHA ERROR:', newVal);
    }
});

// función para retroceder al paso anterior
const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

const handleImageLoad = () => {
    console.log('CAPTCHA image loaded successfully');
};
</script>

<template>
    <AuthBase title="Create an account" description="Join a thriving community of opiniated metalheads">
        <Head title="Register" />
        
        <section class="even-columns container padding-inline-4 margin-block-start-10">
            <div>
                <List class="flow">
                    <div class="margin-block-end-6">
                        <h2 class="ff-accent clr-accent-300">Write rants that draw blood</h2>
                        <ul>
                            <li style="list-style-type: none;">Publish unfiltered reviews, rants and manifestos.</li>
                        </ul>
                    </div>
    
                    <div class="margin-block-end-6">
                        <h2 class="ff-accent clr-accent-300">Forget algorithms, follow your gut</h2>
                        <ul>
                            <li style="list-style-type: none;">Keep up with the topics that interest you by following groups.</li>
                        </ul>
                    </div>
    
                    <div class="margin-block-end-6">
                        <h2 class="ff-accent clr-accent-300">Smash the "Hail" button</h2>
                        <ul>
                            <li style="list-style-type: none;">Approve posts with a 🤘 instead of a lame "like."</li>
                        </ul>
                    </div>
                    <h2>...and <span class="italic">MORE!</span></h2>
                </List>
                <div class="fs-300 text-center padding-4 margin-block-2">
                    <span>Already have an account?</span>
                    <TextLink class="system-link | margin-inline-2" :href="route('login')" :tabindex="6">
                        Log in
                    </TextLink>
                </div>
            </div>

            <form @submit.prevent="submit" class="register-form | container">
                <!-- CAPTCHA verification -->
                <section v-show="currentStep === 1">
                    <div class="captcha-container">
                        <div class="captcha-container__image | text-center">
                            <h3>What band does this album belong to?</h3>
                            <img
                                v-if="captchaImage"
                                :src="captchaImage"
                                alt="CAPTCHA Album Cover"
                                class="margin-block-4"
                                @load="handleImageLoad"
                            />

                            <!-- spinner -->
                            <transition name="fade">
                                <div v-if="isLoading" class="spinner">
                                    <LoaderCircle class="spinner-circle" />
                                    <p>Poser Detector 5000 is working...</p>
                                </div>
                            </transition>

                            <transition name="fade">
                                <div v-if="captchaSuccess && currentStep === 1" class="success-message">
                                    ✓ Verified! Proceeding...
                                </div>
                            </transition>
                        </div>

                        <div class="captcha-container__ui">
                            <!-- mensaje de error -->
                            <div v-if="captchaError" class="captcha-error">
                                {{ captchaError }}
                            </div>
                            
                            <Input
                                v-model="captchaAnswer"
                                type="text"
                                placeholder="Enter band name"
                                class="text-center"
                                :tabIndex="1"
                            />
                            
                            <!-- verify btn -->
                            <Button
                                type="button"
                                @click="submit"
                                class="fw-base button mx-auto"
                                data-type="accent"
                                :disabled="!captchaAnswer.valueOf()"
                                :class="{ 'disabled': !captchaAnswer.valueOf() }"
                            >
                                Verify
                            </Button>
                            
                            <!-- retry challenge -->
                            <span @click="generateNewCaptcha" class="retry-captcha">
                                ↻ Try another album?
                            </span>
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
                        data-type="accent"
                        tabindex="5"
                    >
                        <LoaderCircle v-if="form.processing" class="spinning-loader" />
                        Create account
                    </Button>
                </section>
            </form>
        </section>

    </AuthBase>
</template>