<script setup lang="ts">
import {
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from '@headlessui/vue';
import { ChevronUpDownIcon } from '@heroicons/vue/16/solid';
import {
    CheckIcon,
    PhotoIcon,
    TrashIcon,
    PlusIcon,
} from '@heroicons/vue/20/solid';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ideas from '@/routes/ideas';

interface Idea {
    id: number | null;
    title: string | null;
    description: string | null;
    created_at: string | null;
    status: string | null;
    links: Array<string>;
    image_path: string | null;
}

const props = defineProps<{
    idea: Idea;
    statuses: string[];
    formId: string;
}>();

const emit = defineEmits<{
    close: [];
}>();

const form = useForm<{
    title: string | null;
    description: string | null;
    image_path: string | null;
    links: Array<string>;
    status: string | null;
    image: File | null;
}>({
    title: props.idea.title ?? '',
    description: props.idea.description ?? '',
    image_path: props.idea.image_path ?? '',
    links: props.idea.links ?? [],
    status: props.idea.status,
    image: null,
});

const submitIdeaForm = () => {
    const route = props.idea.id ? ideas.update(props.idea.id) : ideas.store();
    form.submit(route.method, route.url, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            emit('close');
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
const getImageUrl = () => {
    if (!form.image_path) {
        return '';
    }

    if (form.image_path.startsWith('blob:')) {
        return form.image_path;
    }

    return `/storage/${form.image_path}`;
};
</script>

<template>
    <form :id="formId" @submit.prevent="submitIdeaForm">
        <div class="space-y-12">
            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
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
                        <p
                            class="text-xs text-red-500"
                            v-if="form.errors.title"
                        >
                            {{ form.errors.title }}
                        </p>
                    </div>
                </div>
                <div class="col-span-full">
                    <label
                        for="description"
                        class="block text-sm/6 font-medium text-white"
                        >Description</label
                    >
                    <div class="mt-2">
                        <textarea
                            id="description"
                            name="description"
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
                            >Status
                        </ListboxLabel>
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
                                      backgroundImage: `url(${getImageUrl()})`,
                                  }
                                : {}
                        "
                        @dragenter.prevent="isDragging = true"
                        @dragover.prevent="isDragging = true"
                        @dragleave.prevent="handleDragLeave"
                        @drop.prevent="handleDrop"
                    >
                        <div class="rounded-md bg-primary/90 p-6 text-center">
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
                        for="links"
                        class="block text-sm/6 font-medium text-white"
                        >Links</label
                    >
                    <div class="mt-2 flex flex-col gap-2">
                        <div
                            v-for="(link, index) in form.links"
                            :key="index"
                            class="flex flex-wrap items-center rounded-md bg-white/5 pl-3 outline-1 -outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-primary"
                        >
                            <p
                                class="text-xs text-red-500 w-full"
                                v-if="form.errors['links.' + index]"
                            >
                                {{ form.errors['links.' + index] }}
                            </p>
                            <input
                                type="text"
                                class="block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 focus:outline-none sm:text-sm/6"
                                :value="link"
                                @input="form.links[index] = $event.target.value"
                            />
                            <button
                                type="button"
                                @click="form.links.splice(index, 1)"
                            >
                                <TrashIcon class="size-5" aria-hidden="true" />
                            </button>
                        </div>
                        <button
                            class="btn flex justify-center"
                            type="button"
                            @click="form.links.push('')"
                        >
                            <PlusIcon class="size-5" aria-hidden="true" />
                            Add Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>
