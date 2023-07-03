<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("view_purchase_order") }} #{{ $page.props.entity.number }}
            </h2>
        </template>
        <template #screen-actions>
            <inertia-link
                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                :href="route('purchase_orders.edit',$page.props.entity.id)"
            >
                <!--                <svg-vue class="w-5 h-5" icon="save"/>-->
                <span class="text-sm">{{ $t("edit") }}</span>
            </inertia-link>
        </template>

        <div class="screen__content  w-11/12 mx-auto">
            <purchase-order-form v-model="formData" :is-view-page="true" :entity="$page.props.entity"/>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import PurchaseOrderForm from "@/Components/PurchaseOrder/PurchaseOrderForm";
import ResponseMixin from "@/Mixins/ResponseMixin";

export default {
    name: "EditPurchaseOrderPage",
    components: {PurchaseOrderForm, AppLayout},
    mixins: [ResponseMixin],
    data() {
        return {
            formData: {}
        };
    },
};
</script>

<style scoped>

</style>
