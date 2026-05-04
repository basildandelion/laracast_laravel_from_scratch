<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, ref, watch } from 'vue';

interface Flash {
    id?: string | null;
    success?: string | null;
    error?: string | null;
    message?: string | null;
}

type FlashType = 'success' | 'error' | 'message';

interface PageProps {
    flash: Flash;
}

const page = usePage<PageProps>();
const visible = ref(false);
const activeFlash = ref<{ message: string; type: FlashType } | null>(null);
let timeout: ReturnType<typeof window.setTimeout> | null = null;

const currentFlash = computed(() => {
    const flash = page.props.flash;

    if (flash.error) {
        return { message: flash.error, type: 'error' as const };
    }

    if (flash.success) {
        return { message: flash.success, type: 'success' as const };
    }

    if (flash.message) {
        return { message: flash.message, type: 'message' as const };
    }

    return null;
});

const flashSignature = computed(() => {
    const flash = page.props.flash;

    return [
        flash.id,
        flash.error,
        flash.success,
        flash.message,
    ].join('|');
});

const flashClasses = computed(() => {
    if (activeFlash.value?.type === 'error') {
        return 'border-red-500/40 bg-red-950 text-red-100 shadow-red-950/20';
    }

    if (activeFlash.value?.type === 'success') {
        return 'border-emerald-500/40 bg-emerald-950 text-emerald-100 shadow-emerald-950/20';
    }

    return 'border-sky-500/40 bg-sky-950 text-sky-100 shadow-sky-950/20';
});

const hideFlash = () => {
    visible.value = false;
};

watch(
    flashSignature,
    () => {
        if (timeout) {
            window.clearTimeout(timeout);
        }

        if (!currentFlash.value) {
            hideFlash();

            return;
        }

        activeFlash.value = currentFlash.value;
        visible.value = true;
        timeout = window.setTimeout(hideFlash, 3000);
    },
    { immediate: true },
);

onBeforeUnmount(() => {
    if (timeout) {
        window.clearTimeout(timeout);
    }
});
</script>

<template>
    <div
        v-if="visible"
        class="fixed right-4 bottom-4 z-50 max-w-sm rounded-lg border px-4 py-3 text-sm font-medium shadow-lg"
        :class="flashClasses"
        role="status"
        aria-live="polite"
    >
        {{ activeFlash?.message }}
    </div>
</template>
