<script setup lang="ts">
import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
} from '@headlessui/vue';
import { X } from 'lucide-vue-next';

defineProps<{
    open: boolean;
    title: string;
}>();

defineSlots<{
    default?: () => unknown;
    footer?: () => unknown;
}>();

const emit = defineEmits<{
    close: [];
}>();

const close = () => {
    emit('close');
};
</script>

<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="relative z-10" @close="close">
            <TransitionChild
                as="template"
                enter="ease-out duration-300"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="ease-in duration-200"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-gray-900/50 transition-opacity" />
            </TransitionChild>

            <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                <div
                    class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0"
                >
                    <TransitionChild
                        as="template"
                        enter="ease-out duration-300"
                        enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                        enter-to="opacity-100 translate-y-0 sm:scale-100"
                        leave="ease-in duration-200"
                        leave-from="opacity-100 translate-y-0 sm:scale-100"
                        leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    >
                        <DialogPanel
                            class="relative transform overflow-hidden rounded-lg bg-gray-800 text-left shadow-xl outline -outline-offset-1 outline-white/10 transition-all sm:my-8 sm:w-full sm:max-w-lg"
                        >
                            <div class="px-4 pt-5 pb-4 sm:p-6">
                                <div
                                    class="flex items-start justify-between gap-4"
                                >
                                    <DialogTitle
                                        as="h3"
                                        class="text-base font-semibold text-white"
                                    >
                                        {{ title }}
                                    </DialogTitle>

                                    <button
                                        type="button"
                                        class="rounded-md text-gray-400 transition hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-ring"
                                        @click="close"
                                    >
                                        <span class="sr-only">Close</span>
                                        <X class="size-5" aria-hidden="true" />
                                    </button>
                                </div>

                                <div class="mt-4 text-sm text-gray-300">
                                    <slot />
                                </div>
                            </div>

                            <div
                                v-if="$slots.footer"
                                class="flex justify-end gap-3 bg-gray-900/40 px-4 py-3 sm:px-6"
                            >
                                <slot name="footer" />
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
