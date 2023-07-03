<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ pageTitle }}
            </h2>
        </template>

        <template #screen-actions>
            <qr-code :product="$page.props.entity"/>

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
                <div class="flex justify-around gap-3">
                    <div class="flex-1">
                        <div class="py-5 mt-5">
                            <div class="grid grid-cols-3 gap-6">
                                <customInput
                                    v-model="entityData.name"
                                    :label="$t('name')"
                                    :required="true"
                                    :errors="$page.props.errors.name"
                                    :disabled="$page.props.view_only"
                                />

                                <customInput
                                    v-model="entityData.ean_number"
                                    :label="$t('ean_number')"
                                    :required="false"
                                    :errors="$page.props.errors.ean_number"
                                    :disabled="$page.props.view_only"
                                />

                                <div>
                                    <categories-select-list v-model="entityData.category_id" :base-value="entityData.category_id"></categories-select-list>
                                    <ErrorMessage :error="$page.props.errors.category_id"></ErrorMessage>
                                </div>
                            </div>
                        </div>

                        <div class="py-5">
                            <div class="grid grid-cols-3 gap-6">
                                <custom-currency-input
                                    v-model="entityData.default_sale_price"
                                    :error="$page.props.errors.default_sale_price"
                                    :placeholder="$t('default_sale_price')"
                                    :required="true"
                                />

                                <custom-currency-input
                                    v-model="entityData.default_purchase_price"
                                    :error="$page.props.errors.default_purchase_price"
                                    :placeholder="$t('default_purchase_price')"
                                    :required="true"
                                />

                                <customInput
                                    v-model="entityData.comment"
                                    :label="$t('comment')"
                                    :required="false"
                                    :errors="$page.props.errors.comment"
                                    :disabled="$page.props.view_only"
                                />
                            </div>
                        </div>
                        <div class="py-5">
                            <div class="grid grid-cols-3 gap-6">
                                <customInput
                                    v-model="entityData.manufacturer"
                                    :label="$t('manufacturer')"
                                    :required="false"
                                    :errors="$page.props.errors.manufacturer"
                                    :disabled="$page.props.view_only"
                                />
                                <customInput
                                    v-model="entityData.manufacturer_number"
                                    :label="$t('manufacturer_number')"
                                    :required="false"
                                    :errors="$page.props.errors.manufacturer_number"
                                    :disabled="$page.props.view_only"
                                />

                                <customInput
                                    v-model="entityData.model"
                                    :label="$t('model')"
                                    :required="false"
                                    :errors="$page.props.errors.model"
                                    :disabled="$page.props.view_only"
                                />
                            </div>
                        </div>
                        <div class="py-5">
                            <div class="grid grid-cols-3 gap-6">
                                <div>
                                    <label>{{ $t("default_info") }}</label>
                                    <json-input
                                        :error-message="$page.props.errors.default_info"
                                        :has-error="$page.props.errors.default_info"
                                        :json="entityData.default_info"
                                        @json-changed="jsonChanged"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="py-5">
                            <div class="slot-definitions">
                                <label>{{ $t("slot_definitions") }}</label>

                                <inertia-link
                                    class="flex items-center px-4 py-2 space-x-3 text-white bg-gray-900 rounded-full hover:bg-gray-800"
                                    :href="`/products/${entityData.id}/slots/create`"
                                >
                                    <svg-vue icon="plus" class="w-4 h-4"/>
                                    <span class="text-sm">{{ $t("new_slot") }}</span>
                                </inertia-link>

                                <Datatable
                                    :endpoint="`/api/products/${entityData.id}/slots`"
                                    @metaHasBeenUpdated="metaHasBeenUpdated"
                                ><!-- TODO empty on create -->
                                    <template #columns>
                                        <th
                                            :class="{ 'bg-gray-100': orderBy === 'number' }"
                                            class="datatable__header"
                                            scope="col"
                                            @click="updateOrderBy('number')"
                                        >
                                            {{ $t("number") }}
                                        </th>
                                        <th
                                            :class="{ 'bg-gray-100': orderBy === 'name' }"
                                            class="datatable__header w-96"
                                            scope="col"
                                            @click="updateOrderBy('name')"
                                        >
                                            {{ $t("name") }}
                                        </th>
                                        <th class="datatable__header" scope="col"/>
                                        <th class="datatable__header" scope="col"/>
                                    </template>
                                    <template #row="raw">
                                        <td class="px-2 font-medium text-center text-gray-900">
                                            {{ raw.number }}
                                        </td>
                                        <td class="px-2 font-medium text-center text-gray-900">
                                            {{ raw.name }}
                                        </td>
                                        <td
                                            class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                                        >
                                            <inertia-link
                                                :href="`/products/${entityData.id}/slots/${raw.id}/edit`"
                                                class="block py-2 hover:text-indigo-900"
                                            >
                                                {{ $t("edit") }}
                                            </inertia-link>
                                        </td>
                                        <td
                                            class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                                        >
                                            <button
                                                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                                                type="button"
                                                @click="deleteSlot(raw.id)"
                                            >
                                                <svg-vue icon="delete" class="w-5 h-5"/>
                                                <span class="text-sm">{{ $t("remove") }}</span>
                                            </button>
                                        </td>
                                    </template>
                                </Datatable>
                            </div>
                        </div>
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
import JsonInput from "@/Components/Reusable/JsonInput";
import QrCode from '../../Components/Product/QrCode.vue';
import CustomInput from "@/Components/Reusable/CustomInput";
import Datatable from "@/Components/Datatable/Datatable";
import CategoriesSelectList from "@/Components/Category/CategoriesSelectList";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    components: {
        ErrorMessage,
        CategoriesSelectList,
        JsonInput,
        AppLayout,
        SelectWithSearch,
        QrCode,
        CustomInput,
        Datatable
    },
    mixins: [ClosePageUsingEscMixin],
    props: {
        returnCreated: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            closing: false,
            updatePage: false,
            pageTitle: this.$t("create_product"),
            entityData: {
                id: null,
                name: "",
                ean_number: "",
                category_id: "",
                category_name: "",
                default_sale_price: 0,
                default_purchase_price: 0,
                comment: "",
                manufacturer: "",
                manufacturer_number: "",
                model: "",
                default_info: "{}",
            },
            orderBy: "id",
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
        metaHasBeenUpdated(e) {
            this.meta = e.meta;
        },
        updateOrderBy(cl) {
            if (this.orderBy === cl) {
                this.orderDirection = this.orderDirection === "asc" ? "desc" : "asc";
            }
            this.orderBy = cl;
            let params = JSON.parse(this.queryParams);
            params.orderDirection = this.orderDirection;
            params.orderBy = this.orderBy;
            params.status = this.activeTab;
            this.queryParams = JSON.stringify(params);
        },
        saveEntity() {
            this.$loading.show({delay: 0, background: "#444"});

            let data = this.entityData;
            data.return_created = this.returnCreated;
            if (!this.updatePage) {
                this.$inertia.post("/api/products", data, {
                    onSuccess: () => { //page
                        // console.log(page);
                    },

                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            } else {
                this.$inertia.patch("/api/products/" + this.entityData.id, data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            }

            this.$inertia.on("invalid", (event) => {
                event.preventDefault();
                let product = event.detail.response.data;
                this.$emit("created", {product: product});
            });
        },
        categoryChanged(event) {
            this.entityData.category_id = event.item.id;
        },
        jsonChanged(json) {
            this.entityData.default_info = json;
        },
        deleteSlot(slotId) {
            this.$inertia.delete(`/api/products/${this.entityData.id}/slots/${slotId}`);
        }
    },
};
</script>
