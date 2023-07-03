<template>
    <inertia-link :href="url">
        <svg-vue icon="circle" class="w-8 h-8 fill-current" :style="{'color':iconColor}"/>
    </inertia-link>
</template>

<script>
export default {
    name: "ArticleStatusIcon",
    props: {
        color: {
            type: String,
            // required: true
        },
        url: {
            type: String,
            // required: true
        }
    },
    computed: {
        iconColor() {
            if (this.color === 'green')
                return "#22B14C";
            if (this.color === 'yellow')
                return "#FFF200";
            return "#ED1C24";
        }
    }
};
</script>

<style scoped>

</style>

