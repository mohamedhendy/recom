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
                <div class="flex justify-around gap-3">
                    <div class="flex-1">
                        <div class="py-5 mt-5">
                            <div class="grid grid-cols-3 gap-6">
                                <customInput
                                    v-model="entityData.number"
                                    :label="$t('number')"
                                    :required="true"
                                    :errors="$page.props.errors.number"
                                    :disabled="$page.props.view_only"
                                />

                                <customInput
                                    v-model="entityData.name"
                                    :label="$t('name')"
                                    :required="true"
                                    :errors="$page.props.errors.name"
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
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

import ClosePageUsingEscMixin from "@/Mixins/ClosePageUsingEscMixin";
import JsonInput from "@/Components/Reusable/JsonInput";
import CustomInput from "@/Components/Reusable/CustomInput";

export default {
    components: {
        JsonInput,
        AppLayout,
        CustomInput
    },
    mixins: [ClosePageUsingEscMixin],
    props: {
        returnCreated: {
            type: Boolean,
            default: false,
        },
        product: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            closing: false,
            updatePage: false,
            pageTitle: this.$t("create_slot"),
            entityData: {
                id: null,
                name: "",
                number: "",
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
                this.$inertia.post("/api/products/" + this.product.id + "/slots", data, {
                    onSuccess: () => { //page) => {
                        // console.log(page);
                    },

                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            } else {
                this.$inertia.patch("/api/products/" + this.product.id + "/slots/" + this.entityData.id, data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            }

            this.$inertia.on("invalid", (event) => {
                event.preventDefault();
                let productSlot = event.detail.response.data;
                this.$emit("created", {productSlot: productSlot});
            });
        },
        jsonChanged(json) {
            this.entityData.default_info = json;
        },
    },
};
</script>
