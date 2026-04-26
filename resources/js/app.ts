import { createInertiaApp } from '@inertiajs/vue3';
import { initializeTheme } from '@/composables/useAppearance';
import Layout from '@/layouts/layout.vue';
// import AppLayout from '@/layouts/AppLayout.vue';
// import SettingsLayout from '@/layouts/settings/Layout.vue';
import { initializeFlashToast } from '@/lib/flashToast';

const appName = import.meta.env.VITE_APP_NAME || 'IDEA';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    layout: (name) => {
        switch (true) {
            // case name.startsWith('public/'):
            //     return null;
            // case name.startsWith('auth/'):
            //     return AuthLayout;
            //     return null;
            // case name.startsWith('settings/'):
                // return [AppLayout, SettingsLayout];
                // return null;
            default:
                return Layout;
        }
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();

// This will listen for flash toast data from the server...
initializeFlashToast();
