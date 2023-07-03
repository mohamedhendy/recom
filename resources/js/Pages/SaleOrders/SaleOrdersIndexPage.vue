<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("sales") }}
            </h2>
        </template>

        <template #screen-actions>
            <inertia-link
                :href="route('sale_orders.create')"
                class="articles__index__create-article"
            >
                <svg-vue class="w-4 h-4" icon="add"/>
                <span class="text-sm">{{ $t("new_order") }}</span>
            </inertia-link>
        </template>

        <div class="screen__options">
            <div class="screen__views">
                <button
                    :class="{'screen__views-item--active': activeTab === 'all'}"
                    class="screen__views-item"
                    @click="changeActiveTab('all')"
                >
                    {{ $t("all") }}
                </button>
                <button
                    :class="{'screen__views-item--active': activeTab === 'not_billed'}"
                    class="screen__views-item"
                    @click="changeActiveTab('not_billed')"
                >
                    {{ $t("not_billed") }} ({{ $page.props.not_billed }})
                </button>
            </div>

            <div class="screen__statistics">
                <div>({{ meta.total }} {{ $t("total") }})</div>
                <div>({{ meta.per_page }} {{ $t("per_page") }})</div>
                <div>({{ meta.last_page }} {{ $t("pages") }})</div>
            </div>

            <label class="screen__search">
                <input
                    class="screen__search-input"
                    :placeholder="$t('search')"
                    type="text"
                    @change="searchWithKey"
                >
            </label>
        </div>

        <div class="screen__content">
            <Datatable
                :endpoint="route('api.sale_orders.index')"
                :query-params="queryParams"
                @metaHasBeenUpdated="metaHasBeenUpdated"
            >
                <template #columns>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'sale_order_id' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('sale_order_id')"
                    >
                        {{ $t("sale_order") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'created_at' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('created_at')"
                    >
                        {{ $t("created") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'identity_id' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('identity_id')"
                    >
                        {{ $t("customer") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'name' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('name')"
                    >
                        {{ $t("product") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'price' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('price')"
                    >
                        {{ $t("price") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'quantity' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('quantity')"
                    >
                        {{ $t("quantity") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'billed_quantity' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('billed_quantity')"
                    >
                        {{ $t("billed_quantity") }}
                    </th>


                    <th class="datatable__header" scope="col">
                        <div class="flex justify-center items-center">
                            <svg-vue icon="dollarbill" class="datatable__header-icon"/>
                        </div>
                    </th>
                    <th class="datatable__header" scope="col">
                        <div class="flex justify-center items-center">
                            <svg-vue icon="clipboard" class="datatable__header-icon"/>
                        </div>
                    </th>
                    <th class="datatable__header" scope="col">
                        #
                    </th>
                </template>
                <template #row="raw">
                    <td class="datatable__cell">
                        {{ raw.sale_order.number }}
                    </td>
                    <td class="datatable__cell">
                        {{ raw.created_at }}
                    </td>
                    <td class="pdatatable__cell">
                        {{ raw.sale_order.identity ? raw.sale_order.identity.name : "" }}
                    </td>
                    <td class="pdatatable__cell">
                        <inertia-link
                            :href="route('sale_orders.show',raw.sale_order_id)"
                            class="block py-2 hover:text-indigo-900"
                        >
                            {{ raw.product ? raw.product.name : "" }}
                        </inertia-link>
                    </td>
                    <td class="datatable__cell">
                        <display-money :money="raw.price"/>
                    </td>
                    <td class="datatable__cell">
                        {{ raw.quantity }}
                    </td>
                    <td class="datatable__cell">
                        {{ raw.billed_quantity }}
                    </td>
                    <td class="datatable__cell">
                        <update-billing-status :sale-order-product="raw"/>
                    </td>
                    <td class="datatable__cell">
                        <update-invoicing-status :url="route('documents.update_sale_order_product',raw.id)" :order-product="raw"/>
                    </td>
                    <td class="datatable__cell w-48 flex items-center justify-around">
                        <inertia-link
                            v-if="raw.billed_quantity === 0"
                            :href="route('sale_orders.edit',raw.sale_order_id)"
                            class="articles__index__tb-link"
                        >
                            {{ $t("edit") }}
                        </inertia-link>
                        <inertia-link
                            :href="route('sale_orders.show',raw.sale_order_id)"
                            class="block py-2 hover:text-indigo-900"
                        >
                            {{ $t("view") }}
                        </inertia-link>
                    </td>
                </template>
            </Datatable>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Datatable from "@/Components/Datatable/Datatable";
import DisplayMoney from "@/Components/DisplayMoney";
import UpdateBillingStatus from "@/Components/Article/UpdateBillingStatus";
import UpdateInvoicingStatus from "@/Components/Article/UpdateInvoicingStatus";


export default {
    components: {
        UpdateInvoicingStatus,
        UpdateBillingStatus,
        DisplayMoney,
        Datatable,
        AppLayout
    },
    data() {
        return {
            meta: {
                total: 0,
                per_page: 0,
                last_page: 0
            },
            orderBy: "id",
            orderDirection: "desc",
            activeTab:
                localStorage.getItem("articles_status_table_active_tab") ??
                "all",
            queryParams: '{}'
        };
    },
    created() {
        let params = JSON.parse(this.queryParams);
        params.active_page = 'sales_page';
        params.orderDirection = this.orderDirection;
        params.orderBy = this.orderBy;
        params.status = this.activeTab;
        this.queryParams = JSON.stringify(params);
    },
    methods: {
        updateOrderBy(cl) {
            if (this.orderBy === cl) {
                this.orderDirection =
                    this.orderDirection === "asc" ? "desc" : "asc";
            }
            this.orderBy = cl;
            let params = JSON.parse(this.queryParams);
            params.orderDirection = this.orderDirection;
            params.orderBy = this.orderBy;
            params.status = this.activeTab;
            this.queryParams = JSON.stringify(params);
        },

        metaHasBeenUpdated(e) {
            this.meta = e.meta;
        },
        changeActiveTab(activeTab) {
            localStorage.setItem("articles_status_table_active_tab", activeTab);
            this.activeTab = activeTab;
            let params = JSON.parse(this.queryParams);
            params.status = this.activeTab;
            params.page = 1;
            this.queryParams = JSON.stringify(params);
        },
        searchWithKey(e) {
            let params = JSON.parse(this.queryParams);
            params.search = e.target.value;
            params.page = 1;
            this.queryParams = JSON.stringify(params);
        },
        checkVisibility(articleChild) {
            if (this.activeTab === "all") return true;

            if (this.activeTab === "not_billed") {
                return (
                    parseInt(articleChild.quantity) >
                    parseInt(articleChild.billed_quantity)
                );
            }

            if (this.activeTab === "not_received") {
                return (
                    parseInt(articleChild.quantity) >
                    parseInt(articleChild.delivered_quantity)
                );
            }

            return true;
        }
    }
};
</script>
