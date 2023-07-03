<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("Purchases") }}
            </h2>
        </template>

        <template #screen-actions>
            <inertia-link :href="route('purchase_orders.create')" class="articles__index__create-article">
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
                    :class="{'screen__views-item--active': activeTab === 'not_received'}"
                    class="screen__views-item"
                    @click="changeActiveTab('not_received')"
                >
                    {{ $t("not_received") }} ({{ $page.props.not_received }})
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
                :endpoint="route('api.purchase_orders.index')"
                :query-params="queryParams"
                @metaHasBeenUpdated="metaHasBeenUpdated"
            >
                <template #columns>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'purchase_order_id' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('purchase_order_id')"
                    >
                        {{ $t("number") }}
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
                        :class="{ 'bg-gray-100': orderBy === 'supplier_id' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('supplier_id')"
                    >
                        {{ $t("supplier") }}
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
                        :class="{ 'bg-gray-100': orderBy === 'delivered_quantity' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('delivered_quantity')"
                    >
                        {{ $t("delivered_quantity") }}
                    </th>


                    <th class="datatable__header" scope="col">
                        <div class="flex justify-center items-center">
                            <svg-vue class="datatable__header-icon" icon="truck"/>
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
                        {{ raw.purchase_order.number }}
                    </td>
                    <td class="datatable__cell">
                        {{ raw.created_at }}
                    </td>
                    <td class="pdatatable__cell">
                        {{ raw.purchase_order.supplier ? raw.purchase_order.supplier.name : "" }}
                    </td>
                    <td class="pdatatable__cell">
                        <inertia-link
                            :href="route('purchase_orders.show',raw.purchase_order_id)"
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
                        {{ raw.delivered_quantity }}
                    </td>
                    <td class="datatable__cell">
                        <!--                        <update-delivery-status :purchase-order-product="raw"/>-->
                    </td>
                    <td class="datatable__cell">
                        <update-invoicing-status :url="route('purchase_orders.documents',raw.purchase_order_id)"
                                                 :order-product="raw"
                        />
                    </td>
                    <td class="datatable__cell w-48 flex items-center justify-around">
                        <inertia-link

                            :href="route('purchase_orders.edit',raw.purchase_order_id)"
                            class="articles__index__tb-link"
                        >
                            {{ $t("edit") }}
                        </inertia-link>
                        <inertia-link
                            :href="route('purchase_orders.show',raw.purchase_order_id)"
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
import UpdateDeliveryStatus from "@/Components/Article/UpdateDeliveryStatus";
import UpdateInvoicingStatus from "@/Components/Article/UpdateInvoicingStatus";


export default {
    components: {
        UpdateInvoicingStatus,
        UpdateDeliveryStatus,
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
        params.invoice_type = "purchase";
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
