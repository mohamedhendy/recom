<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("Transaction details") }}
            </h2>
        </template>

        <template #screen-actions>
            <a :href="route('api.inventoryPdf')" class="articles__index__create-article">
                Export to PDF
            </a>

            <inertia-link :href="route('inventories.create')" class="articles__index__create-article">
                <svg-vue class="w-4 h-4" icon="add"/>
                <span class="text-sm">{{ $t("new_order") }}</span>
            </inertia-link>
        </template>

        <div class="screen__options">
            <div class="box__flex box__flex__center-between pr-8">
                <div class="space__x__8 box__flex"/>


                <label class="block">
                    <input
                        class="block w-full px-0.5 border-0 border-b-2 border-gray-400 placeholder-gray-400 focus:placeholder-gray-900 focus:ring-0 focus:border-black"
                        :placeholder="$t('search')"
                        type="text"
                        @change="searchWithKey"
                    >
                </label>
            </div>

            <template v-if="activeProductWarehouse">
                <h1 class="mb-2 text-xl font-bold my-8">
                    <i class="fa fa-remove"/>
                    {{
                        activeProductWarehouse.product
                            ? activeProductWarehouse.product.name
                            : ""
                    }}
                </h1>
                <div class="w-full grid grid-cols-4 my-3">
                    <div class="w-full px-6 shadow">
                        <div
                            class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white"
                        >
                            <div class="p-3 rounded-full bg-yellow-600 bg-opacity-75"/>

                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">
                                    {{ meta.total }}
                                </h4>
                                <div class="text-gray-500">
                                    {{ $t("transaction") }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full px-6 shadow">
                        <div
                            class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white"
                        >
                            <div class="p-3 rounded-full bg-yellow-600 bg-opacity-75"/>

                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">
                                    {{ meta.available_qty }}
                                </h4>
                                <div class="text-gray-500">
                                    {{ $t("quantity") }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full px-6 shadow">
                        <div
                            class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white"
                        >
                            <div class="p-3 rounded-full bg-yellow-600 bg-opacity-75"/>

                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">
                                    <display-money :money="meta.unit_cost"/>
                                </h4>
                                <div class="text-gray-500">
                                    {{ $t("cost_price") }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full px-6 shadow">
                        <div
                            class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white"
                        >
                            <div class="p-3 rounded-full bg-yellow-600 bg-opacity-75"/>

                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">
                                    <display-money :money="meta.stock_amount"/>
                                </h4>
                                <div class="text-gray-500">
                                    {{ $t("stock_amount") }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template v-else>
                <div class="w-full grid grid-cols-3 my-3">
                    <div class="w-full px-6 shadow">
                        <div
                            class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white"
                        >
                            <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75"/>

                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">
                                    {{ meta.total }}
                                </h4>
                                <div class="text-gray-500">
                                    {{ $t("transaction") }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full px-6 shadow">
                        <div
                            class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white"
                        >
                            <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75"/>

                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">
                                    {{ meta.products_count }}
                                </h4>
                                <div class="text-gray-500">
                                    {{ $t("product") }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full px-6 shadow">
                        <div
                            class="flex items-center px-5 py-6 shadow-sm rounded-md bg-white"
                        >
                            <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75"/>

                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">
                                    <display-money :money="meta.stock_amount"/>
                                </h4>
                                <div class="text-gray-500">
                                    {{ $t("total_stock_amount") }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="screen__content">
            <Datatable
                :query-params="queryParams"
                endpoint="/api/warehouse_transactions"
                @metaHasBeenUpdated="metaHasBeenUpdated"
            >
                <template #columns>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'id' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('id')"
                    >
                        {{ $t("id") }}
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
                        :class="{ 'bg-gray-100': orderBy === 'product_name' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('product_name')"
                    >
                        {{ $t("product_name") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'warehouse_name' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('warehouse_name')"
                    >
                        {{ $t("warehouse_name") }}
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
                        :class="{ 'bg-gray-100': orderBy === 'transaction_type' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('transaction_type')"
                    >
                        {{ $t("transaction_type") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'available_qty' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('available_qty')"
                    >
                        {{ $t("available_qty") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'unit_cost' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('unit_cost')"
                    >
                        {{ $t("cost_price") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'stock_amount' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('stock_amount')"
                    >
                        {{ $t("stock_amount") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'stock_amount' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('view')"
                    >
                        {{ $t("transactions") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'deployments' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('deployments')"
                    >
                        {{ $t("deployments") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'stock_amount' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('view')"
                    >
                        {{ $t("view") }}
                    </th>
                </template>
                <template #row="raw">
                    <td class="px-2 font-medium text-center text-gray-900">
                        {{ raw.id }}
                    </td>
                    <td class="px-2 font-medium text-center text-gray-900">
                        {{ raw.created_at }}
                    </td>

                    <td class="px-2 font-medium text-center text-gray-900">
                        <a href="#" @click="setActiveProductWarehouse(raw.warehouse_product)">
                            {{
                                raw.warehouse_product.product
                                    ? raw.warehouse_product.product.name
                                    : ""
                            }}</a>
                    </td>
                    <td class="px-2 font-medium text-center text-gray-900">
                        {{
                            raw.warehouse_product.warehouse
                                ? raw.warehouse_product.warehouse.name
                                : ""
                        }}
                    </td>

                    <td
                        class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                    >
                        {{ raw.quantity }}
                    </td>
                    <td
                        class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                    >
                        {{ raw.transaction_type }}
                    </td>

                    <td
                        class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                    >
                        {{ raw.available_qty }}
                    </td>
                    <td
                        class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                    >
                        <display-money :money="raw.unit_cost"/>
                    </td>
                    <td
                        class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                    >
                        <display-money :money="raw.stock_amount"/>
                    </td>
                    <td
                        class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                    >
                        <a href="#" @click="setActiveProductWarehouse(raw.warehouse_product)">
                            {{ $t("transactions") }}</a>
                    </td>
                    <td
                        class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                    >
                        <a :href="`/products/${raw.warehouse_product.product.id}/deployments`">
                            {{ $t("deployments") }}</a>
                    </td>
                    <td
                        class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                    >
                        <a :href="`/products/${raw.warehouse_product.product.id}`">
                            {{ $t("view") }}</a>
                    </td>
                </template>
            </Datatable>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Datatable from "@/Components/Datatable/Datatable";
import DisplayMoney from '../../Components/DisplayMoney.vue';

export default {
    components: {
        Datatable,
        AppLayout,
        DisplayMoney
    },
    data() {
        return {
            activeProductWarehouse: null,
            activeWarehouse: null,
            meta: {
                total: 0,
                per_page: 0,
                last_page: 0
            },
            orderBy: "id",
            orderDirection: "desc",
            activeTab: "all",
            queryParams: "{}"
        };
    },
    methods: {
        setActiveProductWarehouse(product) {
            this.activeProductWarehouse = product;
            let params = JSON.parse(this.queryParams);
            params.warehouse_product_id = product ? product.id : null;
            params.page = 1;
            this.queryParams = JSON.stringify(params);
        },
        setActiveWarehouse(warehouse) {
            this.activeWarehouse = warehouse;


        },
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
            this.activeTab = activeTab;
            let params = JSON.parse(this.queryParams);
            params.status = this.activeTab;
            params.page = 1;
            this.queryParams = JSON.stringify(params);
        },
        searchWithKey(e) {
            let params = JSON.parse(this.queryParams);
            params.search = e.target.value;
            params.warehouse_product_id = null;
            params.page = 1;
            this.queryParams = JSON.stringify(params);
        },
    }
};
</script>
