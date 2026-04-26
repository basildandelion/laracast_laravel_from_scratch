<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import RegisterForm from '@/components/idea/register-form.vue';

defineProps<{
    route: string;
    csrf: string;
    errors: object;
}>();
</script>

<template>
    <Head title="Register" />
    <register-form :action="route" :csrf="csrf" :errors="errors" />
</template>
