<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("inventory_new_entry") }}
            </h2>
        </template>

        <template #screen-actions>
            <button
                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                type="button"
                @click="perform"
            >
                <svg-vue icon="save" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("save") }}</span>
            </button>
        </template>

        <div class="screen__content">
            <div class="form">
                <inventory-products
                    v-model="formData.products"
                    :entities="formData.products"
                    :is-view-page="false"
                />
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ArticleMixin from "@/Pages/Articles/ArticleMixin";
import InventoryProducts from "@/Components/Inventory/InventoryProducts";

export default {
    components: {
        AppLayout,
        InventoryProducts
    },
    mixins: [ArticleMixin],
    data() {
        return {
            formData: {
                products: []
            },
        };
    },


    methods: {

        perform() {
            this.$inertia.post(route("api.inventories.store"), this.formData);
            this.handleResponse(route("inventories.index"), "Success", "Inventory Adjustment Created");
        },
    },
};
</script>
