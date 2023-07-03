<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <h2 class="text-2xl font-semibold text-gray-900">
                {{ $t('error') }}
            </h2>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';

export default {
    components: {
        AppLayout,
    },
};
</script>
