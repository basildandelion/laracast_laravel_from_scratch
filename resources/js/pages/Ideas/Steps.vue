<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import ideas from '@/routes/ideas';

interface Steps {
    id: number;
    idea_id: number;
    completed: boolean;
    description: string;
    created_at: string;
    updated_at: string;
}
const props = defineProps<{
    steps: Steps[];
}>();
const updateStepCompletion = (stepId: number) => {
    const step = props.steps.find((step) => step.id === stepId);

    if (step) {
        const form = useForm();
        const route = ideas.step.complete({ idea: step.idea_id, stepId });
        form.submit(route, {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <div class="mt-10 space-y-10" v-if="steps.length > 0">
        <fieldset>
            <legend class="text-sm/6 font-semibold text-white">To do</legend>
            <div class="flex gap-3" v-for="(step, index) in steps" :key="index">
                <div class="flex h-6 shrink-0 items-center">
                    <div class="group grid size-4 grid-cols-1">
                        <input
                            :id="'step[' + step.id + ']'"
                            :name="'idea[' + step.id + ']'"
                            :checked="step.completed"
                            type="checkbox"
                            class="col-start-1 row-start-1 appearance-none rounded-sm border border-white/10 bg-white/5 checked:border-indigo-500 checked:bg-indigo-500 indeterminate:border-indigo-500 indeterminate:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 disabled:border-white/5 disabled:bg-white/10 disabled:checked:bg-white/10 forced-colors:appearance-auto"
                            @change="
                                updateStepCompletion(
                                    step.id
                                )
                            "
                        />
                        <svg
                            class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-white/25"
                            viewBox="0 0 14 14"
                            fill="none"
                        >
                            <path
                                class="opacity-0 group-has-checked:opacity-100"
                                d="M3 8L6 11L11 3.5"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                class="opacity-0 group-has-indeterminate:opacity-100"
                                d="M3 7H11"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>
                </div>
                <div class="text-sm/6">
                    <label
                        :for="'step[' + step.id + ']'"
                        :class="{
                            'font-medium text-white': !step.completed,
                            'font-medium text-indigo-500 line-through':
                                step.completed,
                        }"
                        >{{ step.description }}</label
                    >
                </div>
            </div>
        </fieldset>
    </div>
</template>
