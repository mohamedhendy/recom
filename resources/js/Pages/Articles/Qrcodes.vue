<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("show_qr_code") }}
            </h2>
        </template>

        <div
            v-for="(article, index) in $page.props.invoice.articles"
            :key="`artciles.${article.id}_${index}`"
            class="my-2 border  shadow mb-3"
        >
            <div
                class="border-b"
            >
                <div class="flex p-3 px-6 bg-gray-50  ">
                    <h1 class="text-xl">
                        {{ article.name }}
                    </h1>
                </div>
                <div
                    v-for="(customer, customerIndex) in article.article_identities"
                    :key="customerIndex"
                >
                    <div class="flex justify-between px-6 text-center">
                        <div class="w-6/12 font-medium text-left">
                            {{ customer.identity.name }}
                        </div>
                        <div class="w-1/12 font-medium"/>
                        <div class="w-1/12 font-medium"/>
                    </div>
                    <div
                        class="px-10 pt-2 pb-2 mb-5 bg-white"
                    >
                        <div class="mt-2">
                            <div class="grid grid-cols-6">
                                <div v-for="(
                                         deployment, customerDeploymentIndex
                                     ) in customer.deployments" :key="customerDeploymentIndex"
                                     class="border m-1 p-3 text-center"
                                >
                                    <h1 class="text-xl font-bold">
                                        {{ customerDeploymentIndex + 1 }}
                                    </h1>
                                    <div>
                                        {{ $t("a_number") }}: {{ deployment.a_number }}
                                    </div>
                                    <div>
                                        {{ $t("serial_number") }}: {{ deployment.serial_number }}
                                    </div>
                                    <div>
                                        {{ $t("comment") }}: {{ deployment.description }}
                                    </div>

                                    <inline-qrcode :deployment="deployment" :product="article.product"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ClosePageUsingEscMixin from "@/Mixins/ClosePageUsingEscMixin";
import InlineQrcode from "@/Components/Product/inlineQrcode";

export default {
    components: {
        InlineQrcode,
        AppLayout,
    },

    mixins: [ClosePageUsingEscMixin],
    data() {
        return {
            invoice: this.$page.props.invoice,
            articles: [],
        };
    },
    created() {
    },

};
</script>
