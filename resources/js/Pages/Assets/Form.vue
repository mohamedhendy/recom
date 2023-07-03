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
                class="flex items-center px-4 py-2  space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                type="button"
                @click="saveAsset"
            >
                <svg-vue icon="save" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("save") }}</span>
            </button>
        </template>


        <div class="screen__content">
            <div class="form">
                <div class="grid grid-cols-3 gap-6">
                    <customInput
                        v-model="assetData.a_number"
                        :label="$t('a_number')"
                        :required="false"
                        :errors="$page.props.errors.a_number"
                        :disabled="$page.props.view_only"
                    />

                    <customInput
                        v-model="assetData.serial_number"
                        :label="$t('serial_number')"
                        :required="false"
                        :errors="$page.props.errors.serial_number"
                        :disabled="$page.props.view_only"
                    />
                </div>
                <div class="grid grid-cols-3 gap-6">
                    <customInput
                        v-model="assetData.description"
                        :label="$t('description')"
                        :required="false"
                        :errors="$page.props.errors.description"
                        :disabled="$page.props.view_only"
                    />

                    <div>
                        <label>{{ $t("product") }}</label>
                        <select-with-search
                            :error-message="$page.props.errors.product_id"
                            :has-error="$page.props.errors.product_id"
                            :init-id="assetData.product_id"
                            :items="$page.props.products"
                            :placeholder="$t('products')"
                            :label="(item) => `${item.name}`"
                            @item-changed="productChanged"
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
import SelectWithSearch from "@/Components/Reusable/SelectWithSearch";
import ResponseMixin from "@/Mixins/ResponseMixin";

export default {
    components: {
        AppLayout,
        CustomInput,
        SelectWithSearch
    },
    mixins: [ClosePageUsingEscMixin,ResponseMixin],
    data() {
        return {
            closing: false,
            updatePage: false,
            pageTitle: this.$t("create_asset"),
            assetData: {
                id: null,
                number: "",
                description: "",
                name: "",
                address_salutation: "",
                address_name: "",
                address_street: "",
                address_zip_code: "",
                address_city: "",
                address_country: "",

                tax_id: "",
                vat_id: "",
                contact_details1_phone1: "",
                contact_details1_mobile: "",
                contact_details1_email: "",

                bank_name: "",
                bank_iban: "",
                bank_bic: "",
                product_id: null
            },
        };
    },
    created() {
        if (this.$page.props.asset) {
            this.assetData = this.$page.props.asset;
            this.pageTitle = this.$t("update_asset");
            this.updatePage = true;
        }
    },

    methods: {
        saveAsset() {
            this.$loading.show({delay: 0, background: "#444"});

            let data = this.assetData;

            if (!this.updatePage) {
                this.$inertia.post("/api/assets", data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            } else {
                this.$inertia.patch("/api/assets/" + this.assetData.id, data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
                this.handleResponse(route('assets.index'), "Success", "Assets Delivery Status");
            }
        },
        productChanged(event) {
            this.assetData.product_id = event.item.id;
        },
    },
};
</script>
