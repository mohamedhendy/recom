<template>
    <div class=" py-2 mt-3 mb-3 mx-auto">
        <h1 class="text-xl font-bold">
            <!--            {{ _.get($page.props, 'screen.labels.related_sales_orders', $t("related_sales_orders")) }}-->
            {{ $t("customers") }}
        </h1>

        <div>
            <div class="flex items-center justify-center gap-1 my-2 text-center">
                <!--                <div class="w-8 border p-1 bg-white">-->
                <!--                    #-->
                <!--                </div>-->
                <div class="flex-1 border p-1 bg-white">
                    {{ _.get($page.props, 'screen.labels.customer', $t("customer")) }}
                </div>
                <div class="w-48 border p-1 bg-white">
                    {{ $t("assigned_quantity") }}
                </div>
                <div v-if="isViewPage" class="w-48 border p-1 bg-white rounded-full">
                    {{ $t("billed_quantity") }}
                </div>
                <div class="w-32 border p-1 bg-white">
                    {{ $t("price") }}
                </div>
                <div class="w-48 border p-1 bg-white">
                    {{ $t("total") }}
                </div>
                <div class="w-32 border p-1 bg-white">
                    {{ $t("options") }}
                </div>
            </div>

            <div v-for="(relatedSalesProduct, saleProductIndex) in relatedSalesProducts"
                 :key="saleProductIndex"
            >
                <div class="flex items-center justify-center gap-1 my-2 text-center border-b pb-2">
                    <div class="flex-1">
                        <customers-staffs-select-list
                            v-if="!isViewPage"
                            :hide-title="true"
                            :use-secound-data-source="true"
                            :second-data-source="customersStaffsList"
                            :base-value-type="relatedSalesProduct.identity_type"
                            :base-value-id="relatedSalesProduct.identity_id"
                            :clean-after-select="false"
                            class="max-w-xl"
                            @change="existCustomerChanged(saleProductIndex,...arguments)"
                        />
                        <span v-else>{{ relatedSalesProduct.identity_name }}</span>
                        <ErrorMessage
                            :error="$page.props.errors[`products.${purchaseOrderProductIndex}.related_sales_orders_products.${saleProductIndex}.identity_type`]"
                        />
                        <ErrorMessage
                            :error="$page.props.errors[`products.${purchaseOrderProductIndex}.related_sales_orders_products.${saleProductIndex}.identity_id`]"
                        />
                    </div>

                    <!--                    || relatedSalesProduct.is_to_stock-->
                    <QuantityField
                        v-model="relatedSalesProduct.quantity"
                        :disabled="isViewPage"
                        :error="$page.props.errors[`products.${purchaseOrderProductIndex}.related_sales_orders_products.${saleProductIndex}.quantity`]"
                        :value="relatedSalesProduct.quantity"
                        class="w-48"
                        :title="$t('assigned_quantity')"
                        @change="calcProductTotal(relatedSalesProduct, saleProductIndex)"
                    />


                    <div v-if="isViewPage" class="w-48">
                        <QuantityField
                            v-if="!relatedSalesProduct.is_to_stock"
                            v-model="relatedSalesProduct.billed_quantity"

                            :disabled="isViewPage"
                            :error="$page.props.errors[`products.${purchaseOrderProductIndex}.related_sales_orders_products.${saleProductIndex}.quantity`]"
                            :value="relatedSalesProduct.billed_quantity"
                            :title="$t('billed_quantity')"
                            @change="calcProductTotal(relatedSalesProduct, saleProductIndex)"
                        />
                    </div>
                    <div class="w-32">
                        <CurrencyField
                            v-if="!relatedSalesProduct.is_to_stock"
                            v-model="relatedSalesProduct.price"
                            :disabled="isViewPage || productFixedPrice(relatedSalesProduct.product)"
                            :error="$page.props.errors[`products.${purchaseOrderProductIndex}.related_sales_orders_products.${saleProductIndex}.price`]"
                            :value="relatedSalesProduct.price"
                            :title="$t('price')"
                            @change="calcProductTotal(relatedSalesProduct, saleProductIndex)"
                        />
                    </div>
                    <div class="w-48">
                        <CurrencyField
                            v-if="!relatedSalesProduct.is_to_stock"
                            v-model="relatedSalesProduct.total"
                            :disabled="isViewPage || true"
                            :error="$page.props.errors[`products.${purchaseOrderProductIndex}.related_sales_orders_products.${saleProductIndex}.total`]"
                            :value="relatedSalesProduct.total"
                            :title="$t('total')"
                        />
                    </div>

                    <div v-if="!isViewPage" class="w-32 flex items-center  justify-between">
                        <el-button
                            v-if="!parseFloat(relatedSalesProduct.billed_quantity) ||
                                parseFloat(relatedSalesProduct.billed_quantity) === 0"
                            type="danger"
                            @click="removeRelatedSaleOrder(saleProductIndex) "
                        >
                            {{ $t("remove") }}
                        </el-button>
                        <update-billing-status v-if="relatedSalesProduct.available_quantity_for_billing"
                                               :sale-order-product="relatedSalesProduct"
                        />
                    </div>
                    <div v-else class="w-32 flex items-center  justify-around">
                        <el-button v-if="relatedSalesProduct.assets.length" size="small" type="info"
                                   @click="toggleAssets(relatedSalesProduct,saleProductIndex)"
                        >
                            {{ $t("assets") }} ({{ relatedSalesProduct.assets.length }})
                        </el-button>
                        <update-billing-status v-if="relatedSalesProduct.available_quantity_for_billing"
                                               :sale-order-product="relatedSalesProduct"
                        />
                        <update-delivery-status v-if="relatedSalesProduct && isViewPage"
                                                :purchase-order-product="purchaseOrderProduct"
                                                :sale-order-product="relatedSalesProduct"
                        />
                    </div>
                </div>
                <assets-form v-if="relatedSalesProduct.showAssets " v-model="relatedSalesProduct.assets"
                             :assets="relatedSalesProduct.assets" :is-view-page="isViewPage"
                             :quantity="relatedSalesProduct.billed_quantity"
                             class="mb-20"
                />
            </div>

            <customers-staffs-select-list
                v-if="!isViewPage"
                :use-secound-data-source="true"
                :second-data-source="customersStaffsList"
                class="max-w-xl"
                @change="customerListChanged"
            />
        </div>
    </div>
