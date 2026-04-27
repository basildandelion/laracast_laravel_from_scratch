<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import StatusLabel from '@/pages/Ideas/StatusLabel.vue';
import ideas from '@/routes/ideas';

interface Idea {
    id: number;
    title: string;
    description: string;
    created_at: string;
    status: string;
}
defineProps<{
    idea: Idea;
}>();
</script>

<template>
    <Link
        :href="ideas.show(idea.id)"
        class="block rounded-lg border border-border bg-card p-4 md:text-sm"
    >
        <h3 class="text-lg text-foreground">
            {{ idea.title }}
        </h3>
        <div class="mt-1">
            <StatusLabel :status="idea.status" />
        </div>
        <div class="mt-5 line-clamp-3">
            {{ idea.description }}
        </div>
        <div class="mt-4">
            {{ idea.created_at }}
        </div>
    </Link>
</template>
