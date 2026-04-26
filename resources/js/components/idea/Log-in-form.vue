<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { reactive } from 'vue';
import FormInput from '@/components/idea/FormInput.vue';

const props = defineProps<{
    action: string;
    errors: object;
}>();

const formData = reactive({
    email: '',
    password: '',
});

const handleSubmit = (event: Event) => {
    event.preventDefault();
    const form = useForm(formData);
    form.post(props.action);
};
</script>

<template>
    <div
        class="flex min-h-[calc(100dvh-4rem)] items-center justify-center px-4"
    >
        <div class="w-full max-w-md">
            <div class="text-center">
                <h1 class="text-3xl font-bold tracking-tight">
                    Log in to an account
                </h1>
                <p class="mt-1 text-muted-foreground">
                    Continue tracking your ideas today!
                </p>
            </div>
            <form
                :action="action"
                method="POST"
                class="mt-10 space-y-4"
                @submit="handleSubmit"
            >
                <FormInput
                    name="email"
                    label="E-Mail"
                    type="email"
                    placeholder=""
                    :errors="errors"
                    v-model="formData.email"
                />
                <FormInput
                    name="password"
                    label="Password"
                    type="password"
                    placeholder=""
                    :errors="errors"
                    v-model="formData.password"
                />
                <button type="submit" class="btn btn-primary mt-6 w-full h-10">
                    Log in
                </button>
            </form>
        </div>
    </div>
</template>
