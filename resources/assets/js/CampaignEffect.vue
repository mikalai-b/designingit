<template>
    <div class="campaignEffect">
        <div class="form-group campaignEffect__column">
            <label>Type:</label>
            <select :name="`effects[${effectKey}][type]`" v-model="effectForm.type">
                <option value="percent_discount">Percentage-based discount</option>
                <option value="value_discount">Value-based discount</option>
                <option value="arbitrary_pricing">Arbitrary pricing</option>
            </select>
        </div>
        <div class="form-group campaignEffect__column">
            <label>Context:</label>
            <select :name="`effects[${effectKey}][context]`" v-model="effectForm.context">
                <option value="first_shipment">First shipment</option>
                <option value="second_shipment">Second shipment</option>
                <option value="all_shipments">All shipments</option>
            </select>
        </div>
        <div class="form-group campaignEffect__column">
            <label>Value:</label>
            <input type="text" :name="`effects[${effectKey}][value]`" v-model="effectForm.value" class="medium">
        </div>
        <div class="form-group campaignEffect__column">
            <button @click.prevent="removeEffect">Remove</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        effect: {
            type: Object,
            default: () => ({})
        },
        effectKey: {
            type: Number,
            default: 0
        }
    },
    data: () => ({
        effectForm: {
            type: null,
            value: null,
            context: null,
        },
    }),
    mounted() {
        this.populateEffectsForm()
    },
    methods: {
        populateEffectsForm() {
            if (this.effect) {
                this.effectForm = {...this.effect}
            }
        },
        removeEffect() {
            this.$emit('remove', this.effectKey)
        }
    }
}
</script>