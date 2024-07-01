<template>
    <span>
        <the-mask type="text" v-model="value" mask="+1 (###) ###-####" />
        <input type="hidden" :name="name" :value="value">
    </span>
</template>
<script>
import {TheMask} from 'vue-the-mask'
export default {
    components: {
        TheMask
    },
    props: {
        name: {
            type: String,
            default: 'phone'
        },
        initialValue: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            value: null
        }
    },
    mounted() {
        this.value = this.initialValue;
    },
    methods: {
        format() {
            let value = this.value;
            var x = value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            value = !x[2] ? x[1] : x[1] + '-' + x[2] + (x[3] ? '-' + x[3] : '');
            this.value = value;
        }
    }
}
</script>