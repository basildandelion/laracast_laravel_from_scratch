<script setup lang="ts">
import Navigation from '@/components/idea/navigation.vue';
</script>

<template>
    <div class="min-h-screen bg-background text-foreground">
        <header>
            <Navigation />
        </header>
        <main class="mx-auto max-w-7xl px-6">
            <slot />
        </main>
    </div>
</template>

<style scoped></style>
