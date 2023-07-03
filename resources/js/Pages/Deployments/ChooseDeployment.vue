<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("choose_deployment") }}
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
                endpoint="/api/deployments"
                @metaHasBeenUpdated="metaHasBeenUpdated"
            >
                <template #columns>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'name' }"
                        class="datatable__header w-96"
                        scope="col"
                        @click="updateOrderBy('name')"
                    >
                        {{ $t("name") }}
                    </th>
                    <th
                        :class="{ 'bg-gray-100': orderBy === 'a_number' }"
                        class="datatable__header"
                        scope="col"
                        @click="updateOrderBy('a_number')"
                    >
                        {{ $t("a_number") }}
                    </th>
                    <th class="datatable__header" scope="col"/>
                </template>
                <template #row="raw">
                    <td class="px-2 font-medium text-center text-gray-900">
                        {{ raw.name }}
                    </td>
                    <td class="px-2 font-medium text-center text-gray-900">
                        {{ raw.asset.a_number }}
                    </td>
                    <td
                        class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                    >
                        <button
                            v-if="$page.props.target === 'deployment'"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                            @click="insertDeployment($page.props.parentDeployment, $page.props.parentDeploymentSlot, raw)"
                        >
                            {{ $t("insert") }}
                        </button>

                        <inertia-link
                            v-if="$page.props.target === 'slot'"
                            :href="`/deployments/${$page.props.parentDeployment.id}/connectSlot/${$page.props.parentDeploymentSlot.id}/${raw.id}`"
                        >
                            <svg-vue
                                class="page-content__deployments--item-icon"
                                icon="plus"
                            />
                            Choose Slot
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
import axios from "axios";

export default {
    components: {
        Datatable,
        AppLayout
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
            queryParams: '{"withSlots": true}',
        };
    },
    created() {
        if (this.$page.props.target === 'slot') {
            this.queryParams = '{"withSlots": true}';
        }
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
        insertDeployment(parentDeployment, parentDeploymentSlot, targetDeployment) {
            this.$loading.show({delay: 0, background: "#444"});

            axios
                .post(`/api/deployments/${parentDeployment.id}/slots/${parentDeploymentSlot.id}/setParent/${targetDeployment.id}`)
                .then(() => {
                    window.history.back();
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
