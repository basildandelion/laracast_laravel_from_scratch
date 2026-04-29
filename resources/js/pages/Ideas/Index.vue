<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import IdeaCard from '@/pages/Ideas/IdeaCard.vue';
import ideas from '@/routes/ideas';

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
interface Counts {
    [key: string]: {
        name: string;
        value: number;
    };
}

defineProps<{
    items: PaginatedIdeas;
    counts: Counts;
    requestedStatus: string;
}>();
</script>

<template>
    <Head title="your ideas" />
    <header class="py-8 md:py-12">
        <h1 class="text-3xl font-bold">Ideas</h1>
        <p class="mt-2 text-sm text-muted-foreground">
            Capture your thoughts. Make a plan.
        </p>
    </header>

    <div class="flex gap-3">
        <Link
            v-for="status in Object.keys(counts)"
            :key="status"
            :test="status"
            :href="
                ideas.index.url() +
                (status !== 'all' ? `?status=${status}` : '')
            "
            :class="`btn` + (status !== requestedStatus ? ' btn-outlined' : '')"
        >
            {{ counts[status].name }}: {{ counts[status].value }}
        </Link>
    </div>

    <div class="mt-10">
        <div
            v-if="items.data.length"
            class="grid gap-6 text-muted-foreground md:grid-cols-2"
        >
            <IdeaCard v-for="idea in items.data" :idea="idea" :key="idea.id" />
        </div>
        <div v-else>No ideas at this time. Create new ones!</div>
        <pre>
        {{ items.data }}
        </pre>
    </div>
</template>
