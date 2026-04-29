<script setup lang="ts">
import { ChevronDownIcon } from '@heroicons/vue/16/solid';
import { PhotoIcon, UserCircleIcon } from '@heroicons/vue/24/solid';
import { Head, useForm } from '@inertiajs/vue3';

interface Data {
    title: string;
    description: string;
    image_path: string;
    links: Array<{ text: string; url: string }>;
    status: string;
}

const props = defineProps<{
    idea: {
        data: Data;
    };
}>();

const form = useForm({
    title: props.idea.data.title,
    description: props.idea.data.description,
    image_path: props.idea.data.image_path,
    links: props.idea.data.links,
    status: props.idea.data.status,
});
</script>

<template>
    <Head :title="`Edit ` + idea.data.title" />
    <form>
        <div class="space-y-12">
            <div class="border-b border-white/10 pb-12">
                <h2 class="text-base/7 font-semibold text-white">
                    Edit an idea
                </h2>

                <div
                    class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6"
                >
                    <div class="sm:col-span-3">
                        <label
                            for="title"
                            class="block text-sm/6 font-medium text-white"
                            >Title</label
                        >
                        <div class="mt-2">
                            <div
                                class="flex items-center rounded-md bg-white/5 pl-3 outline-1 -outline-offset-1 outline-white/10 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-500"
                            >
                                <input
                                    type="text"
                                    name="title"
                                    id="title"
                                    class="block min-w-0 grow bg-transparent py-1.5 pr-3 pl-1 text-base text-white placeholder:text-gray-500 focus:outline-none sm:text-sm/6"
                                    placeholder="Title"
                                    v-model="form.title"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label
                            for="country"
                            class="block text-sm/6 font-medium text-white"
                            >Status</label
                        >
                        <div class="mt-2 grid grid-cols-1">
                            <select
                                id="country"
                                name="country"
                                autocomplete="country-name"
                                class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white/5 py-1.5 pr-8 pl-3 text-base text-white outline-1 -outline-offset-1 outline-white/10 *:bg-gray-800 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                            >
                                <option>United States</option>
                                <option>Canada</option>
                                <option>Mexico</option>
                            </select>
                            <ChevronDownIcon
                                class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-400 sm:size-4"
                                aria-hidden="true"
                            />
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
                                name="description"
                                id="description"
                                rows="3"
                                class="block w-full rounded-md bg-white/5 px-3 py-1.5 text-base text-white outline-1 -outline-offset-1 outline-white/10 placeholder:text-gray-500 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-500 sm:text-sm/6"
                                v-model="form.description"
                            >
                            </textarea>
                        </div>
                        <p class="mt-3 text-sm/6 text-gray-400">
                            Write a few sentences about your idea.
                        </p>
                    </div>

                    <div class="col-span-full">
                        <label
                            for="cover-photo"
                            class="block text-sm/6 font-medium text-white"
                            >Cover photo</label
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
                                            type="file"
                                            class="sr-only"
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
                </div>
            </div>

        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button type="button" class="btn btn-outlined">
                Cancel
            </button>
            <button
                type="submit"
                class="btn"
            >
                Save
            </button>
        </div>
    </form>
    {{ idea.data }}
</template>
