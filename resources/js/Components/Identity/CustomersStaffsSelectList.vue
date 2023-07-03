<template>
    <div class="" :class="{'w-full block': !hideTitle}">
        <label class="demonstration" v-if="!hideTitle">
            {{_.get($page.props, 'screen.labels.choose_client', $t('customer')) + ' / ' + $t('staff')}}
        </label>
        <div>
            <el-select v-model="selectedIndex" :disabled="isViewPage" :placeholder="$t('customers')" class="w-full"
                       filterable
                       @change="publish"
            >
                <el-option v-for="(item,index) in list" :key="index" :label="item.name"
                           :value="index"
                >
                    <span style="float: left">{{ item.full_name }}</span>
                    <span style="float: right; color: #8492a6; font-size: 13px">{{ item.item_type }}</span>
                </el-option>
            </el-select>
        </div>
        <ErrorMessage :error="error"/>
    </div>
</template>

<script>
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import axios from "axios";

export default {
    name: "CustomersStaffsSelectList",
    components: {ErrorMessage},
    props: {
        useSecoundDataSource: {
          type: Boolean,
            default: false
        },hideTitle: {
          type: Boolean,
            default: false
        },
        secondDataSource: {
            type: Array,
            default: null
        },
        cleanAfterSelect: {
            type: Boolean,
            default: true,
        },
        isViewPage: {
            type: Boolean,
            default: false,
        },
        baseValueId: {},
        baseValueType: {},
        error: {
            default: ""
        }
    },
    data() {
        return {
            loading: false,
            list: [],
            value: {},
            selectedIndex: ""
        }
    },
    created() {
        this.value = {id: parseInt(this.baseValueId), item_type: this.baseValueType};
        if (this.useSecoundDataSource) {
            this.updateListFromSecondDataSource();
        } else {
            this.getList();
        }
    },
    watch:{
        secondDataSource:{
            deep: true,
            handler(value) {
                if(this.useSecoundDataSource)
                    this.updateListFromSecondDataSource();
            }
        }
    },
    methods: {
        updateListFromSecondDataSource() {
            this.list = this.secondDataSource;
            this.setSelected();
        },
        publish() {
            let selectedItem = this.list[this.selectedIndex];

            this.$emit('input', {
                id: selectedItem ? selectedItem.id : "",
                type: selectedItem ? selectedItem.item_type : ''
            });
            this.$emit('change', {
                item: selectedItem ? selectedItem : {},
                type: selectedItem ? selectedItem.item_type : ''
            });
            if (this.cleanAfterSelect)
                this.selectedIndex = ""
        },
        setSelected() {
            const index = this.list.findIndex(p => p.id === this.value.id && p.item_type === this.value.item_type);
            this.selectedIndex = index >= 0 ? index : "";
        },
        getList() {
            Promise.all([axios.get(route('api.customers.all')), axios.get(route('api.staffs.all'))]).then((values) => {
                const customers = values[0].data;
                const staffs = values[1].data;
                customers.forEach((item) => {
                    item.item_type = 'customer';
                    this.list.push(item);
                })

                staffs.forEach((item) => {
                    item.item_type = 'staff';
                    this.list.push(item);
                })
                this.setSelected();
            })
        }
    },

}
</script>

<style scoped>

</style>
