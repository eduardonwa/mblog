import { ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import axios from 'axios';

export function useRegisterForm() {
    const currentStep = ref(1);
    const totalSteps = 4;
    const usernameAvailable = ref<boolean | null>(null);
    const checkingUsername = ref(false);
    const usernameError = ref('');

    const form = useForm({
        username: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    // Validar nombre de usuario en tiempo real
    const checkUsernameAvailability = async () => {
        if (!form.username.trim()) {
            usernameAvailable.value = null;
            usernameError.value = '';
            return;
        }

        checkingUsername.value = true;
        usernameError.value = '';

        try {
            const { data } = await axios.post('/check-username', {
                username: form.username.trim()
            });
            usernameAvailable.value = data.available;
            usernameError.value = data.available ? '' : 'Username is already taken.';
        } catch (error) {
            usernameError.value = 'Error checking username availability';
            console.error('Username check failed:', error);
        } finally {
            checkingUsername.value = false;
        }
    };

    // Debounce para evitar muchas llamadas mientras el usuario escribe
    let debounceTimer: number;
    watch(() => form.username, () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            checkUsernameAvailability();
        }, 500);
    });

    const nextStep = () => {
        if (currentStep.value === 2 && !form.username) {
            usernameError.value = 'Username is required';
            return;
        }
        if (currentStep.value === 2 && usernameAvailable.value === false) {
            usernameError.value = 'Username is already taken';
            return;
        }
        if (currentStep.value === 3 && !form.email) {
            usernameError.value = 'Email is required';
            return;
        }
        if (currentStep.value < totalSteps) {
            currentStep.value++;
        }
    };

    const prevStep = () => {
        if (currentStep.value > 1) {
            currentStep.value--;
        }
    };

    return {
        currentStep,
        totalSteps,
        form,
        nextStep,
        prevStep,
        usernameAvailable,
        checkingUsername,
        usernameError,
        checkUsernameAvailability
    };
}