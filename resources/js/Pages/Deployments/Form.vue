<template>
    <app-layout>
        <template #header>
            <h2 class="screen__title">
                {{ pageTitle }}
            </h2>
        </template>

        <template #screen-actions>
            <qr-code :product="$page.props.entity"/>
            <button
                v-if="!$page.props.view_only"
                class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                type="button"
                @click="saveEntity"
            >
                <svg-vue icon="save" class="w-5 h-5"/>
                <span class="text-sm">{{ $t("save") }}</span>
            </button>
        </template>

        <div class="screen__content">
            <div class="form">
                <div class="flex justify-around gap-3">
                    <div class="flex-1">
                        <div class="py-5 mt-5">
                            <div class="grid grid-cols-3 gap-6">
                                <customInput
                                    v-model="entityData.name"
                                    :label="$t('name')"
                                    :required="false"
                                    :errors="$page.props.errors.name"
                                    :disabled="$page.props.view_only"
                                />

                                <customInput
                                    v-model="entityData.o_number"
                                    :label="$t('o_number')"
                                    :required="false"
                                    :errors="$page.props.errors.o_number"
                                    :disabled="$page.props.view_only"
                                />

                                <customInput
                                    v-model="entityData.type"
                                    :label="$t('type')"
                                    :required="false"
                                    :errors="$page.props.errors.type"
                                    :disabled="$page.props.view_only"
                                />
                            </div>
                        </div>

                        <div class="py-5">
                            <div class="grid grid-cols-3 gap-6">
                                <customInput
                                    v-model="entityData.address"
                                    :label="$t('address')"
                                    :required="false"
                                    :errors="$page.props.errors.address"
                                    :disabled="$page.props.view_only"
                                />

                                <customInput
                                    v-model="entityData.building"
                                    :label="$t('building')"
                                    :required="false"
                                    :errors="$page.props.errors.building"
                                    :disabled="$page.props.view_only"
                                />
                            </div>
                        </div>

                        <div class="py-5">
                            <div class="grid grid-cols-3 gap-6">
                                <customInput
                                    v-model="entityData.room"
                                    :label="$t('room')"
                                    :required="false"
                                    :errors="$page.props.errors.room"
                                    :disabled="$page.props.view_only"
                                />

                                <customInput
                                    v-model="entityData.exact_position"
                                    :label="$t('exact_position')"
                                    :required="false"
                                    :errors="$page.props.errors.exact_position"
                                    :disabled="$page.props.view_only"
                                />

                                <customInput
                                    v-model="entityData.contact"
                                    :label="$t('contact_info')"
                                    :required="false"
                                    :errors="$page.props.errors.contact"
                                    :disabled="$page.props.view_only"
                                />
                            </div>
                        </div>

                        <div class="py-5">
                            <div class="grid grid-cols-3 gap-6">
                                <customers-staffs-select-list
                                    :clean-after-select="false"
                                    :is-view-page="$page.props.view_only"
                                    :base-value-id="entityData.identity_id"
                                    :base-value-type="entityData.identity_type"
                                    :error="$page.props.errors.identity_id"
                                    @change="identityChanged"
                                />

                                <assets-select-list
                                    v-model="entityData.asset_id"
                                    :is-view-page="$page.props.view_only"
                                    :base-value="entityData.asset_id"
                                    @change="assetChanged"
                                />
                            </div>
                        </div>

                        <div class="py-5">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label>{{ $t("info") }}</label>
                                    <json-input
                                        :error-message="$page.props.errors.info"
                                        :has-error="$page.props.errors.info"
                                        :json="entityData.info"
                                        @json-changed="(json) => entityData.info = json"
                                    />
                                </div>

                                <div>
                                    <label>{{ $t("parent_deployment") }}</label>
                                    <div v-if="entityData.deployed_slot" class="deployment">
                                        <inertia-link
                                            :href="`/deployments/${entityData.deployed_slot.deployment.id}/edit`">
                                            <svg-vue
                                                class="page-content__deployments--item-icon"
                                                icon="deployments"
                                            />
                                            {{ entityData.deployed_slot.deployment.asset.a_number }}:
                                            {{ entityData.deployed_slot.deployment.name }}
                                            ({{ entityData.deployed_slot.deployment.asset.product.name }})
                                        </inertia-link>

                                        <div class="productSlot">
                                            <label>
                                                <svg-vue
                                                    class="page-content__deployments--item-icon"
                                                    icon="slots"
                                                />
                                                {{ entityData.deployed_slot.product_slot.number }}:
                                                {{ entityData.deployed_slot.product_slot.name }}
                                            </label>

                                            <div class="deploymentSlot">
                                                <div>
                                                    <label>{{ $t("info") }}</label>
                                                    <json-input
                                                        :json="entityData.deployed_slot.info"
                                                        :disabled="true"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div v-else class="addDeployment">
                                        <inertia-link :href="`/deployments/${entityData.id}/link`">
                                            <svg-vue
                                                class="page-content__deployments--item-icon"
                                                icon="plus"
                                            />
                                            TODO: Add Parent Deployment
                                        </inertia-link>
                                    </div>-->
                                </div>
                            </div>
                        </div>

                        <div class="py-5">
                            <label>{{ $t("slots") }}</label>

                            <div v-for="productSlot in orderedProductSlots" :key="productSlot.id">
                                <div class="py-5 productSlot">
                                    <label>
                                        <svg-vue
                                            class="page-content__deployments--item-icon"
                                            icon="slots"
                                        />
                                        {{ productSlot.number }}:
                                        {{ productSlot.name }}
                                    </label>

                                    <div v-if="getDeploymentSlots(entityData.deployment_slots, productSlot).length < 1"
                                         class="addSlot"
                                    >
                                        <button
                                            class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                                            @click="insertDeploymentSlot(entityData, productSlot)"
                                        >
                                            <svg-vue
                                                class="page-content__deployments--item-icon"
                                                icon="plus"
                                            />

                                            Insert DeploymentSlot
                                        </button>
                                    </div>
                                    <div
                                        v-for="deploymentSlot in getDeploymentSlots(entityData.deployment_slots, productSlot)"
                                        :key="`deployment_slot_${deploymentSlot.id}`"
                                    >
                                        <button
                                            class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                                            type="button"
                                            @click="deleteDeploymentSlot(deploymentSlot)"
                                        >
                                            <svg-vue icon="delete" class="w-5 h-5"/>
                                            <span class="text-sm">{{ $t("remove") }}</span>
                                        </button>

                                        <div class="grid grid-cols-2 gap-6 deploymentSlot">
                                            <div>
                                                <div>
                                                    <label>{{ $t("info") }}</label>
                                                    <json-input
                                                        :json="deploymentSlot.info"
                                                        @json-changed="(json) => {
                                                            deploymentSlot.info = json;
                                                        }"
                                                    />
                                                </div>

                                                <label>{{ $t("containes") }}</label>
                                                <div v-if="deploymentSlot.child_deployments.length > 0">
                                                    <div v-for="childDeployment in deploymentSlot.child_deployments"
                                                         :key="`child_deployment_${childDeployment.id}`" class="deployment"
                                                    >
                                                        <button
                                                            class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                                                            type="button"
                                                            @click="deleteChildDeployment(childDeployment)"
                                                        >
                                                            <svg-vue icon="delete" class="w-5 h-5"/>
                                                            <span class="text-sm">{{ $t("remove") }}</span>
                                                        </button>

                                                        <inertia-link :href="`/deployments/${childDeployment.id}/edit`">
                                                            <svg-vue
                                                                class="page-content__deployments--item-icon"
                                                                icon="inventories"
                                                            />
                                                            {{ childDeployment.asset.a_number }}:
                                                            {{ childDeployment.name }}
                                                            ({{ childDeployment.asset.product.name }})
                                                        </inertia-link>
                                                    </div>
                                                </div>
                                                <div v-else class="addChildDeployment">
                                                    <inertia-link
                                                        :href="`/deployments/${entityData.id}/insertAtSlot/${deploymentSlot.id}`"
                                                    >
                                                        <svg-vue
                                                            class="page-content__deployments--item-icon"
                                                            icon="plus"
                                                        />
                                                        Add deployment at slot
                                                    </inertia-link>
                                                </div>
                                            </div>

                                            <div>
                                                <label>{{ $t("connected") }}</label>
                                                <div v-if="getConnections(deploymentSlot).length > 0"
                                                     class="connectedSlot"
                                                >
                                                    <div v-for="connection in getConnections(deploymentSlot)"
                                                         :key="connection.render_key" class="deployment"
                                                    >
                                                        <button
                                                            class="flex items-center px-4 py-2 space-x-4 text-white bg-gray-900 rounded hover:bg-gray-800 focus:outline-none"
                                                            type="button"
                                                            @click="deleteConnection(deploymentSlot, connection)"
                                                        >
                                                            <svg-vue icon="delete" class="w-5 h-5"/>
                                                            <span class="text-sm">{{ $t("remove") }}</span>
                                                        </button>

                                                        <inertia-link
                                                            :href="`/deployments/${connection.deployment.id}/edit`"
                                                        >
                                                            <svg-vue
                                                                class="page-content__deployments--item-icon"
                                                                icon="inventories"
                                                            />
                                                            {{ connection.deployment.asset.a_number }}:
                                                            {{ connection.deployment.name }}
                                                            ({{ connection.deployment.asset.product.name }})
                                                        </inertia-link>
                                                        <div class="productSlot">
                                                            <label>
                                                                <svg-vue
                                                                    class="page-content__deployments--item-icon"
                                                                    icon="slots"
                                                                />
                                                                {{ connection.product_slot.number }}:
                                                                {{ connection.product_slot.name }}
                                                            </label>

                                                            <div class="deploymentSlot">
                                                                <div>
                                                                    <label>{{ $t("info") }}</label>
                                                                    <json-input
                                                                        :json="connection.info"
                                                                        :disabled="true"
                                                                    />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div v-else class="addConnection">
                                                    <inertia-link
                                                        :href="`/deployments/${entityData.id}/connectSlot/${deploymentSlot.id}`"
                                                    >
                                                        <svg-vue
                                                            class="page-content__deployments--item-icon"
                                                            icon="plus"
                                                        />
                                                        Add connection to slot
                                                    </inertia-link>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="py-5">
                            <label>{{ $t("teamviewer_connections") }}</label>

                            <Datatable
                                :endpoint="`/api/deployments/${entityData.id}/tv`"
                            >
                                <template #columns>
                                    <th
                                        :class="{ 'bg-gray-100': orderBy === 'tvc_start_date' }"
                                        class="datatable__header"
                                        scope="col"
                                        @click="updateOrderBy('tvc_start_date')"
                                    >
                                        {{ $t("start") }}
                                    </th>
                                    <th
                                        :class="{ 'bg-gray-100': orderBy === 'tvc_end_date' }"
                                        class="datatable__header w-96"
                                        scope="col"
                                        @click="updateOrderBy('tvc_end_date')"
                                    >
                                        {{ $t("end") }}
                                    </th>
                                    <th
                                        :class="{ 'bg-gray-100': orderBy === 'tvc_duration' }"
                                        class="datatable__header"
                                        scope="col"
                                        @click="updateOrderBy('tvc_duration')"
                                    >
                                        {{ $t("duration") }}
                                    </th>
                                    <th
                                        :class="{ 'bg-gray-100': orderBy === 'tvc_notes' }"
                                        class="datatable__header"
                                        scope="col"
                                        @click="updateOrderBy('tvc_notes')"
                                    >
                                        {{ $t("notes") }}
                                    </th>
                                    <th
                                        :class="{ 'bg-gray-100': orderBy === 'tvc_internal_comment' }"
                                        class="datatable__header"
                                        scope="col"
                                        @click="updateOrderBy('tvc_internal_comment')"
                                    >
                                        {{ $t("comment") }}
                                    </th>
                                </template>
                                <template #row="raw">
                                    <td class="px-2 font-medium text-center text-gray-900">
                                        {{ raw.tvc_start_date }}
                                    </td>
                                    <td class="px-2 font-medium text-center text-gray-900">
                                        {{ raw.tvc_end_date }}
                                    </td>
                                    <td class="px-2 font-medium text-center text-gray-900">
                                        {{ raw.tvc_duration }}
                                    </td>
                                    <td class="px-2 font-medium text-center text-gray-900">
                                        {{ raw.tvc_notes }}
                                    </td>
                                    <td class="px-2 font-medium text-center text-gray-900">
                                        {{ raw.tvc_internal_comment }}
                                    </td>
                                </template>
                            </Datatable>
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
import JsonInput from "@/Components/Reusable/JsonInput";
import QrCode from '../../Components/Product/QrCode.vue';
import CustomInput from "@/Components/Reusable/CustomInput";
import Datatable from "@/Components/Datatable/Datatable";
import AssetsSelectList from "@/Components/Assets/AssetsSelectList";
import CustomersStaffsSelectList from "@/Components/Identity/CustomersStaffsSelectList";

