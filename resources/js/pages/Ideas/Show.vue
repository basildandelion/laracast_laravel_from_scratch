<script setup lang="ts">
import { ArrowLeftIcon } from '@heroicons/vue/24/outline';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/pages/Ideas/Modal.vue';
import StatusLabel from '@/pages/Ideas/StatusLabel.vue';
import ideas from '@/routes/ideas';
import IdeaForm from './IdeaForm.vue';

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
const statusModalOpened = ref(false);
const showChangeStatusModal = () => {
    statusModalOpened.value = true;
};
const closeStatusForm = () => {
    statusModalOpened.value = false;
};
const changeStatus = (idea: Idea, status: string) => {
    const form = useForm<{
        status: string;
    }>({
        status,
    });
    form.submit(ideas.status(idea.data.id), {
        onSuccess: () => {
            statusModalOpened.value = false;
        },
    });
    statusModalOpened.value = false;
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
                            <StatusLabel
                                @click.prevent="showChangeStatusModal()"
                                :status="idea.data.status"
                            />
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
        v-if="createModalOpened"
        :open="createModalOpened"
        title="Create an Idea"
        @close="closeCreateForm"
    >
        <IdeaForm
            :idea="idea.data"
            :statuses="statuses"
            formId="ideaForm"
            @close="closeCreateForm"
        />

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

    <Modal
        :open="statusModalOpened"
        v-if="statusModalOpened"
        title="Change Idea Status"
        @close="closeStatusForm"
    >
        <div class="flex gap-x-2">
            <template v-for="(status, key) in statuses" :key="key">
                <StatusLabel
                    :status="status"
                    @click="changeStatus(idea, status)"
                />
            </template>
        </div>
        <template #footer>
            <div class="flex">
                <div class="gap-x-6">
                    <button
                        type="button"
                        class="btn btn-outlined"
                        @click="closeStatusForm"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </template>
    </Modal>
</template>
