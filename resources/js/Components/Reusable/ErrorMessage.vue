<template>
    <div
        v-if="error"
        class="mt-1 text-red-500"
    >
        {{ error }}
    </div>
</template>

<script>
export default {
    props: {
        error: {
            required: true,
        },
    },
}
</script>
