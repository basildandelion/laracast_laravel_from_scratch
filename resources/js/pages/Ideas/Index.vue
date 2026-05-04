<script setup lang="ts">
import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/16/solid';
import { CheckIcon } from '@heroicons/vue/20/solid';
import { PhotoIcon } from '@heroicons/vue/24/solid';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import IdeaCard from '@/pages/Ideas/IdeaCard.vue';
import Modal from '@/pages/Ideas/Modal.vue';
import Pagination from '@/pages/Ideas/Pagination.vue';
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

const props = defineProps<{
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

const form = useForm({
    title: '',
    description: '',
    image_path: '',
    links: [],
    status: props.statuses[0],
});
const submitCreateForm = () => {
    form.post(ideas.store.url(), {
        preserveScroll: true,
        onSuccess: () => {
            closeCreateForm();
        },
    });
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
            <IdeaCard v-for="idea in items.data" :idea="idea" :key="idea.id" />
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
        <form id="ideaForm" @submit.prevent="submitCreateForm">
            <div class="space-y-12">
                <div
                    class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                >
                    <div class="col-span-full">
                        <label
                            for="username"
                            class="block text-sm/6 font-medium text-white"
                            >Title</label
                        >
                        <div class="mt-2">
                            <div
                                class="flex items-center rounded-md bg-white/5 pl-3 outline-1 -outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-primary"
                            >
                                <input
                                    id="username"
                                    type="text"
                                    name="username"
                                    v-model="form.title"
                                    placeholder="Brilliant name for your idea"
                                    class="block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 focus:outline-none sm:text-sm/6"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="col-span-full">
                        <label
                            for="about"
                            class="block text-sm/6 font-medium text-white"
                            >Description</label
                        >
                        <div class="mt-2">
                            <textarea
                                id="about"
                                name="about"
                                rows="3"
                                v-model="form.description"
                                class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-primary sm:text-sm/6"
                            ></textarea>
                        </div>
                        <p class="mt-3 text-sm/6 text-gray-400">
                            Write a description for your idea.
                        </p>
                    </div>
                    <div class="col-span-full">
                        <Listbox as="div" v-model="form.status">
                            <ListboxLabel
                                class="block text-sm/6 font-medium text-white"
                                >Status</ListboxLabel
                            >
                            <div class="relative mt-2">
                                <ListboxButton
                                    class="grid w-full cursor-default grid-cols-1 rounded-md bg-gray-800/50 py-1.5 pr-2 pl-3 text-left text-white outline-1 -outline-offset-1 outline-white/10 focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-indigo-500 sm:text-sm/6"
                                >
                                    <span
                                        class="col-start-1 row-start-1 flex items-center gap-3 pr-6"
                                    >
                                        <span class="block truncate">{{
                                            form.status
                                        }}</span>
                                    </span>
                                    <ChevronUpDownIcon
                                        class="col-start-1 row-start-1 size-5 self-center justify-self-end text-gray-400 sm:size-4"
                                        aria-hidden="true"
                                    />
                                </ListboxButton>

                                <transition
                                    leave-active-class="transition ease-in duration-100"
                                    leave-from-class=""
                                    leave-to-class="opacity-0"
                                >
                                    <ListboxOptions
                                        class="absolute z-10 mt-1 max-h-56 w-full overflow-auto rounded-md bg-gray-800 py-1 text-base outline-1 -outline-offset-1 outline-white/10 sm:text-sm"
                                    >
                                        <ListboxOption
                                            as="template"
                                            v-for="(status, index) in statuses"
                                            :key="index"
                                            :value="status"
                                            v-slot="{ active, selected }"
                                        >
                                            <li
                                                :class="[
                                                    active
                                                        ? 'bg-indigo-500 text-white outline-hidden'
                                                        : 'text-white',
                                                    'relative cursor-default py-2 pr-9 pl-3 select-none',
                                                ]"
                                            >
                                                <div class="flex items-center">
                                                    <span
                                                        :class="[
                                                            selected
                                                                ? 'font-semibold'
                                                                : 'font-normal',
                                                            'ml-3 block truncate',
                                                        ]"
                                                        >{{ status }}</span
                                                    >
                                                </div>

                                                <span
                                                    v-if="selected"
                                                    :class="[
                                                        active
                                                            ? 'text-white'
                                                            : 'text-indigo-400',
                                                        'absolute inset-y-0 right-0 flex items-center pr-4',
                                                    ]"
                                                >
                                                    <CheckIcon
                                                        class="size-5"
                                                        aria-hidden="true"
                                                    />
                                                </span>
                                            </li>
                                        </ListboxOption>
                                    </ListboxOptions>
                                </transition>
                            </div>
                        </Listbox>
                    </div>
                    <div class="col-span-full">
                        <label
                            for="cover-photo"
                            class="block text-sm/6 font-medium text-white"
                            >Image</label
                        >
                        <div
                            class="mt-2 flex justify-center rounded-lg border border-dashed border-white/25 px-6 py-10"
                        >
                            <div class="text-center">
                                <PhotoIcon
                                    class="mx-auto size-12 text-gray-600"
                                    aria-hidden="true"
                                />
                                <div class="mt-4 flex text-sm/6 text-gray-400">
                                    <label
                                        for="file-upload"
                                        class="relative cursor-pointer rounded-md bg-transparent font-semibold text-indigo-400 focus-within:outline-2 focus-within:outline-offset-2 focus-within:outline-indigo-500 hover:text-indigo-300"
                                    >
                                        <span>Upload a file</span>
                                        <input
                                            id="file-upload"
                                            name="file-upload"
                                            type="text"
                                            class="sr-only"
                                            v-model="form.image_path"
                                        />
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs/5 text-gray-400">
                                    PNG, JPG, GIF up to 10MB
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-full">
                        <label
                            for="about"
                            class="block text-sm/6 font-medium text-white"
                            >Links</label
                        >
                        <div class="mt-2">
                            <textarea
                                id="about"
                                name="about"
                                rows="3"
                                v-model="form.links"
                                class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-primary sm:text-sm/6"
                            ></textarea>
                        </div>
                        <p class="mt-3 text-sm/6 text-gray-400">
                            Each link should be on a new line.
                        </p>
                    </div>
                </div>
            </div>
        </form>

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
