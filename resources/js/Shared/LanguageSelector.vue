<template>
    <inertia-link :href="route('language', [selectable_locale()])" class="flex">
        <span class="flex flex-col items-center">
            <svg-vue class="page-content__sidebar-list-item-icon" icon="nav/lang"/>
            <span class="uppercase text-sm font-bold -mt-3" v-text="show_lang()"/>
        </span>
        <span class="page-content__sidebar-list-item-label">{{ $t("change_locale") }}</span>
    </inertia-link>
</template>

<script>
export default {
    methods: {
        show_lang() {
            return this.$page.props.locale;
        },
        selectable_locale() {
            if (this.$page.props.locale === 'en') {
                return 'de';
            }
            if (this.$page.props.locale === 'de') {
                return 'en';
            }
            return 'de';
        }
    }
};
</script>
