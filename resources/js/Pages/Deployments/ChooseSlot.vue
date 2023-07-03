<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("choose_slot") }}
            </h2>
        </template>

        <div class="screen__options">
            <div/>
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
                :query-params="queryParams"
                :endpoint="`/api/deployments/${$page.props.targetDeployment.id}/slots`"
                @metaHasBeenUpdated="metaHasBeenUpdated"
            >
                <template #columns>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'id' }"
                        class="datatable__header w-96"
                        scope="col"
                        @click="updateOrderBy('id')"
                    >
                        {{ $t("id") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'name' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('name')"
                    >
                        {{ $t("name") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'info' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('info')"
                    >
                        {{ $t("info") }}
                    </th>
                    <th class="datatable__header" scope="col"/>
                </template>
                <template #row="raw">
                    <td class="px-2 font-medium text-center text-gray-900">
                        {{ raw.productSlot.id }}
                    </td>
                    <td class="px-2 font-medium text-center text-gray-900">
                        {{ raw.productSlot.name }}
                    </td>
                    <td class="px-2 font-medium text-center text-gray-900">
                        <json-input
                            :json="raw.info"
                        />
                    </td>
                    <td
                        class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                    >
                        <button
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                            @click="connectSlots($page.props.parentDeployment, $page.props.parentDeploymentSlot, $page.props.targetDeployment, raw)"
                        >
                            Connect Slots
                        </button>
                    </td>
                </template>
            </Datatable>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Datatable from "@/Components/Datatable/Datatable";
import JsonInput from "@/Components/Reusable/JsonInput";

export default {
    components: {
        Datatable,
        AppLayout,
        JsonInput
    },
    data() {
        return {
            meta: {
                total: 0,
                per_page: 0,
                last_page: 0,
            },
            orderBy: "id",
            orderDirection: "desc",
            activeTab: "all",
            queryParams: '{"slotable": true}',
        };
    },
    methods: {
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
            params.page = 1;
            this.queryParams = JSON.stringify(params);
        },
        connectSlots(parentDeployment, parentDeploymentSlot, targetDeployment, targetDeploymentSlot) {
            this.$loading.show({delay: 0, background: "#444"});

            this.$inertia
                .post(`/api/deployments/${parentDeployment.id}/slots/${parentDeploymentSlot.id}/connect/${targetDeployment.id}/${targetDeploymentSlot.id}`)
                .then(() => {
                })
                .catch(function (error) {
                    alert(error.response.data.message);
                    console.log(error.response.data);
                })
                .finally(() => {
                    this.$loading.hide();
                });
        },
    },
};
</script>
