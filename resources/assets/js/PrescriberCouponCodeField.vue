<template>
    <div class="prescriber">

        <div class="modal__wrapper" v-if="modalIsActive">
            <div class="modal__screen" @click.prevent="closeModal"></div>
            <div class="modal">
                <div class="modal__header">
                    <a href="#" class="modal__close" @click.prevent="closeModal"><i class="fa fa-times"></i></a>
                </div>
                <div class="modal__body">
                    <template>
                        Enter voucher code in the field below and click the <em>Redeem Voucher</em> button.<br /><br />
                        <input type="text" v-model="code" class="short" v-on:keydown.enter.prevent="validate"> <button class="button button-secondary--small" @click.prevent="validate">Redeem Voucher</button>
                        <slot v-if="!hasAttemptedValidation"></slot>
                        <div class="help-block" v-if="codeErrors.length">
                            <div class="errorMessage" v-for="error, key in codeErrors" :key="`error${key}`" v-html="error"></div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <template v-if="validatedCode">
            <span class="couponCode__code">
                {{ code }} <a href="#" @click.prevent="removeCode">[remove]</a>
            </span>
        </template>
        <template v-else>
            N/A <button class="button button-secondary button-secondary--small" @click.prevent="openModal">Add Voucher Code</button>
        </template>
        
    </div>
</template>
<script>
import axios from 'axios';
export default {
    props: {
        initialCode: {
            default: null
        },
        orderId : {
            required: true
        }
    },
    data() {
        return {
            modalIsActive: false,
            code: null,
            errors: [],
            isValid: null,
            validatedCode: null,
            hasAttemptedValidation: false,
            confirmingRemoval: false
        }
    },
    computed: {

        codeErrors() {
            if (this.errors && this.errors['code']) {
                return this.errors['code'];
            }
            return [];
        }
    },
    mounted() {
        this.code = this.validatedCode = this.initialCode;
    },
    methods: {
        validate() {
            this.errors = []
            this.code = this.code.trim();
			axios.post(`/api/v1/orders/${this.orderId}/apply-coupon-code`, {
                order_id: this.orderId,
                code: this.code,
                mode: 'prescriber',
            })
				.then((response) => {
                    this.isValid = response.data.valid;
                    this.hasAttemptedValidation = true;
                    
                    this.validatedCode = this.code;
                    this.modalIsActive = false;
                        
                })

                .catch((error) => {
                    if (error.response.data) {
                        this.errors = error.response.data.errors
                    }
                });
        },
        removeCode() {
            axios.post(`/api/v1/orders/${this.orderId}/remove-coupon-code`)
                .then((response) => {
                    this.validatedCode = null
                    this.code = null
                })
        },
        openModal() {
            this.modalIsActive = true;
        },
        closeModal() {
            this.modalIsActive = false;
            Object.assign(this.$data, this.$options.data.apply(this));
        }
    }
}
</script>