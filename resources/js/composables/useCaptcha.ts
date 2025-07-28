import { onMounted, ref } from 'vue';
import axios, { AxiosError } from 'axios';

export function useCaptcha() {
    const captchaImage = ref<string>('');
    const captchaAnswer = ref<string>('');
    const captchaError = ref<string>('');
    const isLoading = ref(false);
    const captchaSuccess = ref(false);

    const generateNewCaptcha = async () => {
        isLoading.value = true;
        captchaError.value = '';
        
        try {
            const cacheBuster = Date.now();
            const { data } = await axios.get(`/captcha/generate?_=${cacheBuster}`, {
                responseType: 'blob',
                timeout: 3000
            });

            // liberar memoria de la imagen anterior
            if (captchaImage.value) {
                URL.revokeObjectURL(captchaImage.value);
            }

            // crear nueva url para la imagen
            captchaImage.value = URL.createObjectURL(data);
            
        } catch (error) {
            captchaError.value = 'Error loading challenge';
            console.error('CAPTCHA load failed:', error);

            // intenta regenerar despues de un breve retraso
            setTimeout(() => {
                generateNewCaptcha();
            }, 2000);

        } finally {
            isLoading.value = false;
        }
    };

    const validateCaptcha = async (): Promise<boolean> => {
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

    const handleImageLoad = () => {
        console.log('CAPTCHA image loaded successfully');
    };

    onMounted(() => {
        generateNewCaptcha();
    });

    return {
        captchaImage,
        captchaAnswer,
        captchaError,
        isLoading,
        captchaSuccess,
        generateNewCaptcha,
        validateCaptcha,
        handleImageLoad
    };
}