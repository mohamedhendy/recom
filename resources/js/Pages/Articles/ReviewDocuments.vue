<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("review_invoice") }}
            </h2>
        </template>

        <div class="py-12">
            <h3 class="text-gray-900 text-xl font-medium">
                All Articles of a purches
            </h3>

            <div class="flex flex-col mt-8">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider"
                                            scope="col"
                                        />
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider"
                                            scope="col"
                                        >
                                            ID
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider"
                                            scope="col"
                                        >
                                            Order Date
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider"
                                            scope="col"
                                        >
                                            Supplier
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider"
                                            scope="col"
                                        >
                                            Article
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider"
                                            scope="col"
                                        >
                                            Amount
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 uppercase tracking-wider"
                                            scope="col"
                                        >
                                            Client
                                        </th>
                                        <th class="px-6 py-3" scope="col">
                                            <span class="sr-only">Edit</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-2 py-4 whitespace-nowrap text-gray-900 text-center text-sm font-medium">
                                            <input class="rounded text-gray-900" type="checkbox">
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                            215
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                            14.10.2020
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                            Amazon
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                            HDD
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                            405
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-900 font-medium">
                                            TDDH
                                        </td>
                                        <td class="px-2 py-4 space-x-4 whitespace-nowrap text-gray-900 text-right text-sm font-medium">
                                            <inertia-link class="text-indigo-600 hover:text-indigo-900" href="#">
                                                Update
                                            </inertia-link>
                                            <inertia-link class="text-indigo-600 hover:text-indigo-900" href="#">
                                                Bill
                                            </inertia-link>
                                            <inertia-link class="text-indigo-600 hover:text-indigo-900" href="#">
                                                Update Doc
                                            </inertia-link>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between items-center mt-10">
                <div class="max-w-md w-full">
                    <label class="text-gray-800">Comments</label>
                    <textarea class="h-40 w-full mt-6"/>
                </div>

                <button class="mt-8 flex items-center space-x-4 px-4 py-2 rounded bg-gray-900 text-white"
                        type="button"
                >
                    <svg-vue icon="save" class="w-5 h-5"/>
                    <span class="text-sm">Confirm invoice</span>
                </button>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';

export default {
    components: {
        AppLayout,
    }
};
</script>
