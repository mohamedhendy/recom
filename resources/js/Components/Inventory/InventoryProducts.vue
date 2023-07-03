<template>
    <div class="my-2 py-3 px-2">
        <ErrorMessage :error="$page.props.errors.products"/>

        <div>
            <div class="flex items-center justify-center gap-1 my-2 text-center">
                <div class="w-8 border p-2 bg-white rounded-full">
                    #
                </div>
                <div class="flex-1 border p-2 bg-white rounded-full">
                    {{ $t('product') }}
                </div>
                <div class="w-48 border p-2 bg-white rounded-full">
                    {{ $t('quantity') }}
                </div>
                <div class="w-48 border p-2 bg-white rounded-full">
                    {{ $t('unit_cost') }}
                </div>
                <div class="w-64 border p-2 bg-white rounded-full">
                    {{ $t('action') }}
                </div>
                <div class="w-48 border p-2 bg-white rounded-full">
                    {{ $t('comment') }}
                </div>
                <div class="w-32">
                    {{ $t('options') }}
                </div>
            </div>

            <div v-for="(invoiceProduct, invoiceProductIndex) in invoiceProducts" :key="invoiceProductIndex">
                <div class="flex items-center justify-center gap-1 my-2 text-center border-b pb-2">
                    <div class="w-8">
                        {{ invoiceProduct.product.id }}
                    </div>

                    <div class="flex-1">
                        {{ invoiceProduct.product.full_name }}
                    </div>

                    <QuantityField
                        v-model="invoiceProduct.quantity"
                        :disabled="isViewPage"
                        :error="$page.props.errors[`products.${invoiceProductIndex}.quantity`]"
                        :is-integer="true"
                        :value="invoiceProduct.quantity"
                        class="w-48"
                        :title="$t('quantity')"
                    />

                    <div class="w-48">
                        <CurrencyField
                            v-if="invoiceProduct.transaction === 'increase'"
                            v-model="invoiceProduct.unit_cost"
                            :disabled="isViewPage"
                            :error="$page.props.errors[`products.${invoiceProductIndex}.unit_cost`]"
                            :is-integer="true"
                            :value="invoiceProduct.unit_cost"
                            :title="$t('unit_cost')"
                        />
                    </div>
                    <div class="w-64">
                        <el-select v-model="invoiceProduct.transaction" :disabled="isViewPage" class="w-full"
                                   :placeholder="$t('select_action')"
                        >
                            <el-option v-for="item in rowItemActions" :key="item.value" :label="item.label"
                                       :value="item.value"
                            />
                        </el-select>
                        <ErrorMessage :error="$page.props.errors[`products.${invoiceProductIndex}.transaction`]"/>
                    </div>
                    <div class="w-48">
                        <el-input v-model="invoiceProduct.comment" :placeholder="$t('comment')"/>

                        <ErrorMessage :error="$page.props.errors[`products.${invoiceProductIndex}.comment`]"/>
                    </div>
                    <div v-if="!isViewPage" class="w-32">
                        <el-button type="danger" @click="removeInvoiceProduct(invoiceProductIndex)">
                            {{ $t('remove') }}
                        </el-button>
                    </div>
                    <div v-else class="w-32 flex items-center justify-between">
                        <el-button v-if="invoiceProduct.assets.length" size="small" type="info"
                                   @click="toggleAssets(invoiceProduct,invoiceProductIndex)"
                        >
                            $t('assets')
                        </el-button>
                    </div>
                </div>

                <assets-form v-if="invoiceProduct.quantity > 0 && invoiceProduct.transaction === 'increase'"
                             v-model="invoiceProduct.assets" :assets="invoiceProduct.assets"
                             :is-view-page="isViewPage"
                             :quantity="invoiceProduct.quantity" class="mb-20"
                />
            </div>
        </div>

        <div v-if="!isViewPage" class="flex items-center justify-center gap-1 my-2 mt-5">
            <div class="w-8"/>
            <div class="flex-1">
                <el-select v-model="newProductId" :placeholder="$t('product')" class="w-full" filterable
                           @change="productListChanged"
                >
                    <el-option v-for="item in products" :key="item.id" :label="item.full_name" :value="item.id"/>
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
            <div class="w-32"/>
            <div class="w-48"/>
            <div class="w-20"/>
        </div>
    </div>
</template>

<script>
import QuantityField from "@/Components/QuantityField";
import Alert from "../../Mixins/AlertMixin";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import AssetsForm from "@/Components/Assets/AssetsForm";
import "collections/shim-array"
import "collections/listen/array-changes";
import axios from "axios";
import CurrencyField from "@/Components/CurrencyField";
import AddProductModal from "@/Components/Product/AddProductModal";

export default {
    name: "InventoryProducts",
    components: {AddProductModal, CurrencyField, AssetsForm, ErrorMessage, QuantityField},
    mixins: [Alert],
    props: {

        isViewPage: {
            type: Boolean,
            default: false,
        },
        entities: {
            type: Array,
            default: () => []
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
        rowItemActions() {
            return [{
                value: 'increase',
                label: this.$t('add_to_inventory')
            }, {
                value: 'descrease',
                label: this.$t('descrase_from_inventory')
            }]
        }
    },
    created() {
        if (!this.isViewPage) this.fetchProducts();
        if (this.entities) this.invoiceProducts = this.entities;
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
        publish() {
            this.$emit("input", this.invoiceProducts);
        },
        fetchProducts() {
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
            if (!isInvoiceProductExists) this.addNewInvoiceProduct(value);
        },

        addNewInvoiceProduct(value) {
            const product = this.products.find((p) => p.id === value);
            this.invoiceProducts.push({
                quantity: 1,
                product_id: value,
                unit_cost: 0,
                total: 0,
                product: product,
            });
            this.publish();
        },


        isInvoiceProductExists(productId) {
            return this.invoiceProducts.find((p) => p.product_id === productId);
        },

    },
};
</script>

<style scoped>
</style>
