<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("update_delivery_status") }}
            </h2>
        </template>

        <template #screen-actions>
            <button
                class="flex items-center px-4 py-2  space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                type="button"
                @click="updateDeliveryStatus"
            >
                <svg-vue icon="save" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("save") }}</span>
            </button>
        </template>

        <ErrorMessage :error="$page.props.errors[`invoice`]"/>

        <div class="flex justify-between p-3 px-6 mt-3 mb-3 text-center bg-gray-100 border">
            <div class="w-1/12 font-medium"/>
            <div class="w-6/12 font-medium text-left">
                {{ $t("article_name") }}
            </div>
            <div class="w-1/12 font-medium">
                {{ $t("quantity") }}
            </div>
            <div class="w-1/12 font-medium">
                {{ $t("delivered") }}
            </div>
            <div class="w-1/12 font-medium text-center"/>
        </div>

        <div
            v-for="(article, index) in articles"
            :key="`active_artciles.${article.id}_${index}`"
            class="my-2"
        >
            <div
                v-if="article.id === $page.props.articleIdentity.article_id"
                class="border-b"
            >
                <div class="flex justify-between p-3 px-6 text-center">
                    <div
                        class="flex items-center justify-center w-1/12 font-medium text-center"
                    />
                    <div class="w-6/12 font-medium text-left">
                        {{ article.name }}
                    </div>
                    <div class="w-1/12 font-medium">
                        {{ article.quantity }}
                    </div>
                    <div class="w-1/12 font-medium">
                        {{ getTotalArticleDeliveredQuantity(article) }}
                    </div>

                    <div class="w-1/12 font-medium text-center"/>
                </div>
                <div
                    v-for="(customer, customerIndex) in article.article_identities"
                    :key="customerIndex"
                >
                    <div class="flex justify-between px-6 text-center">
                        <div class="w-1/12 font-medium">
                            <vue-number-input
                                v-model="customer.received_quantity"
                                :center="true"
                                :class="{
                                    'border-red-500':
                                        $page.props.errors[
                                            `active_artciles.${index}.article_identities.${customerIndex}.received_quantity`
                                        ],
                                }"
                                :disabled="customer.disable_assign_field"
                                :max="
                                    parseInt(customer.quantity) -
                                        parseInt(customer.delivered_quantity)
                                "
                                :min="0"
                                controls
                                inline
                                size="small"
                                @change="updateArticles"
                            />
                        </div>
                        <div class="w-6/12 font-medium text-left">
                            {{ customer.identity.name }}
                        </div>
                        <div class="w-1/12 font-medium">
                            {{ customer.quantity }}
                        </div>
                        <div class="w-1/12 font-medium">
                            {{ customer.delivered_quantity }}
                        </div>

                        <div class="w-1/12 font-medium text-center">
                            <button
                                @click="toggleArticleIdentityDeployments(index, customerIndex)"
                            >
                                <svg-vue icon="right" class="w-6 h-6"/>
                            </button>
                        </div>
                    </div>
                    <div
                        v-if="customer.show_collapse_panel"
                        class="px-10 pt-2 pb-2 mb-5 bg-white"
                    >
                        <div class="mt-2">
                            <table class="min-w-full text-center">
                                <thead>
                                    <tr>
                                        <td class="font-medium text-gray-900 p-2">
                                            {{ $t("a_number") }}
                                        </td>
                                        <td class="font-medium text-gray-900 p-2">
                                            {{ $t("serial_number") }}
                                        </td>
                                        <td class="font-medium text-gray-900 p-2">
                                            {{ $t("comment") }}
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(
                                            deployment, customerDeploymentIndex
                                        ) in customer.deployments"
                                        :key="customerDeploymentIndex"
                                    >
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            <input
                                                v-model="deployment.number"
                                                :class="{
                                                    'border-red-500':
                                                        $page.props.errors[
                                                            `articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.number`
                                                        ],
                                                    'input-filled': !deployment.fillable,
                                                }"
                                                :disabled="
                                                    disableDeployment(
                                                        customer,
                                                        deployment,
                                                        customerDeploymentIndex
                                                    )
                                                "
                                                :placeholder="deployment.fillable ? $t('a_number') : ''"
                                                class="w-full border-gray-200"
                                                type="text"
                                            >
                                            <ErrorMessage
                                                :error="$page.props.errors[`articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.number`]"
                                            />
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            <input
                                                v-model="deployment.serial"
                                                :class="{
                                                    'border-red-500':
                                                        $page.props.errors[
                                                            `articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.serial`
                                                        ],
                                                    'input-filled': !deployment.fillable,
                                                }"
                                                :disabled="
                                                    disableDeployment(
                                                        customer,
                                                        deployment,
                                                        customerDeploymentIndex
                                                    )
                                                "
                                                :placeholder="
                                                    deployment.fillable ? $t('serial_number') : ''
                                                "
                                                class="w-full border-gray-200"
                                                type="text"
                                            >
                                            <ErrorMessage
                                                :error="$page.props.errors[`articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.serial`]"
                                            />
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            <input
                                                v-model="deployment.comment"
                                                :class="{
                                                    'border-red-500':
                                                        $page.props.errors[
                                                            `articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.comment`
                                                        ],
                                                    'input-filled': !deployment.fillable,
                                                }"
                                                :disabled="
                                                    disableDeployment(
                                                        customer,
                                                        deployment,
                                                        customerDeploymentIndex
                                                    )
                                                "
                                                :placeholder="deployment.fillable ? $t('comment') : ''"
                                                class="w-full border-gray-200"
                                                type="text"
                                            >
                                            <ErrorMessage
                                                :error="$page.props.errors[`articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.comment`]"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <h3
            v-if="
                articles.filter((p) => p.id !== $page.props.articleIdentity.article_id)
                    .length >= 1
            "
            class="p-2 pt-4 mt-10 text-sm font-bold border-t"
        >
            {{ $t("other_articles_for_the_same_invoice") }}
        </h3>

        <div
            v-for="(article, index) in articles.filter(
                (p) => p.id !== $page.props.articleIdentity.article_id
            )"
            :key="`artciles.${article.id}_${index}`"
            class="my-2 border-b"
        >
            <div
                v-if="article.id !== $page.props.articleIdentity.article_id"
                class="border-b"
            >
                <div class="flex justify-between p-3 px-6 text-center">
                    <div
                        class="flex items-center justify-center w-1/12 font-medium text-center"
                    />
                    <div class="w-6/12 font-medium text-left">
                        {{ article.name }}
                    </div>
                    <div class="w-1/12 font-medium">
                        {{ article.quantity }}
                    </div>
                    <div class="w-1/12 font-medium">
                        {{ getTotalArticleDeliveredQuantity(article) }}
                    </div>

                    <div class="w-1/12 font-medium text-center"/>
                </div>
                <div
                    v-for="(customer, customerIndex) in article.article_identities"
                    :key="customerIndex"
                >
                    <div class="flex justify-between px-6 text-center">
                        <div class="w-1/12 font-medium">
                            <vue-number-input
                                v-model="customer.received_quantity"
                                :center="true"
                                :class="{
                                    'border-red-500':
                                        $page.props.errors[
                                            `articles.${index}.article_identities.${customerIndex}.received_quantity`
                                        ],
                                }"
                                :disabled="customer.disable_assign_field"
                                :max="
                                    parseInt(customer.quantity) -
                                        parseInt(customer.delivered_quantity)
                                "
                                :min="0"
                                controls
                                inline
                                size="small"
                                @change="updateArticles"
                            />
                        </div>
                        <div class="w-6/12 font-medium text-left">
                            {{ customer.identity.name }}
                        </div>
                        <div class="w-1/12 font-medium">
                            {{ customer.quantity }}
                        </div>
                        <div class="w-1/12 font-medium">
                            {{ customer.delivered_quantity }}
                        </div>

                        <div class="w-1/12 font-medium text-center">
                            <button
                                @click="toggleArticleIdentityDeployments(index, customerIndex)"
                            >
                                <svg-vue icon="right" class="w-6 h-6"/>
                            </button>
                        </div>
                    </div>
                    <div
                        v-if="customer.show_collapse_panel"
                        class="px-10 pt-2 pb-2 mb-5 bg-white"
                    >
                        <div class="mt-2">
                            <table class="min-w-full text-center">
                                <thead>
                                    <tr>
                                        <td class="font-medium text-gray-900 p-2">
                                            {{ $t("a_number") }}
                                        </td>
                                        <td class="font-medium text-gray-900 p-2">
                                            {{ $t("serial_number") }}
                                        </td>
                                        <td class="font-medium text-gray-900 p-2">
                                            {{ $t("comment") }}
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(
                                            deployment, customerDeploymentIndex
                                        ) in customer.deployments"
                                        :key="customerDeploymentIndex"
                                    >
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            <input
                                                v-model="deployment.number"
                                                :class="{
                                                    'border-red-500':
                                                        $page.props.errors[
                                                            `articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.number`
                                                        ],
                                                    'input-filled': !deployment.fillable,
                                                }"
                                                :disabled="
                                                    disableDeployment(
                                                        customer,
                                                        deployment,
                                                        customerDeploymentIndex
                                                    )
                                                "
                                                :placeholder="deployment.fillable ? $t('a_number') : ''"
                                                class="w-full border-gray-200"
                                                type="text"
                                            >
                                            <ErrorMessage
                                                :error="$page.props.errors[`articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.number`]"
                                            />
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            <input
                                                v-model="deployment.serial"
                                                :class="{
                                                    'border-red-500':
                                                        $page.props.errors[
                                                            `articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.serial`
                                                        ],
                                                    'input-filled': !deployment.fillable,
                                                }"
                                                :disabled="
                                                    disableDeployment(
                                                        customer,
                                                        deployment,
                                                        customerDeploymentIndex
                                                    )
                                                "
                                                :placeholder="
                                                    deployment.fillable ? $t('serial_number') : ''
                                                "
                                                class="w-full border-gray-200"
                                                type="text"
                                            >
                                            <ErrorMessage
                                                :error="$page.props.errors[`articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.serial`]"
                                            />
                                        </td>
                                        <td class="font-medium text-gray-900 whitespace-nowrap">
                                            <input
                                                v-model="deployment.comment"
                                                :class="{
                                                    'border-red-500':
                                                        $page.props.errors[
                                                            `articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.comment`
                                                        ],
                                                    'input-filled': !deployment.fillable,
                                                }"
                                                :disabled="
                                                    disableDeployment(
                                                        customer,
                                                        deployment,
                                                        customerDeploymentIndex
                                                    )
                                                "
                                                :placeholder="deployment.fillable ? $t('comment') : ''"
                                                class="w-full border-gray-200"
                                                type="text"
                                            >
                                            <ErrorMessage
                                                :error="$page.props.errors[`articles.${index}.article_identities.${customerIndex}.deployments.${customerDeploymentIndex}.comment`]"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
