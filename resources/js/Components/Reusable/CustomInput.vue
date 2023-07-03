<template>
    <div :class="classes">
        <label>
            {{ label }}
            <span v-if="required === true" class="text-red-400">*</span>
        </label>
        <input
            :value="value"
            :class="{ 'border-red-500': error }"
            :disabled="disabled"
            :placeholder="label"
            class="w-full"
            type="text"
            @change="change($event.target.value)"
            @input="input($event.target.value)"
        >
        <ErrorMessage :error="error"/>
    </div>
</template>

<script>
import ErrorMessage from "@/Components/Reusable/ErrorMessage";
export default {
    components: {ErrorMessage},
    props: {
        value: {
            // validator: prop =>
            //     typeof prop === 'string' ||
            //     typeof prop === 'number' ||
            //     prop === null,
            required: true
        },
        label: {
            type: String,
            required: true
        },
        required: {
            type: Boolean,
            defauLt: false
        },
        error: {
            type: String,
            default: ""
        },
        disabled: {
            type: Boolean,
            default: false
        },
        classes: {
            type: String,
            default: ""
        }
    },
    methods: {
        input(value) {
            this.$emit('input', value);
        },
        change(value) {
            this.$emit('change', value);
        }
    }
};
</script>
