<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("update_delivery_status") }}
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

        <div class="screen__content">
            <div class="form pr-12">
                <ErrorMessage :error="$page.props.errors.related_sales"/>
                <div>
                    <div
                        class="flex justify-between p-3 px-6 mt-3 mb-3 text-center bg-gray-100"
                    >
                        <div class="w-3/12 font-medium text-center">
                            {{ $t("customer") }}
                        </div>
                        <div class="w-3/12 font-medium text-center">
                            {{ $t("article_name") }}
                        </div>
                        <div class="w-2/12 font-medium">
                            {{ $t("ordered_quantity") }}
                        </div>
                        <div class="w-1/12 font-medium">
                            {{ $t("delivered") }}
                        </div>
                        <div class="w-2/12 font-medium">
                            {{ $t("now_received") }}
                        </div>
                    </div>
                    <div
                        v-for="(
                            saleOrderProduct, index
                        ) in purchaseOrderProductRelatedSales"
                        :key="index"
                    >
                        <delivery-status-product
                            v-model="saleOrderProduct.delivery_status"
                            :index="index"
                            :sale-order-product="saleOrderProduct"
                            :purchase-order-product="purchaseOrderProduct"
                        />
                    </div>
                </div>

                <h3
                    v-if="$page.props.relatedPurchaseOrderProducts.length"
                    class="p-2 pt-4 mt-10 text-sm font-bold"
                >
                    {{ $t("other_articles_for_the_same_invoice") }}
                </h3>

                <div
                    v-for="(row, index) in $page.props.relatedPurchaseOrderProducts"
                    :key="`artciles.${row.id}_${index}`"
                    class="my-2 border-b"
                >
                    <delivery-status-product
                        v-model="row.delivery_status"
                        :index="index"
                        :sale-order-product="row"
                        :purchase-order-product="row.purchase_order_product"
                    />
                    <!-- <div
                        class="border-b"
                    >
                        <div class="flex justify-between p-3 px-6 text-center">
                            <div class="w-3/12 font-medium text-left">
                                {{ row.sale_order.identity.name }}
                            </div>
                            <div class="w-3/12 font-medium text-left">
                                <a target="_blank" :href="route('purchase_orders.show',{purchase_order: row.purchase_order_product.purchase_order_id})">
                                    {{ row.product.name }}
                                </a>
                            </div>
                            <div class="w-1/12 font-medium">
                                {{ row.quantity }}
                            </div>
                            <div class="w-1/12 font-medium">
                                {{ row.delivered_quantity }}
                            </div>

                            <div class="w-1/12 font-medium text-center">
                                <update-delivery-status
                                    :purchase-order-product="row.purchase_order_product"
                                    :sale-order-product="row"
                                />
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ResponseMixin from "@/Mixins/ResponseMixin";
import QuantityField from "@/Components/QuantityField";
import AssetsForm from "@/Components/Assets/AssetsForm";
import UpdateDeliveryStatus from "@/Components/Article/UpdateDeliveryStatus";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import DeliveryStatusProduct from "@/Components/PurchaseOrder/DeliveryStatusProduct";

export default {
    name: "UpdatePurchaseOrderProductDeliveryStatusPage",
    components: {
        DeliveryStatusProduct,
        ErrorMessage,
        UpdateDeliveryStatus,
        AssetsForm,
        QuantityField,
        AppLayout,
    },
    mixins: [ResponseMixin],
    props: [
        "purchaseOrderProduct",
        "purchaseOrderProductRelatedSales",
        "relatedPurchaseOrderProducts",
    ],
    data() {
        return {
            formData: {
                received_quantity: 0,
                assets: [],
            },
        };
    },
    methods: {
        perform() {
            let result = [];
            this.relatedPurchaseOrderProducts.forEach((item) => {
                result.push(item);
            });
            this.purchaseOrderProductRelatedSales.forEach((item) => {
                result.push(item);
            });

            this.$inertia.post(
                route("api.purchase_orders.update_delivery_status", [
                    this.$page.props.purchaseOrderProduct.id,
                ]),
                {
                    related_sales: result,
                }
            );
            this.handleResponse(
                route(
                    "purchase_orders.show",
                    this.$page.props.purchaseOrderProduct.purchase_order_id
                ),
                "Success",
                "Update Delivery Status"
            );
        },
    },
};
</script>

<style scoped>
</style>
