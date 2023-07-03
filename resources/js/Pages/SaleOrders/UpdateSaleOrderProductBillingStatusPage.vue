<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("billing") }}
            </h2>
        </template>

        <template v-if="availableBillingQuantity" #screen-actions>
            <button
                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                type="button"
                @click="perform(false)"
            >
                <svg-vue class="w-5 h-5" icon="save"/>
                <span class="text-sm">{{ $t("mark_as_billed") }}</span>
            </button>

            <button
                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                type="button"
                @click="perform(true)"
            >
                <svg-vue class="w-5 h-5" icon="save"/>
                <span class="text-sm">{{ $t("push_to_easy_bill") }}</span>
            </button>
        </template>

        <div class="screen__content  w-11/12 mx-auto">
            <div class="form">
                <div
                    class="  flex justify-between p-3 px-6 mt-3 mb-3 text-center bg-gray-100"
                >
                    <div class="w-1/12 font-medium"/>
                    <div class="w-3/12 font-medium text-center">
                        {{ $t("customer") }}
                    </div>
                    <div class="w-3/12 font-medium text-center">
                        {{ $t("article_name") }}
                    </div>
                    <div class="w-2/12 font-medium">
                        {{ $t("available_quantity") }}
                    </div>
                    <div class="w-2/12 font-medium">
                        {{ $t("assigned_quantity") }}
                    </div>
                    <div class="w-1/12 font-medium">
                        {{ $t("billed_quantity") }}
                    </div>
                    <div class="w-1/12 font-medium">
                        {{ $t("bill") }}
                    </div>
                    <div class="w-1/12 font-medium text-center"/>
                </div>


                <div
                    class="mb-2"
                >
                    <div class="flex justify-between p-3 px-6 text-center">
                        <div
                            class="flex items-center justify-center w-1/12 font-medium text-center"
                        />
                        <div class="w-3/12 font-medium text-center">
                            {{ $page.props.saleOrder.identity.name }}
                        </div>
                        <div class="w-3/12 font-medium text-center">
                            {{ $page.props.product.name }}
                        </div>
                        <div class="w-2/12 font-medium">
                            {{ availableBillingQuantity }}
                        </div>
                        <div class="w-2/12 font-medium">
                            {{ $page.props.saleOrderProduct.quantity }}
                        </div>
                        <div class="w-1/12 font-medium">
                            {{ $page.props.saleOrderProduct.billed_quantity }}
                        </div>
                        <div class="w-1/12 font-medium">
                            <quantity-field v-model="formData.pushed_quantity"
                                           :disabled="!billableQuantity"
                                            :is-integer="true" :max="billableQuantity"
                            />
                            <ErrorMessage :error="$page.props.errors.pushed_quantity"/>
                            <ErrorMessage :error="$page.props.errors.available_qty"/>
                            <inertia-link
                                v-if="$page.props.errors.available_qty && $page.props.saleOrderProduct.purchase_order_product_id"
                                :href="route('purchase_orders.update_delivery_status',$page.props.saleOrderProduct.purchase_order_product_id)"
                            >
                                {{ $t('update_delivery_status') }}
                            </inertia-link>
                        </div>
                        <div class="w-1/12 font-medium text-center"/>
                    </div>

                    <assets-form :based-on-quantity="true"  :is-view-page="true" :assets="deliveredAssets"
                                 v-model="formData.assets"
                                 :quantity="formData.pushed_quantity"/>
                </div>


                <div v-if="availableBillingQuantity" class="mt-12">
                    <h3 class="text-xl font-medium text-gray-900">
                        {{ $t("easy_bill") }}
                    </h3>

                    <div class="mt-8">
                        <el-select
                            v-model="formData.draft_invoice_id"
                            :error-message="$page.props.errors.draft_invoice_id"
                            :has-error="$page.props.errors.draft_invoice_id"
                            class="w-1/3" filterable placeholder="Easbill invoice"
                        >
                            <el-option
                                v-for="item in easybillInvoices"
                                :key="item.id"
                                :label="draftInvoiceTitle(item)"
                                :value="item.id"
                            />
                        </el-select>
                        <ErrorMessage :error="$page.props.errors.draft_invoice_id"/>
                    </div>
                </div>


                <h3
                    v-if="$page.props.relatedSaleOrdersProducts.length"
                    class="p-2 pt-4 mt-10 text-sm font-bold"
                >
                    {{ $t("other_articles_for_the_same_customer") }}
                </h3>

                <div
                    v-for="(saleOrderProduct, index) in $page.props.relatedSaleOrdersProducts"
                    :key="`artciles.${saleOrderProduct.id}_${index}`"
                    class="my-2 border-b"
                >
                    <div
                        class="border-b"
                    >
                        <div class="flex justify-between p-3 text-center">
                            <div
                                class="flex items-center justify-center w-2/12 font-medium text-center"
                            >
                                {{ saleOrderProduct.created_at }}
                            </div>
                            <div class="w-1/12 font-medium text-center">
                                {{ saleOrderProduct.sale_order_id }}
                            </div>
                            <div class="w-3/12 font-medium text-center">
                                {{ saleOrderProduct.product.name }}
                            </div>
                            <div class="w-1/12 font-medium">
                                {{ saleOrderProduct.quantity }}
                            </div>
                            <div class="w-1/12 font-medium">
                                {{ saleOrderProduct.billed_quantity }}
                            </div>

                            <div class="w-1/12 font-medium text-center">
                                <update-billing-status :sale-order-product="saleOrderProduct"/>
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
import ResponseMixin from "@/Mixins/ResponseMixin";
import QuantityField from "@/Components/QuantityField";
import AssetsForm from "@/Components/Assets/AssetsForm";
import UpdateBillingStatus from "@/Components/Article/UpdateBillingStatus";
import axios from "axios";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    name: "UpdateSaleOrderProductBillingStatusPage",
    components: {ErrorMessage, UpdateBillingStatus, AssetsForm, QuantityField, AppLayout},
    mixins: [ResponseMixin],
    props: ['assets'],
    data() {
        return {
            easybillInvoices: [],
            deliveredAssets: this.assets,
            formData: {
                draft_invoice_id: null,
                pushed_quantity:0,
                assets: [],
            }
        };
    },
    computed: {
        billableQuantity() {
            return this.$page.props.saleOrderProduct.available_quantity_for_billing > this.availableBillingQuantity ? this.availableBillingQuantity : this.$page.props.saleOrderProduct.available_quantity_for_billing;
        },
        availableBillingQuantity() {
            return  this.assets.length > this.$page.props.saleOrderProduct.quantity ? this.$page.props.saleOrderProduct.quantity
                :  this.assets.length;
        }
    },
    created() {
        this.loadEasyBillItems();
        this.formData.pushed_quantity = this.availableBillingQuantity;
    },
    methods: {

        perform(pushToEasyBill = false) {
            this.formData.push_to_easy_bill = pushToEasyBill;
            this.$inertia.post(route("api.sale_orders.update_billing", this.$page.props.saleOrderProduct.id), this.formData);
            this.handleResponse(route("sale_purchase_orders.index"), "Success", "Update Billing Status");
        },
        loadEasyBillItems() {
            this.$loading.show({delay: 0, background: "#444"});

            axios
                .get("/api/billing")
                .then((res) => {
                    this.easybillInvoices = res.data.data;
                })
                .finally(() => {
                    this.$loading.hide();
                });
        },

        draftInvoiceTitle(item) {
            let label = "";

            label += "Invoice ID: " + item.id;
            label +=
                " - Client: " +
                (item.address.hasOwnProperty("title") ? item.address.title : "n/a");
            label +=
                " ( " +
                (item.address.hasOwnProperty("first_name")
                    ? item.address.first_name
                    : "n/a") +
                " )";
            label += " - Title: " + (item.title != null ? item.title : "n/a");

            return label;
        },
    }
};
</script>

<style scoped>

</style>
