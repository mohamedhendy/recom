<template>
    <div class="my-2 py-3 px-2">
        <ErrorMessage :error="$page.props.errors.products"/>

        <!-- <h1 class="text-xl font-bold my-2">Products</h1> -->
        <div>
            <div class="flex items-center justify-center gap-1 my-2 text-center">
                <div class="w-8 border p-2 bg-white rounded-full">
                    {{ $t("id") }}
                </div>
                <div class="flex-1 border p-2 bg-white rounded-full">
                    {{ $t("product") }}
                </div>
                <div class="w-48 border p-2 bg-white rounded-full">
                    {{ $t("quantity") }}
                </div>
                <div v-if="isViewPage" class="w-48 border p-2 bg-white rounded-full">
                    {{ $t("delivered_quantity") }}
                </div>
                <div class="w-32 border p-2 bg-white rounded-full">
                    {{ $t("price") }}
                </div>
                <div class="w-48 border p-2 bg-white rounded-full">
                    {{ $t("total") }}
                </div>
                <div class="w-32 border p-2 bg-white rounded-full">
                    {{ $t("options") }}
                </div>
            </div>

            <div v-for="(invoiceProduct, invoiceProductIndex) in invoiceProducts" :key="invoiceProductIndex"
                 class=" mb-20 shadow-md p-2"
            >
                <div class="flex items-center justify-center gap-1 my-2 text-center  border-b pb-2">
                    <div class="w-8">
                        <div class="bg-black rounded-full w-8 text-white h-8 flex items-center justify-center">
                            {{ invoiceProductIndex + 1 }}
                        </div>
                    </div>
                    <div class="flex-1">
                        {{ invoiceProduct.product.full_name }}
                    </div>

                    <QuantityField
                        v-model="invoiceProduct.quantity"
                        class="w-48"
                        :disabled="isViewPage"
                        :value="invoiceProduct.quantity"
                        :title="$t('quantity')"
                        :error="$page.props.errors[`products.${invoiceProductIndex}.quantity`]"
                        @change="calcProductTotal(invoiceProduct, invoiceProductIndex)"
                    />

                    <QuantityField
                        v-if="isViewPage"
                        v-model="invoiceProduct.delivered_quantity"
                        class="w-48"
                        :disabled="isViewPage"
                        :value="invoiceProduct.delivered_quantity"
                        :title="$t('delivered_quantity')"
                        :error="$page.props.errors[`products.${invoiceProductIndex}.quantity`]"
                        @change="calcProductTotal(invoiceProduct, invoiceProductIndex)"
                    />

                    <CurrencyField
                        v-model="invoiceProduct.price"
                        class="w-32"
                        :disabled="isViewPage || productFixedPrice(invoiceProduct.product)"
                        :value="invoiceProduct.price" 
                        :title="$t('price')"
                        :error="$page.props.errors[`products.${invoiceProductIndex}.price`]"
                        @change="calcProductTotal(invoiceProduct, invoiceProductIndex)"
                    />

                    <CurrencyField
                        v-model="invoiceProduct.total"
                        class="w-48"
                        :disabled="isViewPage || true"
                        :value="invoiceProduct.total"
                        :title="$t('total')"
                        :error="$page.props.errors[`products.${invoiceProductIndex}.total`]"
                    />

                    <div v-if="!isViewPage" class="w-32">
                        <el-button type="danger" @click="removeInvoiceProduct(invoiceProductIndex)">
                            {{ $t("remove") }}
                        </el-button>
                    </div>
                    <div v-else class="w-32 flex items-center justify-between">
                        <el-button v-if="invoiceProduct.assets.length" size="small" type="info"
                                   @click="toggleAssets(invoiceProduct,invoiceProductIndex)"
                        >
                            {{ $t("assets") }} ({{ invoiceProduct.assets.length }})
                        </el-button>
                    </div>
                </div>

                <assets-form v-if=" invoiceProduct.showAssets " v-model="invoiceProduct.assets" class="mb-20"
                             :assets="invoiceProduct.assets" :quantity="invoiceProduct.delivered_quantity" :is-view-page="isViewPage"
                />


                <related-sales-orders v-model="invoiceProduct.related_sale_orders_products" :is-view-page="isViewPage"
                                      :entities="invoiceProduct.related_sale_orders_products"
                                      :product="invoiceProduct.product"
                                      :customers-staffs-list="customersStaffsList"
                                      :purchase-order-product-index="invoiceProductIndex"
                                      :purchase-order-product="invoiceProduct"
                                      @deleted="relatedSaleOrderDeleted(invoiceProductIndex)"
                />

                <object-documents v-if="invoiceProduct.id"
                                  :create-url="route('purchase_orders.documents',invoiceProduct.purchase_order_id)"
                                  :object="invoiceProduct"
                />
            </div>
        </div>

        <div v-if="!isViewPage" class="flex items-center justify-start gap-1 my-2 mt-5">
            <fieldset class="w-1/2">
                <label v-if="invoiceProducts.length > 0" class="block mb-2 font-medium">
                    {{ $t('select_new_product') }}
                </label>
                <el-select v-model="newProductId" :placeholder="$t('product')" class="w-full" filterable
                           @change="productListChanged"
                >
                    <el-option v-for="(item, index) in products" :key="index" :label="item.display_name"
                               :value="item.id"
                    />
                </el-select>
            </fieldset>
            <div>
                <label v-if="invoiceProducts.length > 0" class="block mb-2 font-medium">{{ $t('new_product') }}</label>

                <add-product-modal
                    :index="0"
                    @created="newProductCreated"
                />
            </div>
        </div>
    </div>
