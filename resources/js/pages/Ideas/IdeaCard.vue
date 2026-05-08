<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import StatusLabel from '@/pages/Ideas/StatusLabel.vue';
import ideas from '@/routes/ideas';

interface Idea {
    id: number;
    title: string;
    description: string;
    created_at: string;
    status: string;
    image_path: string;
}
defineProps<{
    idea: Idea;
}>();
</script>

<template>
    <Link
        :href="ideas.show(idea.id)"
        class="grid grid-cols-12 gap-6 rounded-lg border border-border bg-card p-4 md:text-sm"
    >
        <div
            :class="{
                'col col-span-8': idea.image_path,
                'col col-span-12': !idea.image_path,
            }"
        >
            <h3 class="text-lg text-foreground">
                {{ idea.title }}
            </h3>
            <div class="mt-1">
                <StatusLabel :status="idea.status" />
            </div>
            <div class="mt-5 line-clamp-3">
                {{ idea.description }}
            </div>
            <div class="mt-4">
                {{ idea.created_at }}
            </div>
        </div>
        <div class="col col-span-4" v-if="idea.image_path">
            <img
                :src="idea.image_path"
                alt="Idea Image"
                class="mt-4 h-40 w-full rounded-md object-cover"
            />
        </div>
    </Link>
</template>
