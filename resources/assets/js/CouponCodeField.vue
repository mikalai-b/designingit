<template>
    <div class="couponCode">
        <div class="modal__wrapper" v-if="confirmingRemoval">
            <div class="modal__screen" @click.prevent="closeConfirmationModal"></div>
            <div class="modal">
                <div class="modal__header">
                    <a href="#" class="modal__close" @click.prevent="closeConfirmationModal"><i class="fa fa-times"></i></a>
                </div>
                <div class="modal__body">
                    <p>Are you sure you wish to remove your coupon code?</p>
                    <p><button class="button button-primary--small" @click.prevent="removeCode">Yes, remove coupon code</button> <button class="button button-secondary--small" @click.prevent="closeConfirmationModal">No, cancel</button></p>
                </div>
            </div>
        </div>
        <template v-if="!validatedCode">
            In order to redeem your voucher, enter your voucher code in the field below and click the <em>Redeem My Voucher</em> button.<br /><br />
            <input type="text" v-model="code" class="short" v-on:keydown.enter.prevent="validate"> <button class="button button-secondary--small" @click.prevent="validate">Redeem My Voucher</button>
            <slot v-if="!hasAttemptedValidation"></slot>
            <div class="help-block" v-if="codeErrors.length">
                <div class="errorMessage" v-for="error, key in codeErrors" :key="`error${key}`" v-html="error"></div>
            </div>
        </template>
        <div class="couponCode__success" v-else>
            <span class="couponCode__code">
                {{ code }} <a href="#" class="delete" @click.prevent="showConfirmationModal"><i class="fas fa-times-circle"></i></a>
            </span>
            <div v-html="finalSuccessMessage"></div>
        </div>
    
    </div>
</template>
<script>
import axios from 'axios';
import { EventBus } from './event-bus.js';
export default {
    props: {
        initialCode: {
            default: null
        },
    },
    data() {
        return {
            code: null,
            errorMessage: null,
            errors: [],
            successMessage: null,
            isValid: null,
            validatedCode: null,
            hasAttemptedValidation: false,
            confirmingRemoval: false,
            codeIsUnlimited: false
        }
    },

    computed: {
        finalSuccessMessage() {
            return this.successMessage
        },
        codeErrors() {
            if (this.errors && this.errors['code']) {
                return this.errors['code'];
            }
            return [];
        }
    },

    mounted() {
        this.code = this.initialCode;
        if (this.code) {
            this.validate();
        }
    },
    methods: {
        validate() {
            this.errors = []
            this.code = this.code.trim();
            this.validatedCode = this.errorMessage = this.successMessage = null;

                axios.post(`/api/v1/coupon-code/validate`, {
                    code: this.code
                })
                    .then((response) => {
                        this.isValid = response.data.valid;
                        this.hasAttemptedValidation = true;
                        this.codeIsUnlimited = response.data.isUnlimited;
                        if (this.isValid) {
                            this.validatedCode = this.code;
                            EventBus.$emit('validatedCode', this.validatedCode);
                            this.successMessage = response.data.message;
                        } else {
                            this.errorMessage = response.data.message;
                        }
                    })
                    .catch((error) => {
                        if (error.response.data) {
                            this.errors = error.response.data.errors
                        }
                    })

        },
        showConfirmationModal() {
            this.confirmingRemoval = true;
        },
        closeConfirmationModal() {
            this.confirmingRemoval = false;
        },
        removeCode() {
			axios.post(`/api/v1/coupon-code/remove`)
				.then((response) => {
                    if (response.data.success) {
                        this.code = this.validatedCode = this.errorMessage = null;
                        this.validated = false;
                        this.confirmingRemoval = false;
                        EventBus.$emit('removedCode');
                    }
				});
        }
        
    }
}
</script>