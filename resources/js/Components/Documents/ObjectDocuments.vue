<template>
    <div class="mx-auto max-w-6xl py-2 mt-3 mb-3 ">
        <h1 class="text-xl font-bold">
            {{ $t('documents') }}
            <inertia-link v-if="createUrl" :href="createUrl" class="font-normal text-lg">
                {{ $t('add_documents') }}
            </inertia-link>
        </h1>
        <table v-if="object.documents && object.documents.length" class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <!--                                    <th-->
                    <!--                                        class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-800 uppercase"-->
                    <!--                                        scope="col"-->
                    <!--                                    >-->
                    <!--                                        {{ $t("article") }}-->
                    <!--                                    </th>-->
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
            <!--                            v-for="(article, index) in articles"-->
            <!--                            :key="index"-->
            <tbody

                class="bg-white divide-y divide-gray-200"
            >
                <tr
                    v-for="(document, documentIndex) in object.documents"
                    :key="documentIndex"
                >
                    <!--                                    <td class="px-6 py-4 font-medium text-gray-900">-->
                    <!--&lt;!&ndash;                                        {{ $page.props.entity.prod }}&ndash;&gt;-->

                    <!--&lt;!&ndash;                                        <ErrorMessage :error="$page.props.errors[`articles.${index}.id`]"/>&ndash;&gt;-->
                    <!--                                    </td>-->
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
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "ObjectDocuments",
    props: {
        createUrl: {
            type: String
        },
        object: {
            type: Object,
            required: true
        }
    }
};
</script>

<style scoped>

</style>
