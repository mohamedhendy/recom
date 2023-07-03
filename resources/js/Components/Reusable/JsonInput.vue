<template>
    <div class="field field--json-input">
        <vue-json-editor
            :expanded-on-start="true"
            :mode="mode"
            :modes="modes"
            :show-btns="false"
            :value="jsonData"
            @json-change="onJsonChange"
        />
        <ErrorMessage :error="errorMessage"/>
    </div>
</template>

<script>
import vueJsonEditor from 'vue-json-editor';
import ErrorMessage from "@/Components/Reusable/ErrorMessage";

export default {
    components: {
        vueJsonEditor,
        ErrorMessage
    },
    props: {
        errorMessage: {
            validator: prop => typeof prop === 'string' || prop === null,
            default: null
        },
        json: {
            type: String,
            default: null
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        jsonData() {
            return JSON.parse(this.json);
        },
        mode() {
            if(this.disabled)
                return "view";
            return "tree";
        },
        modes() {
            if(this.disabled)
                return ["view"];
            return ["tree", "code", "form", "text", "view"];
        }
    },
    methods: {
        onJsonChange(value) {
            console.log('value:', value);
            this.$emit('json-changed', JSON.stringify(value));
        }
    }
};
</script>
