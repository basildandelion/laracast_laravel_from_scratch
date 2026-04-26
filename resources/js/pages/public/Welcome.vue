<script setup lang="ts">
// import { Head, Link } from '@inertiajs/vue3';
// import { about, contacts, home } from '@/routes';
import { Head } from '@inertiajs/vue3';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="MAIN PAGE">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <p>Hello World!</p>
</template>
