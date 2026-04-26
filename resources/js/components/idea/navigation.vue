<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { home, fuckofffromthesite, register, authorization } from '@/routes';

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);

const handleSubmit = (event: Event) => {
    event.preventDefault();
    const form = useForm();
    form.post(fuckofffromthesite()['url']);
};
</script>

<template>
    <nav class="border-b border-border px-6">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between">
            <div>
                <Link :href="home()">
                    <img
                        src="/images/logo.svg"
                        alt="IDEA LOGO"
                        class="h-8 w-auto"
                    />
                </Link>
            </div>

            <div v-if="!isAuthenticated" class="flex items-center gap-6">
                <Link :href="authorization()">Sign in</Link>
                <!--                TODO named routes-->
                <Link class="btn" :href="register()">Register</Link>
            </div>
            <div v-else>
                <form
                    @submit="handleSubmit"
                    :action="fuckofffromthesite()['url']"
                    method="POST"
                >
                    <button class="btn" type="submit">Logout</button>
                </form>
            </div>
        </div>
    </nav>
</template>

<style scoped></style>
