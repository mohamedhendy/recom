<template>
    <div class="mt-12">
        <ErrorMessage :error="$page.props.errors[`articles.${index}.article_identities`]"/>
        <hr class="my-7">
        <div
            v-for="(client, clientIndex) in clients"
            :key="`${clientIndex}_identities_${client.type}_${index}_list_key_${getRandomKey(client)}`"
        >
            <div class="flex gap-6 mt-5">
                <div class="w-96">
                    <label v-if="client.type === 'staff'">{{ $t("identity_staff") }}</label>
                    <label v-else-if="client.is_stock === true">{{ $t("identity_stock") }}</label>
                    <label v-else>{{ $t("identity_client") }} </label>
                    <select-with-search
                        v-if="client.type === 'staff'"
                        :error-message="
                            $page.props.errors[
                                `articles.${index}.article_identities.${clientIndex}.identity_id`
                            ]
                        "
                        :has-error="
                            $page.props.errors[
                                `articles.${index}.article_identities.${clientIndex}.identity_id`
                            ]
                        "
                        :index="clientIndex"
                        :init-id="client.initCustomerId"
                        :is-disabled="viewOnly"
                        :items="$page.props.staffs"
                        :placeholder="$t('identity_staff')"
                        :label="(item) => `${item.name} - ${item.staff_number}`"
                        @item-changed="customerUpdated"
                    />

                    <select-with-search
                        v-else
                        :error-message="
                            $page.props.errors[
                                `articles.${index}.article_identities.${clientIndex}.identity_id`
                            ]
                        "
                        :has-error="
                            $page.props.errors[
                                `articles.${index}.article_identities.${clientIndex}.identity_id`
                            ]
                        "
                        :index="clientIndex"
                        :init-id="client.initCustomerId"
                        :is-disabled="viewOnly"
                        :items="$page.props.customers"
                        :placeholder="$t('identity_client')"
                        :label="(item) => `${item.name} - ${item.number}`"
                        @item-changed="customerUpdated"
                    />
                </div>

                <customInput
                    v-model="client.quantity"
                    :label="$t('quantity')"
                    class="w-32"
                    :required="false"
                    :errors="$page.props.errors[`articles.${index}.article_identities.${clientIndex}.quantity`]"
                    :disabled="$page.props.view_only"
                    @change="quantityUpdated(clientIndex, client)"
                />

                <div v-if="!isCreateSalesPage()" class="w-32">
                    <custom-currency-input
                        v-model="client.sales_price"
                        :disabled="viewOnly"
                        :error="$page.props.errors[`articles.${index}.sales_price`]"
                        :placeholder="$t('sale_price')"
                    />
                </div>

                <div class="flex-1 flex gap-3">
                    <customInput
                        v-model="client.description"
                        :label="$t('comment')"
                        class="w-5/6"
                        :required="false"
                        :errors="`articles.${index}.article_identities.${clientIndex}.comment`"
                        :disabled="$page.props.view_only"
                    />

                    <!-- && $page.props.invoice_type !== 'beginning-inventory' -->
                    <div
                        v-if="!viewOnly"
                        class="flex items-center justify-center w-2/6 pt-5"
                    >
                        <button
                            class="flex items-center px-4 py-2 space-x-4 text-white bg-red-600 rounded"
                            type="button"
                            @click="deleteClient(clientIndex)"
                        >
                            <svg-vue icon="trash" class="w-5 h-5"/>
                        </button>
                    </div>
                    <div v-if="showDeployments || isCreateSalesPage()" class="flex items-center justify-center pt-5">
                        <button
                            class="flex items-center justify-center"
                            @click="toggleArticleIdentityDeployments(clientIndex)"
                        >
                            {{ $t("article_information") }}
                            <svg-vue icon="right" class="w-6 h-6"/>
                        </button>
                    </div>
                    <div v-if="isCreateSalesPage() && article.product_id" class="flex items-center justify-center pt-5">
                        <a
                            target="_blank"
                            :href="`/products/${article.product_id}/deployments`"
                            class="flex items-center justify-center"
                        >
                            {{ $t("show_exists_deployments") }}
                        </a>
                    </div>
                </div>
            </div>



            <deployments
                v-if="showDeployments"
                :identity="client"
                :index="clientIndex"
                :product="product"
                :show="client.show_collapse_panel"
                :updated-deployments="client.updated_deployments"
                @updated="deploymentsUpdated"
            />
        </div>

        <!-- && $page.props.invoice_type !== 'beginning-inventory' -->
        <div class="flex gap-3">
            <button
                v-if="!viewOnly"
                class="flex items-center px-4 py-2 mt-8 space-x-4 text-gray-900 border border-gray-900 rounded-full focus:outline-none"
                type="button"
                @click="addNewClient"
            >
                <svg-vue icon="plus" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("add_client") }}</span>
            </button>
            <!-- && $page.props.invoice_type !== 'beginning-inventory' -->
            <button
                v-if="!viewOnly"
                class="flex items-center px-4 py-2 mt-8 space-x-4 text-gray-900 border border-gray-900 rounded-full focus:outline-none"
                type="button"
                @click="addNewStaff"
            >
                <svg-vue icon="plus" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("add_staff") }}</span>
            </button>
            <!-- && $page.props.invoice_type !== 'beginning-inventory' -->
            <button
                v-if="!viewOnly && showAddStockButton && !isCreateSalesPage()"
                class="flex items-center px-4 py-2 mt-8 space-x-4 text-gray-900 border border-gray-900 rounded-full focus:outline-none"
                type="button"
                @click="addStock"
            >
                <svg-vue icon="plus" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("add_stock") }}</span>
            </button>
        </div>
    </div>