</template>

<script>
import CurrencyField from "@/Components/CurrencyField";
import QuantityField from "@/Components/QuantityField";
import Alert from "../../Mixins/AlertMixin";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import AssetsForm from "@/Components/Assets/AssetsForm";
import RelatedSalesOrders from "@/Components/PurchaseOrder/RelatedSalesOrders";
import axios from "axios";
import ObjectDocuments from "@/Components/Documents/ObjectDocuments";
import AddProductModal from "@/Components/Product/AddProductModal";
import UpdateDeliveryStatus from "@/Components/Article/UpdateDeliveryStatus";

require("collections/shim-array");
require("collections/listen/array-changes");

export default {
    name: "PurchaseOrderProducts",
    components: {
        UpdateDeliveryStatus,
        AddProductModal,
        ObjectDocuments, RelatedSalesOrders, AssetsForm, ErrorMessage, QuantityField, CurrencyField
    },
    mixins: [Alert],
    props: {
        customersStaffsList: {
            type: Array,
            default: null
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
            invoiceProducts: Array(),
            products: Array(),
            newProductId: "",
        };
    },
    computed: {
        screen() {
            return this.$page.props.screen;
        }
    },
    created() {
        if (!this.isViewPage) this.fetch();
        if (this.entities) this.invoiceProducts = this.entities.map((item) => {
            item.total = item.quantity * item.price;
            return item;
        });
    },
    methods: {
        relatedSaleOrderDeleted(index) {
            let purchaseOrderProduct = this.invoiceProducts[index];
            let relatedSalesOrder = Array.from(purchaseOrderProduct.related_sale_orders_products);
            if(relatedSalesOrder.length) {
                let totalQuantity = 0;
                relatedSalesOrder.forEach((item) => {
                    totalQuantity+=parseFloat(item.quantity);
                });
                this.invoiceProducts[index].quantity = totalQuantity;
            }
        },
        newProductCreated(e) {
            let product = e.product;
            this.products.push(product);
            this.productListChanged(e.product.id);
        },
        toggleAssets(product, index) {

            product.showAssets = !product.showAssets;
            this.invoiceProducts.splice(index, 1, product);
        },
        toggleSales(product, index) {

            product.showRelatedSalesOrders = !product.showRelatedSalesOrders;
            this.invoiceProducts.splice(index, 1, product);
        },
        publish() {
            this.$emit("input", this.invoiceProducts);
        },
        fetch() {
            axios
                .get(route("api.products.all"))
                .then((res) => (this.products = Array.from(res.data)))
                .then(() => {
                });
        },
        removeInvoiceProduct(index) {
            this.askUser().then((res) => {
                if (res) {
                    this.invoiceProducts.splice(index, 1);
                    this.publish();
                }
            });
        },
        productListChanged(value) {
            let isInvoiceProductExists = this.isInvoiceProductExists(value);

            if (isInvoiceProductExists) {
                this.updateExistsInvoiceProduct(isInvoiceProductExists);
            } else {
                this.addNewInvoiceProduct(value);
            }
        },

        addNewInvoiceProduct(value) {
            const product = this.products.find((p) => p.id === value);

            const price = parseFloat(this.getProductPrice(product)).toFixed(2);

            this.invoiceProducts.push({
                quantity: 1,
                discount: 0,
                total: price,
                related_sale_orders_products: [],
                // subtotal: price,
                price: price,
                product_id: value,
                product: product,
            });

            this.newProductId = "";

            this.publish();
        },

        getProductPrice(product) {
            return parseFloat(product.default_purchase_price);
        },
        updateExistsInvoiceProduct(invoiceProduct) {
            let invoiceProductIndex = this.invoiceProducts.indexOf(invoiceProduct);
            this.invoiceProducts[invoiceProductIndex].quantity =
                parseFloat(invoiceProduct.quantity) + 1;
            this.calcProductTotal(
                this.invoiceProducts[invoiceProductIndex],
                invoiceProductIndex
            );

            this.newProductId = "";
        },
        calcProductTotal(invoiceProduct, invoiceProductIndex) {
            this.invoiceProducts[invoiceProductIndex].total = parseFloat(
                parseFloat(invoiceProduct.price) * parseFloat(invoiceProduct.quantity)
            ).toFixed(2);
        },

        isInvoiceProductExists(productId) {
            return this.invoiceProducts.find((p) => p.product_id === productId);
        },
        productFixedPrice(product) {
            return false;
        }
    },
};
</script>

<style scoped>
</style>
