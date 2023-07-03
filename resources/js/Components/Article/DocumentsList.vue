<template>
    <div>
        <h3 class="text-xl font-medium text-gray-900">
            {{ $t('documents') }}
        </h3>
        <div class="mt-6">
            <table class="w-full text-gray-900 border">
                <thead>
                    <tr>
                        <th class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap">
                            {{ $t('name') }}
                        </th>
                        <th class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap">
                            {{ $t('type') }}
                        </th>
                        <th class="px-2 py-4 text-sm font-medium text-center text-gray-900 whitespace-nowrap">
                            <!--{{$t('edit')}}-->
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(document, documentIndex) in documents" :key="documentIndex">
                        <td class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap">
                            <span v-if="document.id !== undefined">
                                <a :href="`/files/${document.document_id}/download`" class="text-blue-500"
                                   target="_blank"
                                >{{ document.name }}</a>
                            </span>
                            <span v-else>{{ document.name }}</span>
                            <ErrorMessage :error="$page.props.errors[`articles.${index}.documents.${documentIndex}.document`]"/>
                        </td>
                        <td class="px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap">
                            {{ $t(document.type) }}
                            <ErrorMessage :error="$page.props.errors[`articles.${index}.documents.${documentIndex}.type`]"/>
                        </td>
                        <!--                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">2013.2.3</td>-->

                        <td class="flex justify-center px-6 py-4 font-medium text-center text-gray-900 whitespace-nowrap">
                            <button
                                v-if="!viewOnly"
                                @click="deleteDocument(documentIndex)"
                            >
                                <svg-vue icon="trash" class="w-6 h-6"/>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div v-if="!viewOnly" class="flex">
            <div class="flex-1">
                <label
                    class="flex flex-col items-center w-full max-w-lg px-4 py-6 mx-auto mt-8 tracking-wide text-gray-900 uppercase border border-gray-600 rounded-lg cursor-pointer"
                    @dragover="dragover"
                    @dragleave="dragleave"
                    @drop="drop"
                >
                    <svg-vue icon="upload" class="w-8 h-8"/>
                    <span class="mt-2 text-base leading-normal">{{ $t('select_a_file') }}</span>

                    <input
                        accept="application/msword, application/vnd.ms-excel, application/vnd.ms-powerpoint, text/plain, application/pdf, image/*"
                        class="hidden"
                        multiple type="file" @change="addDocuments"
                    >
                </label>
            </div>
            <div class="flex items-center justify-center flex-1">
                <select v-model="documentType" class="flex-shrink-0 w-56 h-10">
                    <option :value="null">
                        {{ $t("choose_document") }}
                    </option>
                    <option v-for="type in $dataset.document_types" :key="type" :value="type">
                        {{
                            $t(type)
                        }}
                    </option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
import DragDropInputMixin from "@/Mixins/DragDropInputMixin";
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    name: "InvoiceArticleDocumentForm",
    components: {
        ErrorMessage
    },
    mixins: [DragDropInputMixin],
    props: {
        viewOnly: {
            type: Boolean,
            default: false
        },
        article: {
            type: Object,
            required: true
        },
        index: {
            type: Number,
            required: true
        },
        initDocuments: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            documentType: null,
            documents: [],
        };
    },
    watch: {
        documents: {
            deep: true,
            handler(value) {
                // let formData = new FormData;
                // formData.append('documents',value);
                this.$emit('updated', {documents: value, articleIndex: this.index});
            }
        }
    },
    created() {

        this.initDocuments.forEach((document) => {
            document.name = document.document.original_name;
            document.db_document = document.document;
            document.document = null;
            this.documents.push(document);
        });
    },

};
</script>

<style scoped>

</style>