</template>

<script>
import CustomersSelectList from "@/Components/Customer/CustomersSelectList";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import QuantityField from "@/Components/QuantityField";
import CurrencyField from "@/Components/CurrencyField";
import AlertMixin from "@/Mixins/AlertMixin";
import UpdateBillingStatus from "@/Components/Article/UpdateBillingStatus";
import AssetsForm from "@/Components/Assets/AssetsForm";
import CustomersStaffsSelectList from "@/Components/Identity/CustomersStaffsSelectList";
import UpdateDeliveryStatus from "@/Components/Article/UpdateDeliveryStatus";

export default {
    name: "RelatedSalesOrders",
    components: {
        UpdateDeliveryStatus,
        CustomersStaffsSelectList,
        AssetsForm, UpdateBillingStatus, CurrencyField, QuantityField, ErrorMessage, CustomersSelectList
    },
    mixins: [AlertMixin],
    props: {
        customersStaffsList: {
            type: Array,
            required: true,
            default: null
        },
        purchaseOrderProduct: {
            required: true,
            type: Object
        },
        purchaseOrderProductIndex: {
            required: true,
            type: Number
        },
        product: {
            required: true,
            type: Object
        },
        isViewPage: {
            type: Boolean,
            default: false,
        },
        entities: {
            type: Array,
        },
    },
    data() {
        return {
            relatedSalesProducts: Array(),
            products: Array(),
            newProductId: "",
        };
    },
    created() {
        if (this.entities) this.relatedSalesProducts = this.entities.map((item) => {
            item.total = item.quantity * item.price;
            return item;
        });
    },
    methods: {
        existCustomerChanged(index,e) {
            this.relatedSalesProducts[index].identity_type = e.type;
            this.relatedSalesProducts[index].identity_id = e.item.id;
        },
        toggleAssets(product, index) {

            product.showAssets = !product.showAssets;
            this.relatedSalesProducts.splice(index, 1, product);
        },
        publish() {
            this.$emit("input", this.relatedSalesProducts);
            this.$emit("change", this.relatedSalesProducts);
        },
        removeRelatedSaleOrder(index) {
            this.askUser().then((res) => {
                if (res) {
                    this.relatedSalesProducts.splice(index, 1);
                    this.publish();
                    this.$emit("deleted");
                }
            });
        },
        customerListChanged(value) {
            let isIdentityExist = this.isIdentityExists(value.item.id, value.type);
            if (isIdentityExist)
                this.updateExistsIdentity(isIdentityExist);
            else this.addNewIdentity(value.item, value.type);
        },

        addNewIdentity(identity, identityType) {
            let price = this.getProductPrice(this.product).toFixed(2);
            this.relatedSalesProducts.push({
                quantity: 1,
                discount: 0,
                total: price,
                price: price,
                identity_name: identity.full_name,
                identity_id: identity.id,
                identity_type: identityType,
            });
            this.publish();
        },
        getProductPrice(product) {
            return parseFloat(product.default_sale_price);
        },
        updateExistsIdentity(invoiceProduct) {
        },
        calcProductTotal(saleOrderProduct, relatedSalesProductIndex) {
            this.relatedSalesProducts[relatedSalesProductIndex].total = parseFloat(
                parseFloat(saleOrderProduct.price) * parseFloat(saleOrderProduct.quantity)
            ).toFixed(2);
        },

        isIdentityExists(identityId, identityType) {
            return this.relatedSalesProducts.find((p) => p.identity_id === identityId && p.identity_type === identityType);
        },
        productFixedPrice(product) {
            return false;
        }
    },
}
</script>

<style scoped>

</style>
