<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                <template v-if="!viewOnly">
                    {{ $t("update_invoice") }}
                </template>
                <template v-else>
                    {{ $t("view_invoice") }}
                </template>
            </h2>
        </template>

        <template #screen-actions>
            <inertia-link
                :href="`/articles/${$page.props.invoice.id}/edit`"
                class="articles__index__create-article"
            >
                <span class="text-sm">{{ $t("update_invoice") }}</span>
            </inertia-link>
        </template>

        <div class="screen__content">
            <div class="py-12 pr-6">
                <div class="flex gap-6">
                    <div class="w-96">
                        <select-with-search
                            :error-message="$page.props.errors.supplier_id"
                            :has-error="$page.props.errors.supplier_id"
                            :init-id="invoiceData.supplier_id"
                            :is-disabled="viewOnly"
                            :items="$page.props.suppliers"
                            :label="(item) => `${item.name}`"
                        >
                            <template #label="props">
                                {{ props.item.name }} - {{ props.item.name }}
                            </template>
                        </select-with-search>
                    </div>
                    <div class="w-64">
                        <input
                            v-model="invoiceData.issue_date"
                            :class="{ 'border-red-500': $page.props.errors.issue_date }"
                            :disabled="viewOnly"
                            class="w-full"
                            type="date"
                        >
                        <ErrorMessage :error="$page.props.errors.issue_date"/>
                    </div>
                    <div class="w-64">
                        <input
                            v-model="invoiceData.due_date"
                            :class="{ 'border-red-500': $page.props.errors.due_date }"
                            :disabled="viewOnly"
                            class="w-full"
                            type="date"
                        >
                        <ErrorMessage :error="$page.props.errors.due_date"/>
                    </div>

                    <div class="w-32">
                        <input
                            v-model="invoiceData.internal_id"
                            :class="{ 'border-red-500': $page.props.errors.internal_id }"
                            :disabled="viewOnly"
                            :placeholder="$t('internal_id')"
                            class="w-full"
                            type="text"
                        >
                        <ErrorMessage :error="$page.props.errors.internal_id"/>
                    </div>
                </div>

                <!-- <articles-list
            :show-deployments="false"
            :articles-counter="articlesCount"
            @updated="articlesUpdated"
          ></articles-list> -->
                <articles-list
                    :articles-counter="articlesCount"
                    :edit-mode="true"
                    :init-articles="initArticles"
                    :view-only="viewOnly"
                />

                <div class="flex items-center justify-end space-x-4"/>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import SelectWithSearch from "@/Components/Reusable/SelectWithSearch";
import ArticleMixin from "@/Pages/Articles/ArticleMixin";
import ClosePageUsingEscMixin from "@/Mixins/ClosePageUsingEscMixin";
import ArticlesList from "../../Components/Invoice/ArticlesList.vue";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    components: {
        ArticlesList,
        SelectWithSearch,
        AppLayout,
        ErrorMessage
    },
    mixins: [ArticleMixin, ClosePageUsingEscMixin],
    data() {
        return {
            viewOnly: true,
            articlesCount: 0,
            initArticles: this.$page.props.invoice.articles,
            invoiceData: {
                internal_id: this.$page.props.invoice.internal_id,
                supplier_id: this.$page.props.invoice.supplier_id,
                issue_date: this.$page.props.invoice.issue_date,
                due_date: this.$page.props.invoice.due_date,
                articles: [],
            },
        };
    },
};
</script>