export default {
    name: "Deployments.Form",
    components: {
        AssetsSelectList,
        JsonInput,
        AppLayout,
        QrCode,
        CustomInput,
        Datatable,
        CustomersStaffsSelectList
    },
    mixins: [ClosePageUsingEscMixin],
    props: {
        returnCreated: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            closing: false,
            updatePage: false,
            pageTitle: this.$t("create_deployment"),
            entityData: {
                id: null,
                asset_id: null,
                asset: null,
                identity_id: null,
                identity_type: null,
                o_number: null,
                name: null,
                type: null,
                address: null,
                building: null,
                room: null,
                exact_position: null,
                info: null,
                contact: null,
                deployed_slot_id: null,
                deployed_slot: null,
                product_slots: null,
                deployment_slots: null,
                child_deployments: null,
            },
            orderBy: "id",
        };
    },
    computed: {
        orderedProductSlots: function () {
            if (this.entityData.asset)
                return _.orderBy(this.entityData.asset.product.product_slots, 'number');
            else return [];
        },
        entity() {
            return this.$page.props.entity
        }
    },
    watch: {
        entity: {
            deep: true,
            handler(value) {
                this.createOrUpdateEntity(value)
            }
        }
    },
    created() {
        if (this.$page.props.entity) {
            this.createOrUpdateEntity(this.$page.props.entity)
        }
    },
    methods: {
        createOrUpdateEntity(value) {
            this.entityData = value;
            this.entityData.identity_type = value.identity_slug;
            this.pageTitle = this.$t("update");
            this.updatePage = true;
        },
        saveEntity() {
            this.$loading.show({delay: 0, background: "#444"});

            let data = this.entityData;
            data.return_created = this.returnCreated;
            if (!this.updatePage) {
                this.$inertia.post("/api/deployments", data, {
                    onSuccess: () => { //page
                        // console.log(page);
                    },

                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            } else {
                this.$inertia.patch("/api/deployments/" + this.entityData.id, data, {
                    onFinish: () => {
                        this.$loading.hide();
                    },
                });
            }

            this.$inertia.on("invalid", (event) => {
                event.preventDefault();
                let product = event.detail.response.data;
                this.$emit("created", {product: product});
            });
        },
        getDeploymentSlots(deploymentSlots, productSlot) {
            return deploymentSlots.filter(slot => slot.product_slot_id === productSlot.id);
        },
        getConnections(deploymentSlot) {
            /*
             * Map a render kap to all remote and local connections to prevent from render key conflict,
             * when a connection is connected to itself.
             */

            let localConnections =  deploymentSlot.local_connections.map(item =>  ({...item, render_key: `local_connection_${item.id}`})),
                remoteConnections = deploymentSlot.remote_connections.map(item =>  ({...item, render_key: `remote_connection_${item.id}`}))

            return localConnections.concat(remoteConnections);
        },
        insertDeploymentSlot(deployment, productSlot) {
            this.$loading.show({delay: 0, background: "#444"});

            this.$inertia
                .post(`/api/deployments/${deployment.id}/slots/add/${productSlot.id}/`)
                .then(() => {
                    // TODO
                })
                .catch(function (error) {
                    alert(error.response.data.message);
                    console.log(error.response.data);
                })
                .finally(() => {
                    this.$loading.hide();
                });
        },
        deleteDeploymentSlot(deploymentSlot) {
            this.$inertia.delete(`/api/deployments/${this.entityData.id}/slots/${deploymentSlot.id}`);
        },
        deleteChildDeployment(childDeployment) {
            this.$inertia.delete(`/api/deployments/${childDeployment.id}/clearDeployedAt`);
        },
        deleteConnection(deploymentSlot, connection) {
            this.$inertia.delete(`/api/deployments/${this.entityData.id}/slots/${deploymentSlot.id}/disconnect/${connection.pivot.id}`);
        },
        identityChanged(data) {
            this.entityData.identity_type = data.type;
            this.entityData.identity_id = data.item.id
        },
        assetChanged(assetObject) {
            // console.log(assetObject.id);
            this.entityData.asset_id = assetObject.id;
            if (!this.updatePage) {
                this.entityData.info = assetObject.product.default_info;
            }
        },
    },
};
</script>

<style>
.deploymentSlot {
    margin: 3px;
    padding: 3px;
}

.productSlot {
    border: 3px solid black;
    margin: 3px;
    padding: 3px;
}

.deployment {
    background-color: lightgray;
    margin: 3px;
    padding: 3px;
}
</style>
