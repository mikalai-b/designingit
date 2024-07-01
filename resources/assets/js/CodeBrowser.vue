<template>
    <div class="codeBrowser">
        <div class="codeBrowser__code" v-for="code, key in codes" :key="key" :class="code.redeemed ? '-redeemed' : null">{{ code.code }}</div> <div v-if="moreCodes"> ({{ moreCodes }} more codes...)</div>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    props: {
        campaignId: {
            type: Number,
            required: true
        },
        codeCount: {
            type: Number,
        }
    },
    data: () => ({
        codes: []
    }),
    mounted() {
        this.retrieveCodes()
    },
    computed: {
        moreCodes() {
            if (this.codeCount > this.codes.length) {
                return this.codeCount - this.codes.length;
            }
            return 0;
        }
    },
    methods: {
        retrieveCodes() {
            axios.get(`/api/v1/campaigns/${this.campaignId}/coupon-codes`)
                .then((response) => {
                    this.codes = response.data
                })
        }
    },
}
</script>