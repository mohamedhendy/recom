<template>
    <app-layout>
        <template #header>
            <!--            <h2 class="screen__title">-->
            <!--                {{ $t("warehouses") }}-->
            <!--            </h2>-->
        </template>

        <template #nav-buttons/>

        <div>
            <div class="space__p12x12">
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

                <div>
                    <h1 class="mb-2 text-xl font-bold my-8">
                        <i class="fa fa-remove"/>
                        {{ $page.props.product.name }}
                    </h1>
                    <div class="grid grid-cols-4 my-3">
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
                                        {{ $t("deployments") }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <Datatable
                    :endpoint="`/api/products/${$page.props.product.id}/deployments`"
                    :query-params="queryParams"
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
                            :class="{ 'bg-gray-100': orderBy === 'a_number' }"
                            class="datatable__header"
                            scope="col"
                            @click="updateOrderBy('a_number')"
                        >
                            {{ $t("a_number") }}
                        </th>
                        <th
                            :class="{ 'bg-gray-100': orderBy === 'serial_number' }"
                            class="datatable__header"
                            scope="col"
                            @click="updateOrderBy('serial_number')"
                        >
                            {{ $t("serial_number") }}
                        </th>

                        <th
                            :class="{ 'bg-gray-100': orderBy === 'description' }"
                            class="datatable__header"
                            scope="col"
                            @click="updateOrderBy('description')"
                        >
                            {{ $t("description") }}
                        </th>
                        <!-- <th
                            :class="{ 'bg-gray-100': orderBy === 'purchase' }"
                            class="datatable__header"
                            scope="col"
                            @click="updateOrderBy('purchase')"
                        >
                            {{ $t("purchase") }}
                        </th> -->

                        <th
                            :class="{ 'bg-gray-100': orderBy === 'status' }"
                            class="datatable__header"
                            scope="col"
                            @click="updateOrderBy('status')"
                        >
                            {{ $t("status") }}
                        </th>
                        <!-- <th
                            :class="{ 'bg-gray-100': orderBy === 'sales' }"
                            class="datatable__header"
                            scope="col"
                            @click="updateOrderBy('sales')"
                        >
                            {{ $t("sales") }}
                        </th> -->
                        <!-- <th
                            :class="{ 'bg-gray-100': orderBy === 'sales' }"
                            class="datatable__header"
                            scope="col"
                        >
                            #
                        </th> -->
                    </template>
                    <template #row="raw">
                        <td class="px-2 font-medium text-center text-gray-900">
                            {{ raw.id }}
                        </td>
                        <td class="px-2 font-medium text-center text-gray-900">
                            {{ raw.created_at }}
                        </td>

                        <td class="px-2 font-medium text-center text-gray-900">
                            {{ raw.serial_number }}
                        </td>
                        <td class="px-2 font-medium text-center text-gray-900">
                            {{ raw.a_number }}
                        </td>

                        <td
                            class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                        >
                            {{ raw.description }}
                        </td>
                        <!-- <td
                            class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                        >
                            <inertia-link v-if="raw.article_identity_id" :href="`/articles/${raw.article_identity_id}`">
                                {{ $t("view") }}  ({{ raw.article_identity_id }})
                            </inertia-link>
                        </td> -->
                        <td
                            class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                        >
                            <el-tag
                                v-if="!raw.sales_article_identity_id"
                                effect="dark"
                                type="success"
                            >
                                In Stock
                            </el-tag>
                            <el-tag v-else effect="dark" type="info">
                                Sold
                            </el-tag>
                        </td>
                        <!-- <td
                            class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                        >
                            <el-button
                                v-if="!raw.sales_article_identity_id"
                                type="success"
                                effect="dark"
                                @click="dispatchDeployment(raw)"
                            >
                                push
                            </el-button>
                        </td> -->
                        <!-- <td
                            class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                        >
                            <inertia-link v-if="raw.sales_article_identity_id" :href="`/sales/${raw.sales_article_identity_id}`">
                                {{ $t("view") }} ({{ raw.sales_article_identity_id }})
                            </inertia-link>
                        </td> -->
                    </template>
                </Datatable>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Datatable from "@/Components/Datatable/Datatable";
import DisplayMoney from "../../Components/DisplayMoney.vue";

export default {
    components: {
        Datatable,
        AppLayout,
        DisplayMoney,
    },
    data() {
        return {
            activeProductWarehouse: null,
            activeWarehouse: null,
            meta: {
                total: 0,
                per_page: 0,
                last_page: 0,
            },
            orderBy: "id",
            orderDirection: "desc",
            activeTab: "all",
            queryParams: "{}",
        };
    },
    methods: {
        dispatchDeployment(deployment) {
            const instance = new BroadcastChannel("deploymentsBroadCastChannel");
            instance.postMessage(JSON.stringify({deployment: deployment, product: this.$page.props.product}));
            this.$message({
                message: "Deployment has been pushed to the invoice",
                type: "success",
            });
        },
        updateOrderBy(cl) {
            if (this.orderBy === cl) {
                this.orderDirection = this.orderDirection === "asc" ? "desc" : "asc";
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

        searchWithKey(e) {
            let params = JSON.parse(this.queryParams);
            params.search = e.target.value;
            params.warehouse_product_id = null;
            params.page = 1;
            this.queryParams = JSON.stringify(params);
        },
    },
};
</script>
