<script setup lang="ts">
// import { Head, Link } from '@inertiajs/vue3';
import { Head, usePage } from '@inertiajs/vue3';
import FlashCard from '@/pages/public/FlashCard.vue';

const page = usePage();

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
    <Head title="MAIN PAGE">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <p>Hello World!</p>
    <FlashCard v-if="page.props.success" :message="page.props.success" />
</template>
