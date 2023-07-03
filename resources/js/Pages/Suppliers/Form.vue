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
                @click="saveSupplier"
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
                        v-model="supplierData.name"
                        :label="$t('name')"
                        :required="true"
                        :errors="$page.props.errors.name"
                        :disabled="$page.props.view_only"
                    />

                    <customInput
                        v-model="supplierData.number"
                        :label="$t('number')"
                        :required="true"
                        :errors="$page.props.errors.number"
                        :disabled="$page.props.view_only"
                    />

                    <customInput
                        v-model="supplierData.description"
                        :label="$t('comment')"
                        :required="false"
                        :errors="$page.props.errors.description"
                        :disabled="$page.props.view_only"
                    />
                </div>
                <div class="my-6">
                    <div class="grid grid-cols-3 gap-6">
                        <customInput
                            v-model="supplierData.tax_id"
                            :label="$t('tax_id')"
                            :required="false"
                            :errors="$page.props.errors.tax_id"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="supplierData.vat_id"
                            :label="$t('vat_id')"
                            :required="false"
                            :errors="$page.props.errors.vat_id"
                            :disabled="$page.props.view_only"
                        />
                    </div>
                </div>
                <div class="my-6">
                    <h3 class="mb-2 text-2xl text-gray-400 border-b">
                        {{ $t("address") }}
                    </h3>
                    <div class="grid grid-cols-3 gap-6">
                        <customInput
                            v-model="supplierData.address_salutation"
                            :label="$t('address_salutation')"
                            :required="false"
                            :errors="$page.props.errors.address_salutation"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="supplierData.address_name"
                            :label="$t('address_name')"
                            :required="false"
                            :errors="$page.props.errors.address_name"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="supplierData.address_street"
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
                            v-model="supplierData.address_city"
                            :label="$t('address_city')"
                            :required="false"
                            :errors="$page.props.errors.address_city"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="supplierData.address_country"
                            :label="$t('address_country')"
                            :required="false"
                            :errors="$page.props.errors.address_country"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="supplierData.address_zip_code"
                            :label="$t('address_zip_code')"
                            :required="false"
                            :errors="$page.props.errors.address_zip_code"
                            :disabled="$page.props.view_only"
                        />
                    </div>
                </div>

                <div class="my-6">
                    <h3 class="mb-2 text-2xl text-gray-400 border-b">
                        {{ $t("bank_account") }}
                    </h3>
                    <div class="grid grid-cols-3 gap-6">
                        <customInput
                            v-model="supplierData.bank_name"
                            :label="$t('bank_name')"
                            :required="false"
                            :errors="$page.props.errors.bank_name"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="supplierData.bank_iban"
                            :label="$t('bank_iban')"
                            :required="false"
                            :errors="$page.props.errors.bank_iban"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="supplierData.bank_bic"
                            :label="$t('bank_bic')"
                            :required="false"
                            :errors="$page.props.errors.bank_bic"
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
                            v-model="supplierData.contact_details1_email"
                            :label="$t('contact_details1_email')"
                            :required="false"
                            :errors="$page.props.errors.contact_details1_email"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="supplierData.contact_details1_mobile"
                            :label="$t('contact_details1_mobile')"
                            :required="false"
                            :errors="$page.props.errors.contact_details1_mobile"
                            :disabled="$page.props.view_only"
                        />

                        <customInput
                            v-model="supplierData.contact_details1_phone1"
                            :label="$t('contact_details1_phone1')"
                            :required="false"
                            :errors="$page.props.errors.contact_details1_phone1"
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
            pageTitle: this.$t("create_supplier"),
            supplierData: {
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
            },
        };
    },
    created() {
        if (this.$page.props.supplier) {
            this.supplierData = this.$page.props.supplier;
            this.pageTitle = this.$t("update_supplier");
            this.updatePage = true;
        }
    },

    methods: {
        saveSupplier() {
            this.$loading.show({delay: 0, background: "#444"});

            let data = this.supplierData;

            if (!this.updatePage) {
                this.$inertia.post("/api/suppliers", data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            } else {
                this.$inertia.patch("/api/suppliers/" + this.supplierData.id, data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            }
        },
    },
};
</script>