// import AppLayout from "@/Layouts/AppLayout";
import ClosePageUsingEscMixin from "@/Mixins/ClosePageUsingEscMixin";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    components: {
        AppLayout,
        ErrorMessage
    },

    mixins: [ClosePageUsingEscMixin],
    data() {
        return {
            invoice: this.$page.props.invoice,
            articles: [],
        };
    },
    created() {
        this.$page.props.invoice.articles.forEach((article) => {
            article.received_quantity = 0;
            let customers = [];
            article.article_identities.forEach((customer) => {
                customer.received_quantity = 0;
                customer.disable_assign_field =
                    parseInt(customer.quantity) === parseInt(customer.delivered_quantity);
                let deployments = [];
                let un_received =
                    parseInt(customer.quantity) - parseInt(customer.delivered_quantity);
                for (let x = 0; x < un_received; x++) {
                    deployments.push({
                        fillable: true,
                        number: "",
                        serial: "",
                        comment: "",
                    });
                }

                customer.deployments.forEach((deployment) => {
                    deployments.push({
                        id: deployment.id,
                        fillable: false,
                        number: deployment.a_number,
                        serial: deployment.serial_number,
                        comment: deployment.description,
                    });
                });
                customer.deployments = deployments;
                customers.push(customer);
            });
            article.article_identities = customers;
            article.show_collapse_panel = false;
            this.articles.push(article);
        });
    },

    methods: {
        getTotalArticleDeliveredQuantity(article) {

            return article.article_identities.reduce((l, c) => c.delivered_quantity + l, 0);
        },
        toggleArticleIdentityDeployments(index, articleIdentityIndex) {
            let article = this.articles[index];
            let identity = article.article_identities[articleIdentityIndex];
            identity.show_collapse_panel = !identity.show_collapse_panel;
            article.article_identities.splice(articleIdentityIndex, 1, identity);
            this.articles.splice(index, 1, article);
        },
        updateArticles() {
            let articles = [];
            this.articles.forEach((article) => {
                articles.updated = Math.random();

                let received_quantity = 0;
                article.article_identities.forEach((element) => {
                    received_quantity =
                        received_quantity + parseInt(element.received_quantity);
                });

                article.received_quantity = received_quantity;
                articles.push(article);
            });
            this.articles = articles;
        },
        disableDeployment(customer, deployment, customerDeploymentIndex) {
            return (
                customer.disable_assign_field ||
                !deployment.fillable ||
                this.deploymentNotInAssignedQtyRange(customer, customerDeploymentIndex)
            );
        },

        deploymentNotInAssignedQtyRange(customer, customerDeploymentIndex) {
            return (
                parseInt(customer.received_quantity) <
                parseInt(customerDeploymentIndex) + 1
            );
        },
        getArticleUnReceivedQuantity(articleIndex) {
            let article = this.articles[articleIndex];

            return (
                parseInt(article.quantity) -
                (parseInt(article.delivered_quantity) +
                    parseInt(article.received_quantity))
            );
        },
        async updateDeliveryStatus() {
            this.$loading.show({delay: 0, background: "#444"});

            let data = {articles: this.articles};
            this.$inertia.post(
                `/api/purchases/${this.$page.props.purchase.id}/update-delivery-status`,
                data,
                {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                }
            );
            //
        },
    },
};
</script>


<style scoped>
input {
    text-align: center !important;
}

input:disabled {
    @apply bg-gray-50;
}

.input-filled {
    background-color: #f5fff5 !important;
}
</style>
