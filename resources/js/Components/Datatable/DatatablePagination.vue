<template>
    <div class="datatable__pagination">
        <div class="datatable__pagination-container">
            <button
                :class="[links.prev === null ? 'datatable__pagination-item__disabled':'datatable__pagination-item__enabled']"
                :disabled="links.prev === null" class="datatable__pagination-item "
                @click="updateActivePageUrl(links.prev)"
            >
                {{ $t("previous") }}
            </button>

            <!--            <inertia-link class="text-gray-900 hover:underline" href="#">-->
            <!--                1-->
            <!--            </inertia-link>-->

            <!--            <inertia-link class="text-gray-900 hover:underline" href="#">-->
            <!--                2-->
            <!--            </inertia-link>-->

            <!--            <inertia-link class="text-gray-900 hover:underline" href="#">-->
            <!--                3-->
            <!--            </inertia-link>-->

            <button
                :class="[links.next === null ? 'datatable__pagination-item__disabled':'datatable__pagination-item__enabled']"
                :disabled="links.next === null"
                class="datatable__pagination__item" @click="updateActivePageUrl(links.next)"
            >
                {{ $t("next") }}
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "DatatablePagination",
    props: {
        endpoint: {
            type: String,
            required: true
        },
        meta: {
            type: Object,
            required: true
        },
        links: {
            type: Object,
            required: true
        }
    },
    methods: {
        updateActivePageUrl(url) {
            this.$emit('changePage', {url: url});
        }
    }
};
</script>

<style scoped>

</style>
