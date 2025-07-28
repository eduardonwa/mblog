import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import type { SharedData } from '@/types';

export function useRolesPanel() {
    const page = usePage<SharedData>();
    const auth = page.props.auth;

    const isAdmin = computed(() => auth.roles?.includes('admin') ?? false);
    const isMember = computed(() => auth.roles?.includes('member') ?? false);

    function goTo(url: string) {
        window.location.href = url;
    }

    return {
        isAdmin,
        isMember,
        goTo,
    };
}