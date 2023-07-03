<template>
    <div class="">
        <ErrorMessage :error="$page.props.errors[`articles`]"/>

        <div v-for="(article, index) in articles" :key="index">
            <hr class="my-8">

            <div>
                <div class="flex space-x-4">
                    <h3 class="text-2xl font-medium text-gray-900">
                        {{ $t("article") }} {{ index + 1 }}
                    </h3>
                    <button
                        v-if="!viewOnly"
                        class="flex items-center px-4 py-2 space-x-4 text-white bg-red-600 rounded hover:bg-red-500 focus:outline-none"
                        type="button"
                        @click="deleteArticle(index)"
                    >
                        <svg-vue icon="trash" class="w-5 h-5"/>
                    </button>
                </div>

                <div class="flex gap-2">
                    <div class="flex-1">
                        <div class="flex gap-6 mt-6">
                            <div class="w-1/2">
                                <label class="font-bold">{{ $t("product") }}</label>
                                <div>
                                    <el-select
                                        v-model="article.product_id"
                                        :disabled="viewOnly"
                                        filterable
                                        :placeholder="$t('product')"
                                        class="w-full"
                                        @change="productUpdated(index, article)"
                                    >
                                        <el-option
                                            v-for="product in products"
                                            :key="product.id"
                                            :label="`${product.ean_number} ${product.name}`"
                                            :value="product.id"
                                        />
                                    </el-select>
                                    <ErrorMessage :error="$page.props.errors[`articles.${index}.product_id`]"/>
                                </div>
                            </div>

                            <div>
                                <label>or</label>
                                <add-product-modal
                                    :index="index"
                                    @created="newProductCreated"
                                />
                            </div>

                            <customInput
                                v-model="article.description"
                                :label="$t('comment')"
                                :required="true"
                                :errors="$page.props.errors[`articles.${index}.description`]"
                                :disabled="$page.props.view_only"
                            />
                        </div>

                        <div class="flex items-center gap-8">
                            <div class="mt-5 flex-1">
                                <div class="flex items-center w-full gap-6">
                                    <div v-if="isCreateSalesPage()" class="w-32">
                                        <custom-currency-input
                                            v-model="article.sales_price"
                                            :value="article.sales_price"
                                            :disabled="viewOnly"
                                            :error="$page.props.errors[`articles.${index}.sales_price`]"
                                            :placeholder="$t('sales_price')"
                                        />
                                    </div>

                                    <div v-else class="w-32">
                                        <custom-currency-input
                                            v-model="article.cost_price"
                                            :disabled="viewOnly"
                                            :error="$page.props.errors[`articles.${index}.cost_price`]"
                                            :placeholder="$t('cost_price')"
                                        />
                                    </div>

                                    <customInput
                                        v-model="article.quantity"
                                        classes="w-32"
                                        :label="$t('total_quantity')"
                                        :required="true"
                                        :errors="$page.props.errors[`articles.${index}.quantity`]"
                                        :disabled="$page.props.view_only"
                                        @change="articleIdentityQuantityUpdated(index)"
                                    />

                                    <ErrorMessage v-if="isCreateSalesPage()" :error="$page.props.errors[`articles.${index}.available_quantity`]"/>

                                    <div class="h-full flex items-center justify-center pt-5">
                                        {{ $t("remaining_quantity") }}:
                                        {{ remainingQuantity(index) }}
                                    </div>

                                    <div v-if="isCreateSalesPage()" class="w-32">
                                        Total
                                        <display-money
                                            :money="
                                                (isCreateSalesPage()
                                                    ? parseFloat(article.quantity) *
                                                        parseFloat(article.sales_price)
                                                    : parseFloat(article.quantity) *
                                                        parseFloat(article.cost_price)) / 100
                                            "
                                        />
                                    </div>
                                    <div v-if="products.find( p => p.id === article.product_id)" class="w-48">
                                        <label>{{ $t("ean_number") }}</label>
                                        {{ products.find( p => p.id === article.product_id).ean_number }} <inertia-link
                                            :href="`/products/${article.product_id}/edit`"
                                            class=""
                                        >
                                            <span class="text-sm">{{ $t("edit") }}</span>
                                        </inertia-link>
                                    </div>
                                    <div class="h-full flex items-center justify-center ml-10">
                                        <inertia-link
                                            v-if="editMode === true"
                                            :href="`/articles/${article.invoice_id}/update_documents`"
                                            class="flex items-center px-4 py-2 mt-8 space-x-4 text-gray-900 border border-gray-900 rounded-full focus:outline-none"
                                        >
                                            <svg-vue icon="plus" class="w-5 h-5"/>
                                            <span class="text-sm">{{ $t("add_documents") }}</span>
                                        </inertia-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <identities-list
                :article="article"
                :identities-list-updated="article.identities_list_updated"
                :index="index"
                :init-clients="article.initClients"
                :product="article.product"
                :show-deployments="showDeployments || isCreateSalesPage()"
                :view-only="viewOnly"
                @updated="clientsUpdated"
            />
        </div>
    </div>
