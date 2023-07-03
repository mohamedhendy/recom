<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                <!-- {{ $t("update_delivery_status") }} -->
                {{ $t("billing") }} #{{ $page.props.article.name }}
            </h2>
        </template>

        <template #screen-actions>
            <button
                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                type="button"
                @click="saveBilling(false)"
            >
                <svg-vue icon="save" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("push_to_easy_bill") }}</span>
            </button>
            <button
                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                type="button"
                @click="saveBilling(true)"
            >
                <svg-vue icon="save" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("mark_as_billed") }}</span>
            </button>
        </template>

        <div class="screen__content">
            <div class="form">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div
                            class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
                        >
                            <div class="overflow-hidden">
                                <table class="min-w-full text-center divide-y divide-gray-200">
                                    <thead>
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-center text-gray-800 uppercase"
                                                scope="col"
                                            >
                                                {{ $t("id") }}
                                            </th>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-center text-gray-800 uppercase"
                                                scope="col"
                                            >
                                                {{ $t("identity_client") }} / {{ $t("identity_staff") }}
                                            </th>
                                            <th
                                                class="px-6 py-3 text-xs font-medium tracking-wider text-left text-center text-gray-800 uppercase"
                                                scope="col"
                                            >
                                                {{ $t("created") }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        v-for="(customer, index) in articleCustomers"
                                        :key="index"
                                        class="bg-white divide-y divide-gray-200"
                                    >
                                        <tr>
                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                            >
                                                {{ customer.identity.id }}
                                            </td>
                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                            >
                                                {{ customer.identity.name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                            >
                                                {{ customer.created_at }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td colspan="3">
                                                <div class="w-full min-w-full mt-5">
                                                    <table class="min-w-full text-center">
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-center text-gray-800 uppercase"
                                                                    scope="col"
                                                                >
                                                                    {{ $t("a_number") }}
                                                                </th>
                                                                <th
                                                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-center text-gray-800 uppercase"
                                                                    scope="col"
                                                                >
                                                                    {{ $t("serial_number") }}
                                                                </th>
                                                                <th
                                                                    class="px-6 py-3 text-xs font-medium tracking-wider text-left text-center text-gray-800 uppercase"
                                                                    scope="col"
                                                                >
                                                                    {{ $t("comment") }}
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr
                                                                v-for="(
                                                                    deployment, deploymentIndex
                                                                ) in customer.deployments"
                                                                :key="deploymentIndex"
                                                            >
                                                                <td
                                                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                                                >
                                                                    <input
                                                                        v-model="deployment.a_number"
                                                                        :class="{
                                                                            'border-red-500':
                                                                                $page.props.errors[
                                                                                    `customers.${index}.deployments.${deploymentIndex}.a_number`
                                                                                ],
                                                                        }"
                                                                        class="w-56"
                                                                        type="text"
                                                                    >
                                                                    <ErrorMessage :error="$page.props.errors[`customers.${index}.deployments.${deploymentIndex}.a_number`]"/>
                                                                </td>
                                                                <td
                                                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                                                >
                                                                    <input
                                                                        v-model="deployment.serial_number"
                                                                        :class="{
                                                                            'border-red-500':
                                                                                $page.props.errors[
                                                                                    `customers.${index}.deployments.${deploymentIndex}.serial_number`
                                                                                ],
                                                                        }"
                                                                        class="w-56"
                                                                        type="text"
                                                                    >
                                                                    <ErrorMessage :error="$page.props.errors[`customers.${index}.deployments.${deploymentIndex}.serial_number`]"/>
                                                                </td>
                                                                <td
                                                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap"
                                                                >
                                                                    <input
                                                                        v-model="deployment.description"
                                                                        :class="{
                                                                            'border-red-500':
                                                                                $page.props.errors[
                                                                                    `customers.${index}.deployments.${deploymentIndex}.description`
                                                                                ],
                                                                        }"
                                                                        class="w-56"
                                                                        type="text"
                                                                    >
                                                                    <ErrorMessage :error="$page.props.errors[`customers.${index}.deployments.${deploymentIndex}.description`]"/>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12">
                    <h3 class="text-xl font-medium text-gray-900">
                        {{ $t("easy_bill") }}
                    </h3>

                    <div class="mt-8">
                        <select-with-search
                            :error-message="$page.props.errors.draft_invoice_id"
                            :has-error="$page.props.errors.draft_invoice_id"
                            :items="easybillInvoices"
                            :label="(item) => `${item.id} - ${item.title}`"
                            @item-changed="easyBillInvoiceUpdated"
                        >
                            <!--                        -->
                            <template #label="props">
                                {{ draftInvoiceTitle(props.item) }}
                            </template>
                        </select-with-search>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import axios from 'axios';
import AppLayout from "@/Layouts/AppLayout";
import SelectWithSearch from "@/Components/Reusable/SelectWithSearch";
import ClosePageUsingEscMixin from "@/Mixins/ClosePageUsingEscMixin";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    components: {
        SelectWithSearch,
        AppLayout,
        ErrorMessage
    },
    mixins: [ClosePageUsingEscMixin],
    data() {
        return {
            easybillInvoices: [],
            easybillInvoiceId: "",
            articleCustomers: this.$page.props.identities,
        };
    },
    created() {
        this.loadEasyBillItems();
    },
    methods: {
        easyBillInvoiceUpdated(e) {
            this.easybillInvoiceId = e.item.id;
        },
        loadEasyBillItems() {
            this.$loading.show({delay: 0, background: "#444"});

            axios
                .get("/api/billing")
                .then((res) => {
                    this.easybillInvoices = res.data.data;
                })
                .finally(() => {
                    this.$loading.hide();
                });
        },
        draftInvoiceTitle(item) {
            let label = "";

            label += "Invoice ID: " + item.id;
            label +=
                " - Client: " +
                (item.address.hasOwnProperty("title") ? item.address.title : "n/a");
            label +=
                " ( " +
                (item.address.hasOwnProperty("first_name")
                    ? item.address.first_name
                    : "n/a") +
                " )";
            label += " - Title: " + (item.title != null ? item.title : "n/a");

            return label;
        },
        saveBilling(markAsBilled = false) {
            let url = "/api/billing";

            if (markAsBilled) {
                url += "?mask_as_billed=true";
            }

            this.$confirm({
                message: this.$t("are_you_sure"),
                button: {
                    no: this.$t("no"),
                    yes: this.$t("yes"),
                },
                callback: (confirm) => {
                    if (confirm) {
                        this.$loading.show({delay: 0, background: "#444"});

                        // markAsBilled
                        this.$inertia.post(
                            url,
                            {
                                customers: this.articleCustomers,
                                draft_invoice_id: this.easybillInvoiceId,
                            },
                            {
                                onFinish: () => {
                                    this.$loading.hide();
                                },
                            }
                        );
                        //
                    }
                },
            });
        },
    },
};
</script>
