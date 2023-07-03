<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("view_sale_order") }} #{{ $page.props.sale_order.id }}
            </h2>
        </template>


        <div class="screen__content  w-11/12 mx-auto">
            <sale-order-form v-model="formData" :entity="$page.props.sale_order" :is-view-page="true"/>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ResponseMixin from "@/Mixins/ResponseMixin";
import SaleOrderForm from "@/Components/SaleOrder/SaleOrderForm";

export default {
    name: "ShowSaleOrderPage",
    components: {SaleOrderForm, AppLayout},
    mixins: [ResponseMixin],
    data() {
        return {
            formData: {}
        }
    },
}
</script>

<style scoped>

</style>
