<template>
    <div v-if="entities.length" class="mt-3 max-w-6xl mx-auto">
        <h1 class="text-xl font-bold">
            {{ $t("deployments") }}

            <el-button v-if="isViewPage" class="float-right" type="primary" size="small" @click="updateAll">{{
                    $t("save_all")
                }}</el-button>
        </h1>
        <div class="bg-white">
            <div class="mt-1">
                <table class="min-w-full text-center">
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
                            <td v-if="isViewPage" class="font-medium text-gray-900  p-2">
                                {{ $t("status") }}
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(entity, deploymentIndex) in entities"
                            :key="deploymentIndex"
                        >
                            <td class="font-medium text-gray-900 whitespace-nowrap">
                                <input
                                    v-model="entity.a_number"
                                    :placeholder="$t('a_number')"
                                    class="w-full border-gray-200 input-filled"
                                    type="text"
                                >
                                <ErrorMessage
                                    :error="$page.props.errors[`assets.${deploymentIndex}.a_number`]"
                                />
                            </td>
                            <td class="font-medium text-gray-900 whitespace-nowrap">
                                <input
                                    v-model="entity.serial_number"
                                    :placeholder="$t('serial_number')"
                                    class="w-full border-gray-200 input-filled"
                                    type="text"
                                >
                                <ErrorMessage
                                    :error="$page.props.errors[`assets.${deploymentIndex}.serial_number`]"
                                />
                            </td>
                            <td class="font-medium text-gray-900 whitespace-nowrap">
                                <input
                                    v-model="entity.description"

                                    :placeholder="$t('comment')"
                                    class="w-full border-gray-200 nput-filled"
                                    type="text"
                                >
                                <ErrorMessage
                                    :error="$page.props.errors[`assets.${deploymentIndex}.description`]"
                                />
                            </td>

                            <td v-if="isViewPage || entity.id" class="font-medium text-gray-900 whitespace-nowrap">
                                <el-tag type="success"  v-if="entity.is_deployed">
                                    {{  $t('deployed') }}
                                </el-tag>
                                <el-tag type="warning" v-else>
                                    {{$t('delivered')}}
                                </el-tag>
<!--                                <button v-if="entity.id" @click="update(entity)">-->
<!--                                    {{ $t('edit') }}-->
<!--                                </button>-->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import ResponseMixin from "@/Mixins/ResponseMixin";
import AlertMixin from "@/Mixins/AlertMixin";

export default {
    name: "AssetsForm",
    components: {ErrorMessage},
    mixins: [ResponseMixin,AlertMixin],
    props: {

        isViewPage: {
            type: Boolean,
            default: false,
        },
        quantity: {
            required: true,
        },
        assets: {
            type: Array
        },
        basedOnQuantity:{
            default:false,
            type:Boolean
        }
    },
    data() {
        return {
            amount: 0,
            entities: []
        };
    },
    watch: {
        quantity: {
            handler(value) {
                this.reloadAssets();
            }
        },
        entities: {
            deep: true,
            handler(value) {
                this.$emit("input", this.entities);
            },
        },
    },
    created() {
        // TODO:  We need a single source of truth here.
        if (this.assets && !this.basedOnQuantity) {
            this.entities = this.assets;
        } else {
            console.log(this.quantity)
            this.amount = parseInt(this.quantity);
            this.reloadAssets();
        }
    },
    methods: {
        updateAll() {
            this.$inertia.post(route("api.assets.update_all"),{
                'assets': this.entities
            });
            this.handleResponse(null, "Success", "Assets Status");
        },
        update(entity) {
            this.$inertia.put(route("api.assets.update",entity.id), entity);
            this.handleResponse(null, "Success", "Assets Status");

        },
        reloadAssets() {
            this.amount = parseInt(this.quantity);
            let entities = Array.from(this.assets);
            let entitesLen = entities.length;
            this.entities = [];
            for (let i = 0; i < this.amount; i++) {
                if (i < entitesLen) {
                    this.entities.push(entities[i]);
                } else {
                    this.entities.push({serial_number: "", a_number: "", description: ""});
                }

            }
        }
    }
};
</script>

<style scoped>

</style>
