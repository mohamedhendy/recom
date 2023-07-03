<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ $t("customer_diff") }}
            </h2>
        </template>

        <div class="screen__content">
            <table class="datatable__container">
                <thead>
                    <tr>
                        <th>Details</th>
                        <th>Nr</th>
                        <th>Name</th>
                        <th>DB value</th>
                        <th>Update</th>
                        <th>EasyBill value</th>
                    </tr>
                </thead>
                <template v-for="customer in $page.props.customerDetailsToUpdate">
                    <template v-for="(customerDetailToUpdate, dindex) in customer.detailsToUpdate">
                        <tr :key="`${customerDetailToUpdate.id}`">
                            <td v-if="dindex === 0" :rowspan="customer.detailsToUpdate.length">
                                <button
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                                    @click="showCustomerData(customer.dbCustomer)"
                                >
                                    DB
                                </button>
                                <br>
                                <button
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                                    @click="showCustomerData(customer.ebCustomer)"
                                >
                                    EasyBill
                                </button>
                            </td>
                            <td>
                                {{ customerDetailToUpdate.id }}
                            </td>
                            <td>
                                {{ customerDetailToUpdate.name }}
                            </td>
                            <td>
                                {{ customerDetailToUpdate.database.data }}
                            </td>
                            <td>
                                <button
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                                    @click="updateDb(customerDetailToUpdate, customer.id)"
                                >
                                    &lt;==
                                </button>
                                <button
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                                    @click="updateEb(customerDetailToUpdate, customer.id)"
                                >
                                    ==&gt;
                                </button>
                            </td>
                            <td>
                                {{ customerDetailToUpdate.easyBill.data }}
                            </td>
                        </tr>
                    </template>
                </template>
            </table>
        </div>
    </app-layout>
</template>

<script>

import AppLayout from "@/Layouts/AppLayout";
import axios from "axios";

export default {
    components: {
        AppLayout,
    },
    props: {
        customerDetailsToUpdate: {
            type: Array,
            default: () => []
        },
    },
    data() {
        return {
            meta: {
                total: 0,
                per_page: 0,
                last_page: 0,
            },
            orderBy: "id",
            orderDirection: "desc",
            activeTab: "all",
            queryParams: "{}",
        };
    },
    methods: {
        showCustomerData(customer) {
            console.log(JSON.stringify(customer));
        },
        updateDb(customerDetail, customerId) {
            this.$loading.show({delay: 0, background: "#444"});

            axios
                .post(`/api/customers/${customerDetail.database.id}/updateSingle`, {
                    field: customerDetail.database.name,
                    data: customerDetail.easyBill.data
                })
                .then((res) => {
                    if (res.data) {
                        this.customerDetailsToUpdate.forEach(function (c) {
                            if (c.id === customerId) {
                                c.detailsToUpdate = c.detailsToUpdate.filter(cd => cd.id !== customerDetail.id);
                            }
                        });
                    } else {
                        console.log(res);
                        alert(res);
                    }
                })
                .catch(function (error) {
                    alert(error.response.data.message);
                    console.log(error.response.data);
                })
                .finally(() => {
                    this.$loading.hide();
                });
        },
        updateEb(customerDetail, customerId) {
            this.$loading.show({delay: 0, background: "#444"});

            alert("not implemented");
            // axios
            //     .post(`/api/customers/${customerDetail.database.id}/updateEasyBill`, {
            //         field: customerDetail.easyBill.name,
            //         data: customerDetail.database.data
            //     })
            //     .then((res) => {
            //         if (res.data) {
            //             this.customerDetailsToUpdate.forEach(function (c) {
            //                 if (c.id === customerId) {
            //                     c.detailsToUpdate = c.detailsToUpdate.filter(cd => cd.id !== customerDetail.id);
            //                 }
            //             });
            //         } else {
            //             console.log(res);
            //             alert(res);
            //         }
            //     })
            //     .catch(function (error) {
            //         alert(error.response.data.message);
            //         console.log(error.response.data);
            //     })
            //     .finally(() => {
            this.$loading.hide();
            //     });
        },
    },
};
</script>
