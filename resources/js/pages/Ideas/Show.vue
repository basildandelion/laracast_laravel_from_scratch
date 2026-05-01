<script setup lang="ts">
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import { Head, Link, useForm } from '@inertiajs/vue3';
import StatusLabel from '@/pages/Ideas/StatusLabel.vue';
import ideas from '@/routes/ideas';

interface Data {
    id: number;
    title: string;
    description: string;
    created_at: string;
    status: string;
    links: Array<{ text: string; url: string }>;
    image_path: string;
}

interface Idea {
    data: Data;
}

const props = defineProps<{
    idea: Idea;
}>();

const deleteIdea = (event: Event) => {
    event.preventDefault();

    if (confirm('Are you sure you want to delete this idea?')) {
        const form = useForm();
        form.delete(ideas.destroy.url(props.idea.data.id));
    }
};
</script>

<template>
    <Head :title="idea.data.title" />
    <div class="relative mt-2 overflow-hidden bg-gray-900 py-24 sm:py-32">
        <div class="absolute top-4 left-4">
            <Link
                :href="ideas.index()"
                class="flex items-center text-gray-400 transition-colors hover:text-white gap-x-2"
            >
                <ArrowLeftIcon class="h-6 w-6" /> Back to ideas
            </Link>
        </div>
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div
                class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 sm:gap-y-20 lg:mx-0 lg:max-w-none lg:grid-cols-2"
            >
                <div class="lg:pt-4 lg:pr-8">
                    <div class="lg:max-w-lg">
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
                        <ul v-if="idea.data.links">
                            <li
                                v-for="(link, index) in idea.data.links"
                                :key="index"
                            >
                                <a :href="link.url" class="text-indigo-400">{{
                                    link.text
                                }}</a>
                            </li>
                        </ul>
                        <div class="mt-4 flex gap-x-5">
                            <Link class="btn" :href="ideas.edit(idea.data.id)">
                                Edit
                            </Link>
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
                    :src="idea.data.image_path"
                    :alt="idea.data.title"
                    class="w-3xl max-w-none rounded-xl shadow-xl ring-1 ring-white/10 sm:w-228 md:-ml-4 lg:ml-0"
                />
            </div>
        </div>
    </div>
</template>
