<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("add_documents") }}
            </h2>
        </template>

        <template #screen-actions>
            <button
                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded"
                type="button"
                @click="saveDocuments"
            >
                <svg-vue icon="save" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("upload") }}</span>
            </button>
        </template>

        <div class="screen__content">
            <div class="form">
                <div class="flex">
                    <div class="flex flex-col items-center justify-center flex-1">
                        <select v-model="documentType" class="flex-shrink-0 w-56 h-10">
                            <option :value="null">
                                {{ $t("choose_document") }}
                            </option>
                            <option
                                v-for="type in $dataset.document_types"
                                :key="type"
                                :value="type"
                            >
                                {{ $t(type) }}
                            </option>
                        </select>

                        <div class="mt-10 ">
                            <div class="flex flex-col mt-8">
                                <ErrorMessage :error="$page.props.errors[`purchase_order_products`]"/>

                                <button :disabled="disableSelectAll" class="mr-5 py-2 px-3 rounded font-bold" :class="[disableSelectAll ? 'bg-gray-200 text-gray-800 ' : 'bg-gray-800  text-white']" type="button" @click="selectAllPurchaseOrderProducts">
                                    <span class="text-sm">{{ $t("select_all") }}</span>
                                </button>

                                <table class="min-w-full divide-y divide-gray-200">
                                    <tbody
                                        v-for="(purchaseOrderProduct, index) in purchaseOrderProducts"
                                        :key="index"
                                        class="bg-white divide-y divide-gray-200"
                                    >
                                        <tr>
                                            <td
                                                class="px-2 py-4 text-sm font-medium text-center text-gray-900"
                                            >
                                                <input
                                                    v-model="purchaseOrderProduct.add_document"
                                                    class="text-gray-900 rounded"
                                                    type="checkbox"
                                                >
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ purchaseOrderProduct.product.name }}

                                                <ErrorMessage :error="$page.props.errors[`purchase_order_products.${index}.id`]"/>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1">
                        <h3 class="text-xl font-medium text-gray-900">
                            {{ $t("upload_documents") }}
                        </h3>
                        <label
                            class="flex flex-col items-center w-full max-w-lg px-4 py-6 mt-8 tracking-wide text-gray-900 uppercase border border-gray-700 rounded-lg cursor-pointer"
                            @dragleave="dragleave"
                            @dragover="dragover"
                            @drop="drop"
                        >
                            <svg-vue icon="upload" class="w-8 h-8"/>
                            <span class="mt-2 text-base leading-normal">{{
                                $t("select_a_file")
                            }}</span>
                            <input
                                accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint,
                        text/plain, application/pdf, image/*"
                                class="hidden"
                                multiple
                                type="file"
                                @change="addDocuments"
                            >
                        </label>
                        <ErrorMessage :error="$page.props.errors[`documents`]"/>

                        <table>
                            <tr
                                v-for="(document, documentIndex) in documents"
                                :key="documentIndex"
                            >
                                <td
                                    class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap"
                                >
                                    {{ document.name }}
                                </td>
                                <td
                                    class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap"
                                >
                                    {{ $t(document.type) }}
                                </td>

                                <td>
                                    <button @click="deleteDocument(documentIndex)">
                                        <svg-vue icon="trash" class="w-6 h-6"/>
                                    </button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="mt-10">
                    <h3 class="text-xl font-medium text-gray-900">
                        {{ $t("documents") }}
                    </h3>

                    <div class="flex flex-col mt-8">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-800 uppercase"
                                        scope="col"
                                    >
                                        {{ $t("purchaseOrderProduct") }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-800 uppercase"
                                        scope="col"
                                    >
                                        {{ $t("name") }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-800 uppercase"
                                        scope="col"
                                    >
                                        {{ $t("type") }}
                                    </th>
                                    <th
                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-800 uppercase"
                                        scope="col"
                                    />
                                </tr>
                            </thead>
                            <tbody
                                v-for="(purchaseOrderProduct, index) in purchaseOrderProducts"
                                :key="index"
                                class="bg-white divide-y divide-gray-200"
                            >
                                <tr
                                    v-for="(document, documentIndex) in purchaseOrderProduct.documents"
                                    :key="documentIndex"
                                >
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ purchaseOrderProduct.product.name }}

                                        <ErrorMessage :error="$page.props.errors[`purchase_order_products.${index}.id`]"/>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ document.document.original_name }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900">
                                        {{ $t(document.type) }}
                                    </td>
                                    <td
                                        class="flex items-center justify-center gap-2 px-6 py-4 font-medium text-gray-900"
                                    >
                                        <a :href="`/files/${document.document_id}/download`"><span>{{ $t("view") }}</span></a>
                                        <button @click="deleteOldDocument(document.id)">
                                            <svg-vue icon="trash" class="w-6 h-6"/>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import DragDropInputMixin from "@/Mixins/DragDropInputMixin";
import ClosePageUsingEscMixin from "@/Mixins/ClosePageUsingEscMixin";

export default {
    name: "UploadDocuments",
    delimiters: ["${", "}"],
    components: {
        AppLayout,
        ErrorMessage
    }, // Avoid Twig conflicts
    mixins: [DragDropInputMixin, ClosePageUsingEscMixin],
    props: ['purchaseOrder'],
    data() {
        return {
            disableSelectAll: false,
            filelist: [],
            documentType: null,
            purchaseOrderProducts: this.purchaseOrder.purchase_order_products,
            documents: [],
        };
    },
    watch: {
        purchaseOrderProducts: {
            deep: true,
            handler(value) {
                for (let purchaseOrderProductKey in this.purchaseOrderProducts) {
                    let purchaseOrderProduct = this.purchaseOrderProducts[purchaseOrderProductKey];

                    if (!purchaseOrderProduct.add_document) {
                        this.disableSelectAll = false;
                        return ;
                    }
                }
                this.disableSelectAll = true;

            }
        }
    },
    methods: {
        selectAllPurchaseOrderProducts() {
            let list = [];
            for (let purchaseOrderProductKey in this.purchaseOrderProducts) {
                let purchaseOrderProduct = this.purchaseOrderProducts[purchaseOrderProductKey];
                purchaseOrderProduct.add_document = true;
                list.push(purchaseOrderProduct);

            }

            this.purchaseOrderProducts = list;
        },
        deleteOldDocument(documentId) {
            this.askUser().then(res => {
                if (res) this.$inertia.delete(route('api.documents.delete',documentId));
            })
        },
        saveDocuments() {
            this.$loading.show({delay: 0, background: "#444"});

            let formData = new FormData();

            for (let purchaseOrderProductKey in this.purchaseOrderProducts) {
                let purchaseOrderProduct = this.purchaseOrderProducts[purchaseOrderProductKey];
                if (purchaseOrderProduct.add_document === true) {
                    formData.append(`purchase_order_products[${purchaseOrderProductKey}][id]`, purchaseOrderProduct.id);
                    formData.append(
                        `purchase_order_products[${purchaseOrderProductKey}][documents_upload]`,
                        purchaseOrderProduct.documents_upload === true
                    );
                }
            }

            for (let documentKey in this.documents) {
                let document = this.documents[documentKey];
                formData.append(
                    `documents[${documentKey}][document]`,
                    document.document
                );
                formData.append(`documents[${documentKey}][type]`, document.type);
                formData.append(`documents[${documentKey}][name]`, document.name);
            }

            this.$inertia.post(route('api.purchase_orders.documents',this.$page.props.purchaseOrder.id),
                               formData,
                               {
                                   onFinish: () => {
                                       this.$loading.hide();
                                   },
                               }
            );

            this.handleResponse(location.href, "Success", "Document Uploaded");
        },
    },
}
</script>

<style scoped>

</style>
