<script setup lang="ts">
// import { Head, Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        canRegister: boolean;
        success?: string;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="MAIN PAGE" />
    <p>Hello World!</p>
</template>
