<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import StatusLabel from '@/pages/Ideas/StatusLabel.vue';
import ideas from '@/routes/ideas';

defineProps<{
    idea: any;
}>();
</script>

<template>
    <Head :title="idea.data.title" />
    <div class="overflow-hidden bg-gray-900 py-24 sm:py-32">
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
                            <li v-for="link in idea.data.links" :key="link.id">
                                <a :href="link.url" class="text-indigo-400">{{
                                    link.text
                                }}</a>
                            </li>
                        </ul>
                        <Link class="btn mt-4" :href="ideas.edit(idea.data.id)"> Edit </Link>
                    </div>
                </div>
                <img
                    v-if="idea.data.image_path"
                    width="2432"
                    height="1442"
                    :src="idea.data.image_path"
                    :alt="idea.data.title"
                    class="w-3xl max-w-none rounded-xl shadow-xl ring-1 ring-white/10 sm:w-228 md:-ml-4 lg:-ml-0"
                />
            </div>
        </div>
    </div>
</template>
