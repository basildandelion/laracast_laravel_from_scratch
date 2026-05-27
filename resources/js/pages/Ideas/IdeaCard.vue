<script setup lang="ts">
import { TrashIcon, CogIcon } from '@heroicons/vue/20/solid';
import { Link, useForm } from '@inertiajs/vue3';
import StatusLabel from '@/pages/Ideas/StatusLabel.vue';
import ideas from '@/routes/ideas';

interface Idea {
    id: number;
    title: string | null;
    description: string | null;
    created_at: string | null;
    status: string;
    image_path: string | null;
    links: string[] | null;
}
defineProps<{
    idea: Idea;
}>();
const emit = defineEmits<{
    editItem: [idea: Idea];
}>();

const showCreateForm = (idea: Idea) => {
    emit('editItem', idea);
    console.log('Show create form for idea:', idea);
};
const deleteIdea = (ideaId: number) => {
    if (confirm('Are you sure you want to delete this idea?')) {
        const form = useForm();
        const route = ideas.destroy(ideaId);
        form.submit(route);
    }
};
</script>

<template>
    <Link
        :href="ideas.show(idea.id)"
        class="relative grid grid-cols-12 gap-6 rounded-lg border border-border bg-card p-4 md:text-sm"
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
        <div class="absolute top-0 right-0 flex gap-x-1">
            <button
                class="aspect-square h-fit rounded-sm bg-primary p-1 text-foreground"
                type="button"
                @click.prevent="showCreateForm(idea)"
            >
                <CogIcon class="size-4" aria-hidden="true" />
            </button>
            <form>
                <button
                    @click.prevent="deleteIdea(idea.id)"
                    type="button"
                    class="aspect-square h-fit rounded-sm bg-red-500 p-1 text-foreground"
                >
                    <TrashIcon class="size-4" aria-hidden="true" />
                </button>
            </form>
        </div>
    </Link>
</template>
