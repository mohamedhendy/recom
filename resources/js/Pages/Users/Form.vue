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
                <h3 class="mb-2 text-2xl text-gray-400 border-b">
                    {{ $t("basic_info") }}
                </h3>
                <div class="grid grid-cols-3 gap-6">
                    <customInput
                        v-model="entityData.name"
                        :label="$t('name')"
                        :required="true"
                        :errors="$page.props.errors.name"
                        :disabled="$page.props.view_only"
                    />

                    <customInput
                        v-model="entityData.email"
                        :label="$t('email')"
                        :required="true"
                        :errors="$page.props.errors.email"
                        :disabled="$page.props.view_only"
                    />

                    <customInput
                        v-model="entityData.role"
                        :label="$t('role')"
                        :required="true"
                        :errors="$page.props.errors.role"
                        :disabled="$page.props.view_only"
                    />
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

import ClosePageUsingEscMixin from "@/Mixins/ClosePageUsingEscMixin";
import CustomInput from "@/Components/Reusable/CustomInput";

export default {
    components: {
        AppLayout,
        CustomInput
    },
    mixins: [ClosePageUsingEscMixin],
    data() {
        return {
            closing: false,
            updatePage: false,
            pageTitle: this.$t("create_user"),
            entityData: {
                id: null,
                created_at: null,
                name: "",
                email: "",
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
                this.$inertia.post("/api/users", data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            } else {
                this.$inertia.patch("/api/users/" + this.entityData.id, data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            }
        },
    },
};
</script>
