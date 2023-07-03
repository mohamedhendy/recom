<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("create_sale_order") }}
            </h2>
        </template>

        <template #screen-actions>
            <button
                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                type="button"
                @click="perform"
            >
                <svg-vue class="w-5 h-5" icon="save"/>
                <span class="text-sm">{{ $t("save") }}</span>
            </button>
        </template>

        <div class="screen__content  w-11/12 mx-auto">
            <sale-order-form v-model="formData"/>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ResponseMixin from "@/Mixins/ResponseMixin";
import SaleOrderForm from "@/Components/SaleOrder/SaleOrderForm";

export default {
    name: "CreateSaleOrderPage",
    components: {SaleOrderForm, AppLayout},
    mixins: [ResponseMixin],
    data() {
        return {
            formData: {}
        }
    },
    methods: {
        perform() {
            this.$inertia.post(route("api.sale_orders.store"), this.formData);
            this.handleResponse(route("sale_orders.index"), "Success", "Sale Order Created",true);
        },
    }
}
</script>

<style scoped>

</style>
