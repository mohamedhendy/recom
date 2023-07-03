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
                        v-model="entityData.number"
                        :label="$t('number')"
                        :required="true"
                        :errors="$page.props.errors.number"
                        :disabled="$page.props.view_only"
                    />

                    <customInput
                        v-model="entityData.note"
                        :label="$t('comment')"
                        :required="false"
                        :errors="$page.props.errors.note"
                        :disabled="$page.props.view_only"
                    />
                </div>

                <div class="my-6">
                    <h3 class="mb-2 text-2xl text-gray-400 border-b">
                        {{ $t("address") }}
                    </h3>
                    <div class="grid grid-cols-3 gap-6">
                        <customInput
                            v-model="entityData.address_salutation"
                            :label="$t('address_salutation')"
                            :required="false"
                            :errors="$page.props.errors.address_salutation"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="entityData.address_name"
                            :label="$t('address_name')"
                            :required="false"
                            :errors="$page.props.errors.address_name"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="entityData.address_street"
                            :label="$t('address_street')"
                            :required="false"
                            :errors="$page.props.errors.address_street"
                            :disabled="$page.props.view_only"
                        />
                    </div>
                </div>

                <div class="my-6">
                    <div class="grid grid-cols-3 gap-6">
                        <customInput
                            v-model="entityData.address_city"
                            :label="$t('address_city')"
                            :required="false"
                            :errors="$page.props.errors.address_city"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="entityData.address_country"
                            :label="$t('address_country')"
                            :required="false"
                            :errors="$page.props.errors.address_country"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="entityData.address_zip_code"
                            :label="$t('address_zip_code')"
                            :required="false"
                            :errors="$page.props.errors.address_zip_code"
                            :disabled="$page.props.view_only"
                        />
                    </div>
                </div>

                <div class="my-6">
                    <h3 class="mb-2 text-2xl text-gray-400 border-b">
                        {{ $t("contact_info") }}
                    </h3>
                    <div class="grid grid-cols-3 gap-6">
                        <customInput
                            v-model="entityData.contact_email"
                            :label="$t('contact_details1_email')"
                            :required="false"
                            :errors="$page.props.errors.contact_email"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="entityData.contact_mobile"
                            :label="$t('contact_details1_mobile')"
                            :required="false"
                            :errors="$page.props.errors.contact_mobile"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="entityData.contact_phone1"
                            :label="$t('contact_details1_phone1')"
                            :required="false"
                            :errors="$page.props.errors.contact_phone1"
                            :disabled="$page.props.view_only"
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
            pageTitle: this.$t("create_customer"),
            entityData: {
                id: null,
                number: "",
                note: "",
                name: "",
                address_salutation: "",
                address_name: "",
                address_street: "",
                address_zip_code: "",
                address_city: "",
                address_country: "",
                type: "client",
                contact_phone1: "",
                contact_mobile: "",
                contact_email: "",
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
                this.$inertia.post("/api/customers", data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            } else {
                this.$inertia.patch("/api/customers/" + this.entityData.id, data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            }
        },
    },
};
</script>
