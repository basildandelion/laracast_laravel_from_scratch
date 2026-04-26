<script setup lang="ts">
defineProps<{
    name: string;
    type: string;
    errors: any;
    label: string;
    placeholder: string;
    modelValue: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();
</script>

<template>

    <div class="space-y-2">
        <label :for="name" class="label">{{ label }}</label>
        <div>
            <input
                class="input"
                :type="type"
                :name="name"
                :placeholder="placeholder"
                :id="name"
                :value="modelValue"
                @input="
                emit('update:modelValue', ($event.target as HTMLInputElement).value)
            "
            />
            <div v-if="errors[name]" class="text-red-400">{{ errors[name] }}</div>
        </div>
    </div>
</template>
