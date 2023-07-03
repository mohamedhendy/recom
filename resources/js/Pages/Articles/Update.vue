<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("update_invoice") }}
            </h2>
        </template>

        <template #screen-actions>
            <div class="flex items-center justify-between gap-64">
                <button
                    class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                    type="button"
                    @click="deleteInvoice"
                >
                    <svg-vue icon="delete" class="w-5 h-5"/>
                    <span class="text-sm">{{ $t("delete_invoice") }}</span>
                </button>


                <button
                    class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                    type="button"
                    @click="updateInvoice"
                >
                    <svg-vue icon="save" class="w-5 h-5"/>
                    <span class="text-sm">{{ $t("update") }}</span>
                </button>
            </div>
        </template>

        <div class="screen__content">
            <div class="form">
                <div class="flex gap-6">
                    <div class="w-96">
                        <label>{{ $t("supplier") }}</label>

                        <select-with-search
                            :error-message="$page.props.errors.supplier_id"
                            :has-error="$page.props.errors.supplier_id"
                            :init-id="invoiceData.supplier_id"
                            :items="$page.props.suppliers"
                            :label="(item) => `${item.name}`"
                            @item-changed="supplierChanged"
                        />
                    </div>
                    <customInput
                        v-model="invoiceData.issue_date"
                        classes="w-64"
                        :label="$t('issue_date')"
                        :required="false"
                        :errors="$page.props.errors.issue_date"
                        :disabled="$page.props.view_only"
                    />

                    <customInput
                        v-model="invoiceData.due_date"
                        classes="w-64"
                        :label="$t('estimated_delivery_date')"
                        :required="false"
                        :errors="$page.props.errors.due_date"
                        :disabled="$page.props.view_only"
                    />

                    <customInput
                        v-model="invoiceData.internal_id"
                        classes="w-32"
                        :label="$t('internal_id')"
                        :required="false"
                        :errors="$page.props.errors.internal_id"
                        :disabled="$page.props.view_only"
                    />
                </div>

                <articles-list
                    :articles-counter="articlesCount"
                    :edit-mode="true"
                    :init-articles="initArticles"
                    @updated="articlesUpdated"
                />

                <div class="flex items-center justify-end space-x-4">
                    <button
                        class="flex items-center px-4 py-2 mt-8 space-x-4 text-gray-900 border border-gray-900 rounded-full"
                        @click="addArticle"
                    >
                        <svg-vue icon="plus" class="w-5 h-5"/>
                        <span class="text-sm">{{ $t("add_new_article") }}</span>
                    </button>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ArticlesList from "@/Components/Invoice/ArticlesList.vue";
import SelectWithSearch from "@/Components/Reusable/SelectWithSearch";
import ArticleMixin from "@/Pages/Articles/ArticleMixin";
import ClosePageUsingEscMixin from "@/Mixins/ClosePageUsingEscMixin";
import CustomInput from "@/Components/Reusable/CustomInput";

export default {
    components: {
        ArticlesList,
        SelectWithSearch,
        AppLayout,
        CustomInput
    },
    mixins: [ArticleMixin, ClosePageUsingEscMixin],
    data() {
        return {
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

    methods: {


        deleteInvoice() {
            this.$confirm({
                message: this.$t("are_you_sure"),
                button: {
                    no: this.$t("no"),
                    yes: this.$t("yes"),
                },
                /**
                 * Callback Function
                 * @param {Boolean} confirm
                 */
                callback: (confirm) => {
                    if (confirm) {
                        this.performDeleteInvoice();
                    }

                },
            });
        },


        performDeleteInvoice() {
            this.$inertia.delete(`/api/purchases/${this.$page.props.invoice.id}`);
        },

        supplierChanged(event) {
            this.invoiceData.supplier_id = event.item.id;
        },
        addArticle() {
            ++this.articlesCount;
        },
        articlesUpdated(e) {
            this.invoiceData.articles = e.articles;
        },

        async updateInvoice() {
            this.$loading.show({delay: 0, background: "#444"});

            let data = await this.getData();

            this.$inertia.post(`/api/purchases/${this.$page.props.invoice.id}`, data, {
                onFinish: () => {
                    this.$loading.hide();
                },
                onCancelToken: () => { //cancelToken
                },
                onCancel: () => {
                },
                onBefore: () => { //visit
                },
                onStart: () => { //visit
                },
                onProgress: () => { //progress
                },
                onSuccess: () => { //page
                },
            });
        },
    },
};
</script>
