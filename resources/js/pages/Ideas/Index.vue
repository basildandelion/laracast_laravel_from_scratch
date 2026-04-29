<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed } from 'vue';
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
    meta: {
        total: number;
        per_page: number;
        from: number;
        to: number;
        current_page: number;
        last_page: number;
        links: {
            url: string | null;
            label: string;
            active: boolean;
            page: number | null;
        }[];
    };
    links: {
        prev: string | null;
        next: string | null;
    };
}
interface Counts {
    [key: string]: {
        name: string;
        value: number;
    };
}

const props = defineProps<{
    items: PaginatedIdeas;
    counts: Counts;
    requestedStatus: string;
}>();

const pageLinks = computed(() => props.items.meta.links.slice(1, -1));
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

        <div v-if="items.meta.total > items.meta.per_page" class="mt-10">
            <div
                class="flex items-center justify-between border-t border-border py-3"
            >
                <div class="flex flex-1 justify-between sm:hidden">
                    <Link
                        v-if="items.links.prev"
                        :href="items.links.prev"
                        preserve-scroll
                        class="relative inline-flex items-center rounded-md border border-border bg-card px-4 py-2 text-sm font-medium text-foreground hover:bg-accent"
                    >
                        Previous
                    </Link>
                    <span
                        v-else
                        aria-disabled="true"
                        class="relative inline-flex items-center rounded-md border border-border bg-card px-4 py-2 text-sm font-medium text-muted-foreground opacity-50"
                    >
                        Previous
                    </span>
                    <Link
                        v-if="items.links.next"
                        :href="items.links.next"
                        preserve-scroll
                        class="relative ml-3 inline-flex items-center rounded-md border border-border bg-card px-4 py-2 text-sm font-medium text-foreground hover:bg-accent"
                    >
                        Next
                    </Link>
                    <span
                        v-else
                        aria-disabled="true"
                        class="relative ml-3 inline-flex items-center rounded-md border border-border bg-card px-4 py-2 text-sm font-medium text-muted-foreground opacity-50"
                    >
                        Next
                    </span>
                </div>
                <div
                    class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between"
                >
                    <div>
                        <p class="text-sm text-muted-foreground">
                            Showing
                            <span class="font-medium">{{
                                items.meta.from
                            }}</span>
                            to
                            <span class="font-medium">{{ items.meta.to }}</span>
                            of
                            <span class="font-medium">
                                {{ items.meta.total }}
                            </span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav
                            aria-label="Pagination"
                            class="isolate inline-flex -space-x-px rounded-md"
                        >
                            <Link
                                v-if="items.links.prev"
                                :href="
                                    items.links.prev +
                                    (requestedStatus
                                        ? `&status=` + requestedStatus
                                        : '')
                                "
                                preserve-scroll
                                class="relative inline-flex items-center rounded-l-md px-2 py-2 text-muted-foreground inset-ring inset-ring-border hover:bg-accent focus:z-20 focus:outline-offset-0"
                            >
                                <span class="sr-only">Previous</span>
                                <ChevronLeft class="size-5" />
                            </Link>
                            <span
                                v-else
                                aria-disabled="true"
                                class="relative inline-flex items-center rounded-l-md px-2 py-2 text-muted-foreground opacity-50 inset-ring inset-ring-border"
                            >
                                <span class="sr-only">Previous</span>
                                <ChevronLeft class="size-5" />
                            </span>
                            <template
                                v-for="(link, index) in pageLinks"
                                :key="`${link.label}-${link.page}-${index}`"
                            >
                                <Link
                                    v-if="link.url"
                                    :href="
                                        link.url +
                                        (requestedStatus
                                            ? `&status=` + requestedStatus
                                            : '')
                                    "
                                    preserve-scroll
                                    :aria-current="
                                        link.active ? 'page' : undefined
                                    "
                                    :class="[
                                        'relative inline-flex items-center px-4 py-2 text-sm font-semibold inset-ring inset-ring-border focus:z-20 focus:outline-offset-0',
                                        link.active
                                            ? 'z-10 bg-primary text-primary-foreground focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring'
                                            : 'text-foreground hover:bg-accent',
                                    ]"
                                >
                                    {{ link.label }}
                                </Link>
                                <span
                                    v-else
                                    aria-disabled="true"
                                    class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-muted-foreground inset-ring inset-ring-border"
                                >
                                    {{ link.label }}
                                </span>
                            </template>
                            <Link
                                v-if="items.links.next"
                                :href="
                                    items.links.next +
                                    (requestedStatus
                                        ? `&status=` + requestedStatus
                                        : '')
                                "
                                preserve-scroll
                                class="relative inline-flex items-center rounded-r-md px-2 py-2 text-muted-foreground inset-ring inset-ring-border hover:bg-accent focus:z-20 focus:outline-offset-0"
                            >
                                <span class="sr-only">Next</span>
                                <ChevronRight class="size-5" />
                            </Link>
                            <span
                                v-else
                                aria-disabled="true"
                                class="relative inline-flex items-center rounded-r-md px-2 py-2 text-muted-foreground opacity-50 inset-ring inset-ring-border"
                            >
                                <span class="sr-only">Next</span>
                                <ChevronRight class="size-5" />
                            </span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
