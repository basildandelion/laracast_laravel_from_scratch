<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import IdeaCard from '@/pages/Ideas/IdeaCard.vue';
import IdeaForm from '@/pages/Ideas/IdeaForm.vue';
import Modal from '@/pages/Ideas/Modal.vue';
import Pagination from '@/pages/Ideas/Pagination.vue';
import ideas from '@/routes/ideas';

interface Idea {
    id: number;
    title: string | null;
    description: string | null;
    created_at: string | null;
    status: string;
    image_path: string | null;
    links: string[];
}
const newIdea: Idea = {
    id: 0,
    title: null,
    description: null,
    created_at: null,
    status: 'pending',
    image_path: null,
    links: [],
};

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
interface Flash {
    id?: string | null;
    success?: string;
    error?: string;
    message?: string;
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
    statuses: string[];
    flash?: Flash;
}>();

const createModalOpened = ref(false);

const showCreateForm = () => {
    createModalOpened.value = true;
};

const closeCreateForm = () => {
    createModalOpened.value = false;
};
</script>

<template>
    <Head title="your ideas" />
    <header class="py-8 md:py-12">
        <h1 class="text-3xl font-bold">Ideas</h1>
        <p class="mt-2 text-sm text-muted-foreground">
            Capture your thoughts. Make a plan.
        </p>
    </header>

    <div class="flex items-center justify-between">
        <div class="flex gap-3">
            <Link
                v-for="status in Object.keys(counts)"
                :key="status"
                :test="status"
                :href="
                    ideas.index.url() +
                    (status !== 'all' ? `?status=${status}` : '')
                "
                :class="
                    `btn` + (status !== requestedStatus ? ' btn-outlined' : '')
                "
            >
                {{ counts[status].name }}: {{ counts[status].value }}
            </Link>
        </div>
        <button class="btn btn-outlined" type="button" @click="showCreateForm">
            Create an Idea
        </button>
    </div>

    <div class="mt-10">
        <div
            v-if="items.data.length"
            class="grid gap-6 text-muted-foreground md:grid-cols-2"
        >
            <IdeaCard v-for="(idea, key) in items.data" :idea="idea" :key="key" />
        </div>
        <div v-else>No ideas at this time. Create new ones!</div>

        <div v-if="items.meta.total > items.meta.per_page" class="mt-10">
            <pagination
                :meta="items.meta"
                :requested-status="requestedStatus"
                :links="items.links"
            />
        </div>
    </div>

    <Modal
        :open="createModalOpened"
        title="Create an Idea"
        @close="closeCreateForm"
    >
        <IdeaForm :idea="newIdea" :statuses="statuses" formId="ideaForm" />

        <template #footer>
            <div class="flex">
                <div class="gap-x-6">
                    <button type="submit" form="ideaForm" class="btn">
                        Create an Idea
                    </button>
                    <button
                        type="button"
                        class="btn btn-outlined"
                        @click="closeCreateForm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </template>
    </Modal>
</template>
