<script setup lang="ts">
import { onMounted, ref } from 'vue';

const props = defineProps<{
    message: any;
}>();

const isVisible = ref(true);

onMounted(() => {
    setTimeout(() => {
        isVisible.value = false;
    }, 2500);
});
</script>

<template>
    <div
        v-if="isVisible"
        class="absolute right-4 bottom-4 rounded-lg bg-primary px-4 py-3"
    >
        {{ props.message }}
    </div>
</template>
