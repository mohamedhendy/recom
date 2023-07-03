<template>
    <button v-if="camBack" class="back-button" type="button" @click="back()">
        <svg-vue class="w-5 h-5" icon="back"/>
        <span class="text-sm">{{ $t("back") }}</span>
    </button>
</template>

<script>
export default {
    props: {
        camBack: {
            type: Boolean,
            default: () => window.history.length > 2
        }
    },
    methods: {
        back() {
            window.history.back();
        },
    }
}
</script>
