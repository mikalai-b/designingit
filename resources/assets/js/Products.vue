<template>
    <div class="portal-products">
        <h3>Your Cart</h3>
        <div class="portal-product" v-for="(item, index) in items" :key="index">
            <div
                class="portal-product-image"
                :class="`${
                    item.product.id === '642edd7e-d3cf-4a7a-9e9a-db683a7a0b39' ||
                    item.product.id === '009cad7b-08a1-4de9-afbc-b30f7c824d7d' ||
                    item.product.id === 'b16fa3da-48ad-470e-8d9c-4f592a2b5765' ||
                    item.product.id === '74536e54-1207-45b6-a889-3c1776787ef7' ||
                    item.product.id === 'a3fb0ef6-0225-4b1b-ac0f-2b25576e8419'
                ? 'no--paddings' : ''}`"
            >
                <img
                    v-if="
                    item.product.id === '642edd7e-d3cf-4a7a-9e9a-db683a7a0b39' ||
                    item.product.id === '009cad7b-08a1-4de9-afbc-b30f7c824d7d' ||
                    item.product.id === 'b16fa3da-48ad-470e-8d9c-4f592a2b5765' ||
                    item.product.id === '74536e54-1207-45b6-a889-3c1776787ef7' ||
                    item.product.id === 'a3fb0ef6-0225-4b1b-ac0f-2b25576e8419'
                    "
                    :src="getImageUrl(item.product.thumbnail)"
                    :alt="item.product.name"
                >
                <img
                    v-else
                    :src="item.product.thumbnail"
                    :alt="item.product.name"
                >
            </div>
            <div
                class="portal-product-video"
                v-if="
                item.product.id === '642edd7e-d3cf-4a7a-9e9a-db683a7a0b39' ||
                item.product.id === '009cad7b-08a1-4de9-afbc-b30f7c824d7d' ||
                item.product.id === 'b16fa3da-48ad-470e-8d9c-4f592a2b5765' ||
                item.product.id === '74536e54-1207-45b6-a889-3c1776787ef7' ||
                item.product.id === 'a3fb0ef6-0225-4b1b-ac0f-2b25576e8419'
                "
            >
                <video width="100%" muted autoplay loop>
                    <source :src="`/video/${getVideoUrl(item.product.thumbnail)}`" type="video/mp4">
                    Your browser does not support HTML video.
                </video>
            </div>
            <div class="portal-product-info--wrapper">
                <div class="portal-product-info">
                    <span class="portal-product-text">
                        {{ item.product.fullName }},
                        {{ item.product.quantity }}
                        <!--<template v-if="item.product.prescriptionOnly">, Rx only</template> ({{ item.product.info }})-->
                    </span>
                    <span><span v-if="item.price != item.product.price"><s>{{ item.product.price|currency }}</s><br /></span>{{ getPriceForItem(item)|currency }}</span>
                </div>
                <a class="cart-action--light" :href="`/cart/items/${item.rowId}`">
                    <span class="remove"></span>
                    Remove {{ item.name }} {{ item.product.strength }}
                </a>
            </div><!-- .portal-product-info--wrapper -->
        </div><!-- .portal-product -->
        <span class="portal-product-text"><small>Free shipping (always included)</small></span>
        <template v-if="couponCode">
            <div class="portal-product-info portal-product-info--sub">
                <span class="portal-product-text">Subtotal:</span>
                <span class="portal-product-text">{{ subtotal|currency }}</span>
            </div>
            <div class="portal-product-info portal-product-info--sub">
                <span class="portal-product-text">Discount/Voucher Code:</span>
                <span class="portal-product-text">{{ couponCode }}</span>
            </div>
            <div class="portal-product-info portal-product-info--final">
                <span class="portal-product-text">Total: <span class="note" v-if="firstShipmentNote">{{ firstShipmentNote }}</span></span>
                <span class="portal-product-text">{{ firstShipmentTotal|currency }}</span>
            </div>
        </template>
        <template v-else>
            <div class="portal-product-info portal-product-info--final">
                <span class="portal-product-text">Total:</span>
                <span class="portal-product-text">{{ subtotal|currency }}</span>
            </div>
        </template>
    </div><!-- portal-products -->
</template>
<script>
import { EventBus } from './event-bus.js';
import axios from 'axios';
export default {
    props: {
        // items: {},
        initialCouponCode: {},
        unlimitedCouponCode: {
            type: Boolean
        }
    },
    data() {
        return {
            couponCode: null,
            cart: null,
        }
    },
    filters: {
        currency(value) {
            return '$'+parseFloat(value).toFixed(2);
        }
    },
    watch: {
    },
    computed: {
        getVideoUrl() {
            return (url) => {
                let vidUrl = url.split('/images/thumbnails/').join('')
                vidUrl = vidUrl.split('.jpg').join('.mp4')
                return vidUrl
            }
        },
        getImageUrl() {
            return (url) => {
                let imageUrl = url.split('.jpg').join('.png')
                return imageUrl
            }
        },
        items() {
            return this.cart ? this.cart.items : []
        },
        subtotal() {
            let subtotal = 0;
            this.items.forEach((item) => {
                subtotal += this.getPriceForItem(item)
            });
            return subtotal;
        },
        firstShipmentTotal() {
            let subtotal = 0;
            this.items.forEach((item) => {
                subtotal += this.getFirstShipmentPriceForItem(item)
            });
            return subtotal;
        },
        firstShipmentNote() {
            if (this.firstShipmentTotal === 0) {
                if (this.unlimitedCouponCode) {
                    return '(First shipment is FREE!)'
                }
                return '(First shipment is pre-paid)'
            }
            return ''
        },
        cartQueryString() {
            if (this.couponCode) {
                return `?couponCode=${this.couponCode}`
            }
            return ''
        }
    },
    mounted() {
        if (this.initialCouponCode) {
            this.couponCode = this.initialCouponCode;
        }
        EventBus.$on('validatedCode', couponCode => {
            this.couponCode = couponCode;
            this.retrieveCurrentOrder()
        });
        EventBus.$on('removedCode', () => {
            this.couponCode = null;
            this.retrieveCurrentOrder()
        });
        this.retrieveCurrentOrder();
    },
    methods: {
        getPriceForItem(item) {
            return item.price;
        },
        getFirstShipmentPriceForItem(item) {
            return this.items.firstShipmentPrice !== null ? item.firstShipmentPrice : item.price;
        },
        retrieveCurrentOrder() {
            axios.get(`/api/v1/cart${this.cartQueryString}`)
                .then((response) => {
                    if (response.data.data) {
                        this.cart = response.data.data
                    }
                })
                .catch(function (error) {
                })
        }
    }
}
</script>