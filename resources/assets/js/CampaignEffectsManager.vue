<template>
    <div class="campaignEffectsManager">
        <h3>Effects <button @click.prevent="addNewEffect" class="button button-primary--mini">Add New Effect</button></h3>
        <campaign-effect
            class="effect"
            v-for="effect, key in effectsForms"
            :key="effect.key"
            :effect-key="key"
            :effect="effect"
            @remove="removeEffect"
        >
        </campaign-effect>
    </div>
</template>

<script>
import CampaignEffect from './CampaignEffect.vue'
export default {
    components: { CampaignEffect },
        props: {
            effects: {
                type: Array,
                default: [],
            }
        },
		data: () => ({
            effectsForms: [],
        }),
		mounted() {
            this.populateEffectsForms()
		},
        methods: {
            populateEffectsForms() {
                this.effectsForms = this.effects.map((effect) => {
                    return { ...effect, key: this.generateKey() }
                })
            },
            addNewEffect() {
                this.effectsForms.push({
                    type: null,
                    value: null,
                    context: null,
                    key: this.generateKey()
                })
            },
            removeEffect(key) {
                this.effectsForms.splice(key, 1)
            },
            generateKey() {
                return (Math.random() + 1).toString(36).substring(7)
            }
        }
	}
</script>
