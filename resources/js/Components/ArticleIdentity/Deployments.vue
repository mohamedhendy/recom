<template>
    <div v-if="showCollapse()" class="mt-4 p-3 bg-gray-100">
        <h3 class="text-lg flex justify-between items-center">
            {{ $t("deployments") }}
        </h3>
        <div class="bg-white">
            <div class="mt-2">
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <td class="font-medium text-gray-900 p-2">
                                {{ $t("a_number") }}
                            </td>
                            <td class="font-medium text-gray-900  p-2">
                                {{ $t("serial_number") }}
                            </td>
                            <td class="font-medium text-gray-900  p-2">
                                {{ $t("comment") }}
                            </td>
                            <td v-if="product" class="font-medium text-gray-900  p-2"/>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(deployment, deploymentIndex) in deployments"
                            :key="deploymentIndex"
                        >
                            <td class="font-medium text-gray-900 whitespace-nowrap">
                                <input
                                    v-model="deployment.a_number"
                                    :placeholder="$t('a_number')"
                                    class="w-full border-gray-200 input-filled"
                                    type="text"
                                >
                                <ErrorMessage :error="$page.props.errors[`articles.${index}.article_identities.${index}.deployments.${deploymentIndex}.a_number`]"/>
                            </td>
                            <td class="font-medium text-gray-900 whitespace-nowrap">
                                <input
                                    v-model="deployment.serial_number"
                                    :placeholder="$t('serial_number')"
                                    class="w-full border-gray-200 input-filled"
                                    type="text"
                                >
                                <ErrorMessage :error="$page.props.errors[`articles.${index}.article_identities.${index}.deployments.${deploymentIndex}.serial_number`]"/>
                            </td>
                            <td class="font-medium text-gray-900 whitespace-nowrap">
                                <input
                                    v-model="deployment.description"
                                    :placeholder="$t('comment')"
                                    class="w-full border-gray-200 nput-filled"
                                    type="text"
                                >
                                <ErrorMessage :error="$page.props.errors[`articles.${index}.article_identities.${index}.deployments.${deploymentIndex}.description`]"/>
                            </td>


                            <td v-if="product" class="font-medium text-gray-900 items-center flex justify-center">
                                <div class="w-20">
                                    <qr-code :deployment="deployment" :product="product"/>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import QrCode from '../Product/QrCode.vue';
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    components: {
        QrCode,
        ErrorMessage
    },
    props: {
        identity: {
            type: Object,
            required: true,
        },
        product: {
            type: Object,
            default: () => null
        },
        updatedDeployments: {
            type: Array,
            default: () => null
        },
        index: {
            type: Number,
            required: true,
        },
        show: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            deployments: [],
            deploymentsBroadCastChannel: new BroadcastChannel('deploymentsBroadCastChannel'),
        };
    },
    watch: {
        updatedDeployments: {
            deep: true,
            handler(value) {
                this.deployments = value;
            },
        },
        deployments: {
            deep: true,
            handler(value) {
                this.$emit("updated", {deployments: value, index: this.index});
            },
        },
    },

    created() {
        console.log(this.product);
        this.deployments = this.identity.deployments;
        // if(this.$page.props.invoice_type === 'sale' && this.$page.props.page_type == 'create')
        // {
        //     this.deploymentsBroadCastChannel.onmessage =  (event) => {
        //         if (event.isTrusted) {
        //             const data = JSON.parse(event.data)
        //             if(this.identity && data.identity.product_id === this.product.id && !data.deployment.sales_article_identity_id)
        //             {
        //                 let position = this.deployments.find(p => !p.is_pushed);
        //                 if(position)
        //                 {
        //                     let positionIndex = this.deployments.indexOf(position)
        //                     this.deployments[positionIndex].serial_number = data.deployment.serial_number;
        //                     this.deployments[positionIndex].a_number = data.deployment.a_number;
        //                     this.deployments[positionIndex].description = data.deployment.description;
        //                 }
        //             }
        //         }
        //     }
        // }
    },
    methods: {
        showCollapse() {
            return this.show;
        },
        disableDeployment(customer, deployment, customerDeploymentIndex) {
            return (
                customer.disable_assign_field ||
                !deployment.fillable ||
                this.deploymentNotInAssignedQtyRange(customer, customerDeploymentIndex)
            );
        },
    },
};
</script>

<style>
</style>
