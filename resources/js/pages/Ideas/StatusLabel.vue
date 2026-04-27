<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
    status: String,
});

const statusClasses = computed(() => {
    switch (props.status?.toLowerCase()) {
        case 'pending':
            return 'bg-yellow-500/10 text-yellow-500 border-yellow-500/20';
        case 'completed':
            return 'bg-green-500/10 text-green-500 border-green-500/20';
        case 'in progress':
            return 'bg-blue-500/10 text-blue-500 border-blue-500/20';
        default:
            return 'bg-gray-500/10 text-gray-500 border-gray-500/20';
    }
});
</script>

<template>
    <span
        :class="[
            'tex-xs inline-block rounded-full border px-2 py-1 font-medium',
            statusClasses,
        ]"
    >
        {{ status }}
    </span>
</template>
