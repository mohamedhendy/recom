<template>
    <div>
        <el-input-number
            v-if="!disabled"
            v-model="inputValue"
            :disabled="disabled"
            :label="title"
            :max="max"
            :min="min"
            :placeholder="title"
            :controls="false"
            class="text-center w-full"
            suffix-icon="el-icon-coin"
            @change="publish"
        />
        <div v-else>
            {{ inputValue }}
        </div>
        <ErrorMessage
            :error="error"
        />
    </div>
</template>

<script>
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
export default {
    components: {ErrorMessage},
    props: {
        min: {
            defualt: 0,
            type: Number,
        },
        max: {
            type: Number,
            default: 100000
        },
        value: {},
        title: {
            default: "",
        },
        isInteger: {
            type: Boolean,
            defualt: false
        },
        disabled: {
            type: Boolean,
            default: false,
        },
        error: {
            default: ""
        }
    },
    data: () => ({
        inputValue: 0,
    }),
    watch: {
        value: {
            handler(val) {
                this.inputValue = val;
            },
        },
    },
    created() {
        this.inputValue = this.value;
    },
    methods: {
        publish() {
            let validatedValue = this.isInteger ? parseInt(this.inputValue) : parseFloat(this.inputValue).toFixed(2);
            if(validatedValue < this.min) {
                this.validatedValue = this.min
                this.inputValue = this.min
            }

            this.inputValue = validatedValue;
            if (!validatedValue) {
                validatedValue = 0;
                this.inputValue = validatedValue;
            }
            this.$emit("input", validatedValue);
            this.$emit("change", validatedValue);
        },
    },
};
</script>

<style>
</style>
