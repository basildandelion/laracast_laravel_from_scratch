<script setup lang="ts">
import Navigation from '@/components/idea/navigation.vue';
import FlashCard from '@/pages/public/FlashCard.vue';
</script>

<template>
    <div class="min-h-screen bg-background text-foreground">
        <header>
            <Navigation />
        </header>
        <main class="mx-auto max-w-7xl px-6">
            <slot />
        </main>
        <FlashCard />
    </div>
</template>

<style scoped></style>
