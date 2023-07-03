<template>
    <div class="flex flex-col">
        <label v-if="label" class="demonstration">{{ label }}</label>
        <div class="w-full">
            <el-select v-model="localValue" class="w-full" v-bind="$attrs">
                <el-option v-for="item in yearsList" :key="item" :label="item" :value="item"/>
            </el-select>
        </div>
        <ErrorMessage :error="_.get($page.props.errors, name)"/>
    </div>
</template>

<script>
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
import dayjs from "dayjs"

export default {
    name: "Field.Year",
    introduction: "A Select field to select years",
    components: {ErrorMessage},
    model: {
        prop: 'value',
        event: 'change'
    },
    props: {
        value: null,
        max: {
            type: String,
            default: null,
            note: "Maximum value for year in select field."
        },
        min: {
            type: String,
            default: null,
            note: "Minimum value for year in select field."
        },
        label: {
            type: String,
            default: null,
            note: "Label for the field."
        },
        name: {
            type: String,
            required: true,
            note: "Name of the field."
        }
    },
    data() {
        return {
            maxYear: null,
            minYear: null,
            yearsList: []
        }
    },
    computed: {
        localValue: {
            get() {
                return this.$props.value
            },
            set(value) {
                this.$emit('change', value)
            }
        }
    },
    created() {
        this.maxYear = parseInt(this.$props.max ? this.$props.max : dayjs().year())
        this.minYear = parseInt(this.$props.min ? this.$props.min : dayjs().year('2020').format('YYYY'))
        this.yearsList = _.range(this.minYear, this.maxYear + 1, 1).reverse()
    }
}
</script>

<style scoped>

</style>
