<template>
    <div class="offerManager">
        <offer-manager-modal
            v-if="modalIsActive && products.length"
            @close="closeModal"
            @save="saveOffer"
            :products="products"
            :offer="selectedOffer"
        ></offer-manager-modal>
        <confirmation-modal
            v-if="showDeleteModal"
            @confirm="performOfferRemoval"
            @cancel="cancelOfferRemoval"
        >
            Are you sure you wish to remove this product from the campaign?
        </confirmation-modal>

        <h3>Products <button @click.prevent="addProduct" class="button button-primary--mini">Add Product</button></h3>
        <table v-if="offers.length" class="list">
            <tr>
                <th></th>
                <th>Product</th>
            </tr>

            <tr v-for="offer, key in offers" :key="key">
                <td><a href="#" @click.prevent="selectOffer(offer)"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;<a href="#" @click.prevent="removeOffer(offer)"><i class="fa fa-trash"></i></a></td>
                <td>{{ offer.product.name }} {{ offer.product.strength }} ({{ offer.product.quantity }})</td>
            </tr>
        </table>
        <div v-else>
            <p class="message">There are no products associated with this campaign.</p>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    props: {
        campaignId: {
            type: Number,
            required: true,
        }
    },
    data: () => ({
        products: [],
        offers: [],
        modalIsActive: false,
        selectedOffer: null,
        showDeleteModal: false,
        offerToRemove: null
    }),
    mounted() {
        this.retrieveProducts()
        this.retrieveOffers()
    },
    computed: {
    },
    methods: {
        retrieveOffers() {
            axios.get(`/api/v1/offers?campaignId=${this.campaignId}`)
                .then((response) => {
                    if (response.data.data) {
                        this.offers = response.data.data
                    }
                })
                .catch(function (error) {
                })
        },
        retrieveProducts() {
            axios.get(`/api/v1/products`)
                .then((response) => {
                    if (response.data.data) {
                        this.products = response.data.data
                    }
                })
                .catch(function (error) {
                })
        },
        closeModal() {
            this.modalIsActive = false
            this.selectedOffer = null
        },
        addProduct() {
            this.modalIsActive = true
            this.selectedOffer = null
        },
        removeOffer(offer) {
            this.offerToRemove = offer
            this.showDeleteModal = true
        },
        cancelOfferRemoval() {
            this.offerToRemove = null
            this.showDeleteModal = false
        },
        performOfferRemoval() {
            axios.delete(`/api/v1/offers/${this.offerToRemove.id}`)
                .then((response) => {
                    this.retrieveOffers()
                    this.offerToRemove = null
                    this.showDeleteModal = false
                })
        },
        saveOffer(form) {
            axios.post(`/api/v1/offers`, {
                    campaignId: this.campaignId,
                    ...form
                })
                .then((response) => {
                    this.retrieveOffers()
                })
            this.modalIsActive = false
            this.selectedOffer = null
        },
        selectOffer(offer) {
            this.selectedOffer = offer
            this.modalIsActive = true
        },
    }
}
</script>
