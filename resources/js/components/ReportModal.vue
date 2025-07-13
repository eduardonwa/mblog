<script setup lang="ts">
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ReportIcon from './ui/icons/ReportIcon.vue';
import { ReportableEntity } from '@/types';

const props = defineProps<({
    reportable: ReportableEntity;
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
</script>

<template>
    <!-- report button -->
    <section class="report-icon-wrapper">
        <ReportIcon
            popovertarget="reportPopover"
            :disabled="form.processing"
        />
    </section>

    <!-- report form -->
    <section>
        <dialog popover id="reportPopover">
            <template v-if="success">
                <div class="report-success">
                    <h2 class="heading-4">Thank you.</h2>
                    <p>We'll get back to you if neccessary. Click outside or press ESC to close.</p>
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
                            <p v-if="frontendErrors.message" class="clr-error-100">
                                {{ frontendErrors.message }}
                            </p>
                        </div>
        
                        <button class="button" type="submit">Submit report</button>
                    </form>
                </article>
            </template>
        </dialog>
    </section>
</template>