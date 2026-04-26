<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { home } from '@/routes';
</script>

<template>
    <nav class="border-b border-border px-6">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between">
            <div>
                <Link :href="home()">IDEA</Link>
            </div>

            <div class="flex items-center gap-6">
                <Link href="/login">Sign in</Link>
                <Link class="btn" href="/register">Register</Link>
<!--                TODO named routes-->
            </div>
        </div>
    </nav>
</template>

<style scoped></style>
