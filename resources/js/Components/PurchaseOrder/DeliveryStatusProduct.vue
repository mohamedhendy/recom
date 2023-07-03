<template>
    <div class="mb-2">
        <div class="flex justify-between p-3 px-6 text-center">
            <div class="w-3/12 font-medium text-left">
                {{ saleOrderProduct.sale_order.identity.name }}
                <ErrorMessage :error="$page.props.errors[`related_sales.${index}.id`]"/>
            </div>
            <div class="w-3/12 font-medium text-left">
                <a target="_blank" :href="route('purchase_orders.show',{purchase_order: purchaseOrderProduct.purchase_order_id})">{{ purchaseOrderProduct.product.name }}</a>
            </div>
            <div class="w-2/12 font-medium">
                {{ saleOrderProduct.quantity }}
            </div>
            <div class="w-1/12 font-medium">
                {{ saleOrderProduct.delivered_quantity }}
            </div>
            <div class="w-2/12 font-medium">
                <quantity-field v-model="formData.received_quantity"
                                :disabled="!saleOrderProduct.not_received_quantity"
                                :max="saleOrderProduct.not_received_quantity"
                                :is-integer="true"
                />
                <ErrorMessage :error="$page.props.errors[`related_sales.${index}.delivery_status.received_quantity`]"/>
            </div>
        </div>

        <assets-form v-model="formData.assets" :assets="[]" :quantity="formData.received_quantity"/>
    </div>
</template>

<script>
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import AssetsForm from "@/Components/Assets/AssetsForm";
import QuantityField from "@/Components/QuantityField";
export default {
    name: "DeliveryStatusProduct",
    components: {QuantityField, AssetsForm, ErrorMessage},
    props: ['saleOrderProduct','purchaseOrderProduct',"index"],

    data() {
        return {
            formData: {
                received_quantity: 0,
                assets: []
            }
        }
    },
    watch: {
        formData: {
            deep: true,
            handler(value) {
                this.$emit('input',value);
            }
        }
    }
}
</script>

<style scoped>

</style>
