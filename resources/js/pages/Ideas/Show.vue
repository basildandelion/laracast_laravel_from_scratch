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
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import { PhotoIcon } from '@heroicons/vue/24/solid';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/pages/Ideas/Modal.vue';
import StatusLabel from '@/pages/Ideas/StatusLabel.vue';
import ideas from '@/routes/ideas';

interface Data {
    id: number;
    title: string;
    description: string;
    created_at: string;
    status: string;
    links: Array<string>;
    image_path: string;
}

interface Idea {
    data: Data;
}

const props = defineProps<{
    idea: Idea;
    statuses: string[];
}>();

const deleteIdea = (event: Event) => {
    event.preventDefault();

    if (confirm('Are you sure you want to delete this idea?')) {
        const form = useForm();
        form.delete(ideas.destroy.url(props.idea.data.id));
    }
};

const createModalOpened = ref(false);

const showCreateForm = () => {
    createModalOpened.value = true;
};

const closeCreateForm = () => {
    createModalOpened.value = false;
};
const form = useForm<{
    title: string;
    description: string;
    image_path: string;
    links: string;
    status: string;
    image: File | null;
}>({
    title: props.idea.data.title,
    description: props.idea.data.description,
    image_path: props.idea.data.image_path,
    links: props.idea.data.links.join('\n'),
    status: props.idea.data.status,
    image: null,
});
const submitCreateForm = () => {
    form.patch(ideas.update.url(props.idea.data.id), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            closeCreateForm();
        },
    });
};
const isDragging = ref(false);
const handleFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;

    if (!target.files || !target.files.length) {
        return;
    }

    handleFile(target.files[0]);
};

const handleDrop = (event: DragEvent) => {
    isDragging.value = false;

    if (!event.dataTransfer?.files.length) {
        return;
    }

    handleFile(event.dataTransfer.files[0]);
};
const handleDragLeave = (event: DragEvent) => {
    const currentTarget = event.currentTarget as HTMLElement;
    const relatedTarget = event.relatedTarget as Node | null;

    if (!currentTarget.contains(relatedTarget)) {
        isDragging.value = false;
    }
};
let previewUrl: string | null = null;

const MAX_FILE_SIZE = 10 * 1024 * 1024;

const handleFile = (file: File | null) => {
    if (!file) {
        return;
    }

    if (!file.type.startsWith('image/')) {
        alert('Only image files are allowed.');

        return;
    }

    if (file.size > MAX_FILE_SIZE) {
        alert('File size must be under 10MB.');

        return;
    }

    if (previewUrl) {
        URL.revokeObjectURL(previewUrl);
    }

    previewUrl = URL.createObjectURL(file);

    form.image = file;
    form.image_path = previewUrl;
};
</script>

<template>
    <Head :title="idea.data.title" />
    <div class="relative mt-2 overflow-hidden bg-gray-900 py-24 sm:py-32">
        <div class="absolute top-4 left-4">
            <Link
                :href="ideas.index()"
                class="flex items-center gap-x-2 text-gray-400 transition-colors hover:text-white"
            >
                <ArrowLeftIcon class="h-6 w-6" /> Back to ideas
            </Link>
        </div>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div
                :class="{
                    'mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2':
                        idea.data.image_path,
                    'mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-1':
                        !idea.data.image_path,
                }"
            >
                <div class="lg:pt-4 lg:pr-8">
                    <div :class="{ 'lg:max-w-lg': idea.data.image_path }">
                        <div class="mt-1">
                            <StatusLabel :status="idea.data.status" />
                        </div>
                        <h1
                            class="mt-2 text-4xl font-semibold tracking-tight text-pretty text-white sm:text-5xl"
                        >
                            {{ idea.data.title }}
                        </h1>
                        <p class="mt-6 text-lg/8 text-gray-300">
                            {{ idea.data.description }}
                        </p>
                        <div class="mt-4">
                            {{ idea.data.created_at }}
                        </div>
                        <div v-if="idea.data.links" class="my-6">
                            <ul class="flex flex-col gap-2">
                                <li v-for="link in idea.data.links" :key="link">
                                    <a
                                        :href="link"
                                        target="_blank"
                                        class="text-primary opacity-60 hover:opacity-100"
                                        >{{ link }}</a
                                    >
                                </li>
                            </ul>
                        </div>
                        <div class="mt-4 flex gap-x-5">
                            <button
                                class="btn"
                                type="button"
                                @click="showCreateForm"
                            >
                                Edit
                            </button>
                            <form @submit="deleteIdea">
                                <button
                                    type="submit"
                                    class="btn border border-red-900/80 bg-red-400"
                                >
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <img
                    v-if="idea.data.image_path"
                    width="2432"
                    height="1442"
                    :src="'/storage/' + idea.data.image_path"
                    :alt="idea.data.title"
                    class="w-3xl max-w-none rounded-xl shadow-xl ring-1 ring-white/10 sm:w-228 md:-ml-4 lg:ml-0"
                />
            </div>
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
                        <label class="block text-sm/6 font-medium text-white">
                            Image
                        </label>

                        <div
                            class="mt-2 flex justify-center rounded-lg border border-dashed border-white/25 bg-gray-900 bg-cover bg-center px-6 py-10 transition"
                            :class="{
                                'border-primary bg-primary/10': isDragging,
                                'bg-cover bg-center': form.image_path,
                                'bg-gray-900': !form.image_path,
                            }"
                            :style="
                                form.image_path
                                    ? {
                                          backgroundImage: `url(/storage/${form.image_path})`,
                                      }
                                    : {}
                            "
                            @dragenter.prevent="isDragging = true"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="handleDragLeave"
                            @drop.prevent="handleDrop"
                        >
                            <div
                                class="rounded-md bg-primary/90 p-6 text-center"
                            >
                                <PhotoIcon
                                    v-if="!form.image_path"
                                    class="mx-auto size-12 text-gray-300"
                                    aria-hidden="true"
                                />

                                <div
                                    class="mt-4 flex justify-center text-sm text-white"
                                >
                                    <label
                                        for="file-upload"
                                        class="cursor-pointer rounded-md font-semibold hover:text-indigo-300"
                                    >
                                        Upload a file
                                    </label>

                                    <input
                                        id="file-upload"
                                        type="file"
                                        accept="image/*"
                                        class="sr-only"
                                        @change="handleFileSelect"
                                    />

                                    <p class="pl-1">or drag and drop</p>
                                </div>

                                <p class="text-xs/5 text-white">
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
                        Update Idea
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
