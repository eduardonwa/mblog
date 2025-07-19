<script setup lang="ts">
import { ref } from 'vue';
import { ReportableEntity } from '@/types';
import { useForm } from '@inertiajs/vue3';
import ReportIcon from './ui/icons/ReportIcon.vue';

defineOptions({ inheritAttrs: false });

const props = defineProps<({
    reportable: ReportableEntity;
    popoverId: string;
    showText?: boolean;
})>();

const reasons = {
    'sexual': 'Sexual content',
    'hate': 'Hate speech or incitement',
    'abuse': 'Abusive or hateful content',
    'harassment': 'Harassment or bullying',
    'misleading': 'Misleading information',
    'other': 'Other',
};

const form = useForm({
    reason: null,
    message: '',
    reportable_id: props.reportable.id,
    reportable_type: props.reportable.type,
});

const success = ref(false);
const frontendErrors = ref<{ reason?: string; message?: string }>({});

function submit() {
    // usuario no especifica una razón
    if (!form.reason) {
        frontendErrors.value.reason = 'Please specify a reason for this report.';
        return;
    }

    // usuario selecciona "other" sin comentario adicional
    if (form.reason === 'other' && !form.message.trim()) {
        frontendErrors.value.message = 'Please add a comment to the report if your choice is "other".';
        return;
    }

    // todo bien.
    form.post('/report', {
        onSuccess: () => {
            success.value = true;
        },
        onError: (errors) => {
            //
        }
    });
}

// Declarar el ref para el dialog y tiparlo explícitamente
const dialogRef = ref<HTMLDialogElement | null>(null);

// Función para abrir el popover/dialog
function openPopover() {
    const dialog = dialogRef.value;

    if (!dialog) {
        console.warn(`No se encontró el dialog con id ${props.popoverId}`);
        return;
    }

    if ('showPopover' in dialog) {
        (dialog as any).showPopover();
    } else if ('showModal' in dialog) {
        (dialog as HTMLDialogElement).showModal();
    } else {
        console.warn("Your browser doesn't support showPopover or showModal. Please send us an email to: admin@sickofmetal.net to review your concerns.");
    }
}
</script>

<template>
    <!-- report button -->
    <div v-bind="$attrs" style="display:flex;">
        <ReportIcon
            @click="openPopover"
            :disabled="form.processing"
            :showText="showText"
        />
    </div>

    <!-- report form -->
    <section>
        <dialog class="report-popover" ref="dialogRef" popover :id="popoverId">
            <template v-if="success">
                <div class="report-success">
                    <h2 class="heading-4">Thank you.</h2>
                    <p>We'll get back to you if neccessary. Click/tap outside or press ESC to close.</p>
                </div>
            </template>
    
            <template v-else>
                <article class="popover-report-card">
                    <form @submit.prevent="submit" class="form-report-card">
                        <h2 class="header">What's wrong?</h2>
                        
                        <div v-for="(label, key) in reasons" :key="key" class="reasons-container">
                            <label class="reasons-label">
                                <input
                                    type="radio"
                                    :value="key"
                                    v-model="form.reason"
                                    class="custom-radio"
                                />
                                    {{ label }}
                            </label>
                        </div>
        
                        <p v-if="frontendErrors.reason" class="clr-error-100">
                            {{ frontendErrors.reason }}
                        </p>
        
                        <div v-if="form.reason === 'other'">
                            <textarea
                                v-model="form.message"
                                placeholder="Additional comment"
                                class="textarea"
                            />
                            <p v-if="frontendErrors.message" class="clr-error-100 fs-300 padding-block-2">
                                {{ frontendErrors.message }}
                            </p>
                        </div>
        
                        <button class="button" type="submit">Submit report</button>
                    </form>
                    <p style="font-size: 14px;" class="margin-inline-start-6 padding-block-end-4 clr-neutral-300">Click/tap outside to close</p>
                </article>
            </template>
        </dialog>
    </section>
</template>