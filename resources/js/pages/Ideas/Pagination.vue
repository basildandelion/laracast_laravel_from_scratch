<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { computed } from 'vue';

interface Links {
    prev?: string | null;
    next?: string | null;
    first?: string | null;
    last?: string | null;
}
interface Meta {
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
}
const props = defineProps<{
    links: Links;
    meta: Meta;
    requestedStatus: string;
}>();

const pageLinks = computed(() => props.meta.links.slice(1, -1));
</script>

<template>
    <div class="flex items-center justify-between border-t border-border py-3">
        <div class="flex flex-1 justify-between sm:hidden">
            <Link
                v-if="links.prev"
                :href="links.prev"
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
                v-if="links.next"
                :href="links.next"
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
                    <span class="font-medium">{{ meta.from }}</span>
                    to
                    <span class="font-medium">{{ meta.to }}</span>
                    of
                    <span class="font-medium">
                        {{ meta.total }}
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
                        v-if="links.prev"
                        :href="
                            links.prev +
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
                            :aria-current="link.active ? 'page' : undefined"
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
                        v-if="links.next"
                        :href="
                            links.next +
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
</template>