</template>

<script>
import SelectWithSearch from "@/Components/Reusable/SelectWithSearch";
import IdentitiesList from "../Article/IdentitiesList.vue";
import AddProductModal from "../Product/AddProductModal.vue";
import DisplayMoney from "../DisplayMoney.vue";
import CustomInput from "@/Components/Reusable/CustomInput";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    name: "InvoiceArticleForm",
    components: {
        SelectWithSearch,
        IdentitiesList,
        AddProductModal,
        DisplayMoney,
        CustomInput,
        ErrorMessage
    },
    props: {
        showDeployments: {
            type: Boolean,
            default: true,
        },
        viewOnly: {
            type: Boolean,
            default: false,
        },
        articlesCounter: {
            type: Number,
            default: 0,
        },
        initArticles: {
            type: Array,
            default: () => [],
        },
        editMode: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            products: this.$page.props.products,
            articles: [],
        };
    },
    watch: {
        articlesCounter() {
            this.addNewArticle();
        },
        articles: {
            deep: true,
            handler(value) {
                this.$emit("updated", { articles: value });
            },
        },
    },
    created() {
        if (this.editMode) {
            this.initArticles.forEach((item) => {
                item.initClients = item.article_identities;
                item.initDocuments = item.documents;
                this.articles.push(item);
            });
        } else this.addNewArticle();
    },
    methods: {
        isCreateSalesPage() {
            return (
                this.$page.props.page_type === "create" && this.$page.props.invoice_type === "sale"
            );
        },
        articleIdentityQuantityUpdated(index) {
            // if(this.$page.props.invoice_type === 'beginning-inventory')
            // {
            //     // let stockIdentity = this.articles[index].article_identities[0];
            //     // if(this.articles[index].article_identities.length === 1 && stockIdentity && stockIdentity.type === "customer" && stockIdentity.is_stock)
            //     // {
            //     //     // stockIdentity.quantity = this.articles[index].quantity;
            //     //     // this.articles[index].article_identities[0] = stockIdentity;
            //     //     // this.articles[index].identities_list_updated = this.articles[index].article_identities;
            //     //     // this.articles[index].show_collapse_panel = !this.articles[index].show_collapse_panel;
            //     // }
            // }
        },
        newProductCreated(e) {
            let product = e.product;
            this.products.push(product);
            this.articles[e.index].reset_key = product;
        },
        productUpdated(articleIndex, article) {
            let product = this.products.find((p) => p.id === article.product_id);
            // console.log(event);
            // this.articles[event.index].product_id = event.item.id;
            if (this.isCreateSalesPage())
                this.articles[articleIndex].sales_price = product.sales_price;
            else {
                this.articles[articleIndex].cost_price = product.purchase_price;
                this.updateArticleIdentitiesSalesPrice(
                    product.sales_price,
                    articleIndex
                );
            }
        },
        updateArticleIdentitiesSalesPrice(salesPrice, articleIndex) {
            let updatedArticleIdentitiesList = [];
            this.articles[articleIndex].article_identities.forEach(
                (oldArticleIdentity) => {
                    oldArticleIdentity.sales_price = salesPrice;
                    updatedArticleIdentitiesList.push(oldArticleIdentity);
                }
            );

            this.articles[
                articleIndex
            ].article_identities = updatedArticleIdentitiesList;
        },

        remainingQuantity(articleIndex) {
            let total = 0;
            this.articles[articleIndex].article_identities.forEach((item) => {
                if (!isNaN(parseInt(item.quantity)))
                    total = parseInt(item.quantity) + parseInt(total);
            });

            let quantity = 0;
            if (!isNaN(parseInt(this.articles[articleIndex].quantity)))
                quantity = parseInt(this.articles[articleIndex].quantity);
            return parseInt(quantity) - parseInt(total);
        },
        addNewArticle() {
            this.articles.push({
                name: "",
                product_id: "",
                cost_price: "",
                currency_code: "€",
                quantity: "",
                remaining_to_assign: "",
                article_identities: [],
                documents: [],
            });
        },

        clientsUpdated(e) {
            let article = this.articles[e.articleIndex];
            article.article_identities = e.clients;
            this.articles.splice(e.articleIndex, 1, article);
        },

        documentsUpdated(e) {
            let article = this.articles[e.articleIndex];
            article.documents = e.documents;
            this.articles.splice(e.articleIndex, 1, article);
        },

        deleteArticle(index) {
            this.$confirm({
                message: this.$t("are_you_sure"),
                button: {
                    no: this.$t("no"),
                    yes: this.$t("yes"),
                },
                callback: (confirm) => {
                    if (confirm) {
                        this.articles.splice(index, 1);
                    }
                },
            });
        },
    },
};
</script>

<style scoped>
</style>
