<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("inventory") }}
            </h2>
        </template>

        <template #screen-actions>
            <a
                :href="route('api.inventoryPdf')"
                class="articles__index__create-article"
            >
                Export to PDF
            </a>

            <inertia-link
                :href="route('inventories.create')"
                class="articles__index__create-article"
            >
                <svg-vue class="w-4 h-4" icon="add"/>
                <span class="text-sm">{{ $t("new_order") }}</span>
            </inertia-link>
        </template>

        <div class="screen__options">
            <div class="screen__views"/>

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
                :endpoint="route('api.inventories.index')"
                :query-params="queryParams"
                @metaHasBeenUpdated="metaHasBeenUpdated"
            >
                <template #columns>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'inventory_id' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('inventory_id')"
                    >
                        {{ $t("inventory_id") }}
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
                        :class="{ 'bg-gray-100': orderBy === 'name' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('name')"
                    >
                        {{ $t("product") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'unit_cost' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('unit_cost')"
                    >
                        {{ $t("unit_cost") }}
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
                        :class="{ 'bg-gray-100': orderBy === 'quantity' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('transaction')"
                    >
                        {{ $t("transaction") }}
                    </th>
                    <th class="datatable__header" scope="col">
                        #
                    </th>
                </template>
                <template #row="raw">
                    <td class="datatable__cell">
                        {{ raw.inventory.id }}
                    </td>
                    <td class="datatable__cell">
                        {{ raw.created_at }}
                    </td>
                    <td class="pdatatable__cell">
                        <inertia-link
                            :href="route('inventories.show',raw.inventory_id)"
                            class="block py-2 hover:text-indigo-900"
                        >
                            {{ raw.product.name }}
                        </inertia-link>
                    </td>
                    <td class="datatable__cell">
                        <display-money :money="raw.unit_cost"/>
                    </td>
                    <td class="datatable__cell">
                        {{ raw.quantity }}
                    </td>
                    <td class="datatable__cell">
                        {{ raw.transaction }}
                    </td>
                    <td class="datatable__cell w-48 flex items-center justify-around">
                        <inertia-link
                            :href="route('inventories.show',raw.inventory_id)"
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


export default {
    name: "InventoryAdjustingIndexPage",
    components: {
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
