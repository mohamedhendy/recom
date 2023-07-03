<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ pageTitle }}
            </h2>
        </template>

        <template #screen-actions>
            <button
                v-if="!$page.props.view_only"
                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                type="button"
                @click="saveEntity"
            >
                <svg-vue icon="save" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("save") }}</span>
            </button>
        </template>
        <div class="screen__content">
            <div class="form">
                <div class="grid grid-cols-3 gap-6">
                    <customInput
                        v-model="entityData.name"
                        :label="$t('name')"
                        :required="true"
                        :errors="$page.props.errors.name"
                        :disabled="$page.props.view_only"
                    />

                    <div>
                        <label>{{ $t("parent_category") }}</label>
                        <select-with-search
                            :error-message="$page.props.errors.parent_category_id"
                            :has-error="$page.props.errors.parent_category_id"
                            :init-id="entityData.parent_category_id"
                            :items="$page.props.categories"
                            :placeholder="$t('parent_category')"
                            :label="(item) => `${item.name}`"
                            @item-changed="categoryChanged"
                        />
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

import ClosePageUsingEscMixin from "@/Mixins/ClosePageUsingEscMixin";
import SelectWithSearch from "@/Components/Reusable/SelectWithSearch";
import CustomInput from "@/Components/Reusable/CustomInput";

export default {
    components: {
        AppLayout,
        SelectWithSearch,
        CustomInput
    },
    mixins: [ClosePageUsingEscMixin],
    data() {
        return {
            closing: false,
            updatePage: false,
            pageTitle: this.$t("create_category"),
            entityData: {
                id: null,
                name: "",
                parent_category_id: null,
            },
        };
    },
    created() {
        if (this.$page.props.entity) {
            this.entityData = this.$page.props.entity;
            this.pageTitle = this.$t("update");
            this.updatePage = true;
        }
    },

    methods: {
        saveEntity() {
            this.$loading.show({delay: 0, background: "#444"});

            let data = this.entityData;

            if (!this.updatePage) {
                this.$inertia.post("/api/categories", data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            } else {
                this.$inertia.patch("/api/categories/" + this.entityData.id, data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            }
        },
        categoryChanged(event) {
            this.entityData.parent_category_id = event.item.id;
        },
    },
};
</script>
