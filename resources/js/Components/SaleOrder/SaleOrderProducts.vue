<template>
    <div class="my-2 py-3 px-2">
        <ErrorMessage :error="$page.props.errors.products"/>

        <div>
            <div class="flex items-center justify-center gap-1 my-2 text-center">
                <div class="w-8 border p-2 bg-white rounded-full">
                    #
                </div>
                <div class="flex-1 border p-2 bg-white rounded-full">
                    {{ $t("product") }}
                </div>
                <div class="w-48 border p-2 bg-white rounded-full">
                    {{ $t("quantity") }}
                </div>
                <div v-if="isViewPage" class="w-48 border p-2 bg-white rounded-full">
                    {{ $t("billed_quantity") }}
                </div>
                <div class="w-32 border p-2 bg-white rounded-full">
                    {{ $t("price") }}
                </div>
                <div class="w-48 border p-2 bg-white rounded-full">
                    {{ $t("total") }}
                </div>
                <div class="w-32">
                    {{ $t("options") }}
                </div>
            </div>

            <div v-for="(invoiceProduct, invoiceProductIndex) in invoiceProducts"
                 :key="invoiceProductIndex"
                 class="border-b pb-2 "
            >
                <div

                    class="flex items-center justify-center gap-1 my-2 text-center "
                >
                    <div class="w-8">
                        {{ invoiceProduct.product.id }}
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
                        v-model="invoiceProduct.billed_quantity"
                        class="w-48"
                        :disabled="isViewPage"
                        :value="invoiceProduct.billed_quantity"
                        :title="$t('billed_quantity')"
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
                        <el-button
                            type="danger"
                            @click="removeInvoiceProduct(invoiceProductIndex)"
                        >
                            {{ $t("remove") }}
                        </el-button>
                    </div>
                    <div v-else class="w-32 flex items-center justify-between text-center">
                        <update-billing-status v-if="isViewPage && invoiceProduct.available_quantity_for_billing" :sale-order-product="invoiceProduct"/>
                    </div>
                </div>

                <assets-form v-if="isViewPage" v-model="invoiceProduct.assets" :is-view-page="isViewPage" class="mb-20" :assets="invoiceProduct.assets" :quantity="invoiceProduct.billed_quantity"/>
                <object-documents v-if="invoiceProduct.id" :create-url="route('documents.update_sale_order_product',invoiceProduct.id)" :object="invoiceProduct"/>
            </div>
        </div>

        <div v-if="!isViewPage" class="flex items-center justify-center gap-1 my-2 mt-5">
            <div class="flex-1">
                <el-select
                    v-model="newProductId"
                    :placeholder="$t('product')"
                    class="w-full"
                    filterable
                    @change="productListChanged"
                >
                    <el-option
                        v-for="(item, index) in products"
                        :key="index"
                        :label="item.full_name"
                        :value="item.id"
                    />
                </el-select>
            </div>
            <div class="w-64">
                <div>
                    <add-product-modal
                        :index="0"
                        @created="newProductCreated"
                    />
                </div>
            </div>

            <div class="w-48"/>
            <!--            <div class="w-32"/>-->
            <div class="w-48"/>
            <div class="w-20"/>
        </div>
    </div>
</template>

<script>
import CurrencyField from "@/Components/CurrencyField";
import QuantityField from "@/Components/QuantityField";
import Alert from "../../Mixins/AlertMixin";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import AssetsForm from "@/Components/Assets/AssetsForm";
import UpdateBillingStatus from "@/Components/Article/UpdateBillingStatus";
import axios from "axios";
import ObjectDocuments from "@/Components/Documents/ObjectDocuments";
import AddProductModal from "@/Components/Product/AddProductModal";

require("collections/shim-array");
require("collections/listen/array-changes");
export default {
    name: "SaleOrderProducts",
    components: {
        AddProductModal,
        ObjectDocuments, UpdateBillingStatus, AssetsForm, ErrorMessage, QuantityField, CurrencyField
    },
    mixins: [Alert],
    props: {

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
    created() {
        if (!this.isViewPage) this.fetch();
        if (this.entities) this.invoiceProducts = this.entities.map((item) => {
            item.total = item.quantity * item.price;
            return item;
        });
    },
    methods: {
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
            if (isInvoiceProductExists)
                this.updateExistsInvoiceProduct(isInvoiceProductExists);
            else this.addNewInvoiceProduct(value);
        },

        addNewInvoiceProduct(value) {
            const product = this.products.find((p) => p.id === value);

            const price = parseFloat(this.getProductPrice(product)).toFixed(2);
            this.invoiceProducts.push({
                quantity: 1,
                discount: 0,
                total: price,
                price: price,
                product_id: value,
                product: product,
            });
            this.publish();
        },

        getProductPrice(product) {
            return parseFloat(product.default_sale_price);
        },
        updateExistsInvoiceProduct(invoiceProduct) {
            let invoiceProductIndex = this.invoiceProducts.indexOf(invoiceProduct);
            this.invoiceProducts[invoiceProductIndex].quantity =
                parseFloat(invoiceProduct.quantity) + 1;
            this.calcProductTotal(
                this.invoiceProducts[invoiceProductIndex],
                invoiceProductIndex
            );
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
