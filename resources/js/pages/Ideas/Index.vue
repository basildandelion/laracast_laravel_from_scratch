<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import IdeaCard from '@/pages/Ideas/IdeaCard.vue';

interface Idea {
    id: number;
    title: string;
    description: string;
    created_at: string;
    status: string;
}

interface PaginatedIdeas {
    data: Idea[];
}

defineProps<{
    ideas: PaginatedIdeas;
}>();
</script>

<template>
    <Head title="your ideas" />
    <header class="py-8 md:py-12">
        <h1 class="text-3xl font-bold">Ideas</h1>
        <p class="mt-2 text-sm text-muted-foreground">
            Capture your thoughts. Make a plan.
        </p>

        <div class="mt-10">
            <div
                v-if="ideas.data.length"
                class="grid gap-6 text-muted-foreground md:grid-cols-2"
            >
                <IdeaCard
                    v-for="idea in ideas.data"
                    :idea="idea"
                    :key="idea.id"
                />
            </div>
            <div v-else>No ideas at this time. Create new ones!</div>
            <pre>

            {{ ideas.data }}
            </pre>
        </div>
    </header>
</template>