</template>

<script>
import SelectWithSearch from "@/Components/Reusable/SelectWithSearch";
import Deployments from "./../ArticleIdentity/Deployments";
import CustomInput from "@/Components/Reusable/CustomInput";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    name: "InvoiceArticleClientsForm",
    components: {
        SelectWithSearch,
        Deployments,
        CustomInput,
        ErrorMessage
    },
    props: {
        showDeployments: {
            type: Boolean,
            default: true
        },
        viewOnly: {
            type: Boolean,

            default: false,
        },
        product: {
            type: Object,
            default: () => null
        },
        article: {
            type: Object,
            required: true,
        },
        identitiesListUpdated: {
            type: Array,
            default: () => undefined
        },
        index: {
            type: Number,
            required: true,
        },
        initClients: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            clients: [],
        };
    },
    computed: {
        showAddStockButton() {
            return !this.clients.find((p) => p.is_stock);
        },
        isCreatingBeginning() {
            return this.$page.props.page_type === "create" && this.$page.props.invoice_type === 'beginning-inventory';
        }
    },
    watch: {

        identitiesListUpdated: {
            deep: true,
            handler(newList) {
                if (newList) {
                    for (const newListElementIndex in newList) {
                        // console.log(newListElementIndex)
                        // this.quantityUpdated(newListElementIndex,newList[newListElementIndex])
                    }
                }
            },
        },
        clients: {
            deep: true,
            handler(value) {
                this.$emit("updated", {clients: value, articleIndex: this.index});
            },
        },
    },
    created() {
        this.initClients.forEach((client) => {
            client.initProjects = client.identity.projects;
            client.initPorjectId = client.project_id;
            client.initCustomerId = client.identity.id;
            client.deployments = client.deployments ?? [];
            this.clients.push(client);
        });

        if (this.initClients.length === 0 && this.$page.props.invoice_type === 'beginning-inventory')
            this.addStock();

    },

    methods: {
        isCreateSalesPage() {
            return (this.$page.props.page_type === 'create' && this.$page.props.invoice_type === 'sale');
        },
        quantityUpdated(clientIndex, client) {
            if (this.$page.props.invoice_type === 'beginning-inventory'  || this.isCreateSalesPage())
                this.generateDeploymentsList(clientIndex, client);
        },
        generateDeploymentsList(clientIndex, client) {
            let oldDeployments = client.deployments ? client.deployments : [];
            let clientQuantity = parseInt(client.quantity);
            let newList = [];
            for (let x = 0; x < clientQuantity; x++) {
                if (oldDeployments[x]) {
                    newList.push(oldDeployments[x]);
                } else {
                    newList.push({
                        serial_number: "",
                        ean_number: "",
                        a_number: "",
                    });
                }
            }
            this.toggleArticleIdentityDeployments(clientIndex);
            this.clients[clientIndex].updated_deployments = newList;
        },
        getRandomKey(item) {
            return `${item.identity_id}_${item.type}`;
        },
        deploymentsUpdated(e) {
            let client = this.clients[e.index];
            client.deployments = e.deployments;
            this.clients.splice(e.index, 1, client);
        },
        toggleArticleIdentityDeployments(index) {
            let identity = this.clients[index];
            identity.show_collapse_panel = !identity.show_collapse_panel;
            this.clients.splice(index, 1, identity);
        },
        customerUpdated(event) {
            this.clients[event.index].initCustomerId = event.item.id;
            this.clients[event.index].identity_id = event.item.id;
            this.clients[event.index].customer_projects = event.item.projects;
        },

        customerProjectUpdated(event) {
            this.clients[event.index].project_id = event.item.id;
        },

        addStock() {
            let stockCustomer = this.$page.props.customers.find((p) => p.type === "stock");

            this.clients.push({
                projects: [],
                identity_id: stockCustomer.id,
                type: "customer",
                initCustomerId: stockCustomer.id,
                is_stock: true,
                quantity: "",
                sales_price: "",
                description: "",
                price_per_piece_currency_code: "€",
                project_id: 0,
            });
        },
        addNewClient() {
            this.clients.push({
                projects: [],
                identity_id: "",
                type: "customer",
                quantity: "",
                sales_price: "",
                description: "",
                price_per_piece_currency_code: "€",
                project_id: 0,
            });
        },

        addNewStaff() {
            this.clients.push({
                projects: [],
                identity_id: "",
                type: "staff",
                quantity: "",
                sales_price: "",
                description: "",
                price_per_piece_currency_code: "€",
                project_id: 0,
            });
        },
        deleteClient(index) {

            this.$confirm({

                message: this.$t("are_you_sure"),
                button: {
                    no: this.$t("no"),
                    yes: this.$t("yes"),
                },
                callback: (confirm) => {
                    if (confirm) {
                        let clients = this.clients;
                        clients.splice(index, 1);
                        this.clients = clients;
                    }


                },
            });
        },
    },
};
</script>

<style scoped>
</style>
