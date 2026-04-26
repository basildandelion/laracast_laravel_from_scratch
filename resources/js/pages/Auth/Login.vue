<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import LogInForm from '@/components/idea/Log-in-form.vue';

defineProps<{
    route: string;
    csrf: string;
    errors: object;
}>();
</script>

<template>
    <Head title="Log in" />
    <log-in-form :action="route" :errors="errors" />
</template>
