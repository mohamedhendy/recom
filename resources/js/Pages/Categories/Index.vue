<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("categories") }}
            </h2>
        </template>

        <template #screen-actions>
            <inertia-link
                class="flex items-center px-4 py-2 space-x-3 text-white bg-gray-900 rounded-full hover:bg-gray-800"
                href="/categories/create"
            >
                <svg-vue icon="add" class="w-4 h-4"/>
                <span class="text-sm">{{ $t("new_category") }}</span>
            </inertia-link>
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

        <Datatable
            :query-params="queryParams"
            endpoint="/api/categories"
            @metaHasBeenUpdated="metaHasBeenUpdated"
        >
            <template #columns>
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
                    {{ $t("name") }}
                </th>

                <th
                    :class="{ 'bg-gray-100': orderBy === 'parent_name' }"
                    class="datatable__header"
                    scope="col"
                    @click="updateOrderBy('parent_name')"
                >
                    {{ $t("parent_category") }}
                </th>

                <th class="datatable__header" scope="col"/>
            </template>
            <template #row="raw">
                <td class="px-2 font-medium text-center text-gray-900">
                    {{ raw.created_at }}
                </td>
                <td class="px-2 font-medium text-center text-gray-900">
                    {{ raw.name }}
                </td>
                <td class="px-2 font-medium text-center text-gray-900">
                    {{ raw.parent_category ? raw.parent_category.name : "" }}
                </td>
                <td
                    class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap"
                >
                    <inertia-link
                        :href="`/categories/${raw.id}/edit`"
                        class="block py-2 hover:text-indigo-900"
                    >
                        {{ $t("edit") }}
                    </inertia-link>
                </td>
            </template>
        </Datatable>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Datatable from "@/Components/Datatable/Datatable";

export default {
    components: {
        Datatable,
        AppLayout,
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
            queryParams: "{}",
        };
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
    },
};
</script>
