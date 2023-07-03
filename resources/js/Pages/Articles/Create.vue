<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("order_new_articles") }}
            </h2>
        </template>

        <template #screen-actions>
            <button
                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                type="button"
                @click="saveInvoice"
            >
                <svg-vue icon="save" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("save") }}</span>
            </button>
        </template>

        <div class="screen__content">
            <div class="form">
                <div class="flex gap-6">
                    <div class="w-96">
                        <label>{{ $t("supplier") }}</label>
                        <select-with-search
                            :error-message="$page.props.errors.supplier_id"
                            :has-error="$page.props.errors.supplier_id"
                            :items="$page.props.suppliers"
                            :placeholder="$t('supplier')"
                            :label="(item) => `${item.number} - ${item.name}`"
                            @item-changed="supplierChanged"
                        >
                            <template #label="props">
                                {{ props.item.number }} - {{ props.item.name }}
                            </template>
                        </select-with-search>
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

                    <div class="w-32">
                        <label>{{ $t("invoice_year") }}</label>
                        <select-with-search
                            :error-message="$page.props.errors.invoice_year"
                            :has-error="$page.props.errors.invoice_year"
                            :init-id="invoiceData.invoice_year"
                            :items="[
                                { id: '2020', name: '2020' },
                                { id: '2021', name: '2021' },
                            ]"
                            :placeholder="$t('invoice_year')"
                            :label="(item) => `${item.name}`"
                            @item-changed="invoiceYearHasChanged"
                        >
                            <template #label="props">
                                {{ props.item.name }}
                            </template>
                        </select-with-search>

                        <ErrorMessage :error="$page.props.errors.invoice_year"/>
                    </div>
                </div>

                <articles-list
                    :articles-counter="articlesCount"
                    :show-deployments="false"
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
import SelectWithSearch from "@/Components/Reusable/SelectWithSearch";
import ArticleMixin from "@/Pages/Articles/ArticleMixin";
import ClosePageUsingEscMixin from "@/Mixins/ClosePageUsingEscMixin";
import ArticlesList from "@/Components/Invoice/ArticlesList.vue";
import CustomInput from "@/Components/Reusable/CustomInput";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    components: {
        SelectWithSearch,
        AppLayout,
        ArticlesList,
        CustomInput,
        ErrorMessage
    },
    mixins: [ArticleMixin, ClosePageUsingEscMixin],
    data() {
        return {
            closing: false,
            articlesCount: 0,
            invoiceData: {
                invoice_year: "2021",
                internal_id: "",
                supplier_id: "",
                issue_date: new Date().toISOString().substr(0, 10),
                due_date: new Date(new Date().setDate(new Date().getDate() + 3))
                    .toISOString()
                    .substr(0, 10),
                products: [],
            },
        };
    },

    created() {
        let date = new Date();
        date.setDate(date.getDate() + 3);
        this.due_date = date.toISOString().substr(0, 10);
    },
    methods: {
        supplierChanged(event) {
            this.invoiceData.supplier_id = event.item.id;
        },

        invoiceYearHasChanged(event) {
            this.invoiceData.invoice_year = event.item.id;
        },
        addArticle() {
            ++this.articlesCount;
        },
        articlesUpdated(e) {
            this.invoiceData.articles = e.articles;
        },
        async saveInvoice() {
            this.$loading.show({delay: 0, background: "#444"});

            let data = await this.getData();

            this.$inertia.post("/api/purchases", data, {
                onFinish: () => {
                    this.$loading.hide();
                },
            });
        },
    },
};
</script>
