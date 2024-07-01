<template>
    <div class="offerManagerModal modal__wrapper">
        <div class="modal__screen" @click.prevent="closeModal"></div>
        <div class="modal">
            <div class="modal__header">
                <a href="#" class="modal__close" @click.prevent="closeModal"><i class="fa fa-times"></i></a>
            </div>
            <div class="modal__body">
                <p>Select the product you would like to associate with this campaign.</p>
                <div class="form-group">
                    <select v-model="form.productId">
                        <option v-for="product, key in products" :key="key" :value="product.id">{{ product.name }} {{ product.strength }} ({{ product.quantity }})</option>
                    </select>
                </div>
                <div class="form-group" v-if="form.productId">
                    <label><input type="checkbox" v-model="overridePricing" value="1"> Override pricing for this product</label>
                </div>
                <template v-if="overridePricing">
                    <div class="form-group">
                        <label>Base Price:</label>
                        <input type="text" v-model="form.price" />
                    </div>
                    <div class="form-group">
                        <label>First Shipment Price:</label>
                        <input type="text" v-model="form.firstShipmentPrice" />
                    </div>
                </template>
                <div class="form-group" v-if="form.productId">
                    <label><input type="checkbox" v-model="overrideSuccessMessage" value="1"> Override success message for this product</label>
                </div>
                <template v-if="overrideSuccessMessage">
                    <div class="form-group">
                        <label>Success Message:</label>
                        <textarea v-model="form.successMessage"></textarea>
                    </div>
                </template>
                <button
                    v-if="form.productId"
                    class="button button-primary"
                    @click.prevent="save"
                >Save</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        products: {
            type: Array
        },
        offer: {
            type: Object
        }
    },
    data: () => ({
        form: {
            productId: null,
            price: null,
            firstShipmentPrice: null,
            successMessage: null,
        },
        overridePricing: false,
        overrideSuccessMessage: false,
    }),
    mounted() {
        if (this.offer) {
            this.populateFormFromOffer()
        }
    },
    methods: {
        populateFormFromOffer() {
            this.form.productId = this.offer.product.id
            this.form.price = this.offer.price
            this.form.firstShipmentPrice = this.offer.firstShipmentPrice
            this.form.successMessage = this.offer.successMessage
            if (this.form.price || this.form.firstShipmentPrice) {
                this.overridePricing = true
            }
            if (this.form.successMessage) {
                this.overrideSuccessMessage = true
            }
        },
        reset() {
            this.form = {
                productId: null,
                price: null,
                firstShipmentPrice: null,
            }
            this.overridePricing = false
            this.overrideSuccessMessage = false
        },
        closeModal() {
            this.$emit('close')
            this.reset()
        },
        save() {
            if (!this.overridePricing) {
                this.form.price = null
                this.form.firstShipmentPrice = null
            }
            if (!this.overrideSuccessMessage) {
                this.form.successMessage = null
            }
            this.$emit('save', this.form)
            this.reset();
        }
    }
}
</script>