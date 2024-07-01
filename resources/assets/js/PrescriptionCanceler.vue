<template>
    <div>
        <div class="modal__wrapper" v-if="modalOpen">
            <div class="modal__screen" @click.prevent="closeModal"></div>
            <div class="modal">
                <div class="modal__header">
                    <a href="#" class="modal__close" @click.prevent="closeModal"><i class="fa fa-times"></i></a>
                </div>
                <div class="modal__body">
                    <h3 class="modal__heading">Cancel Your Prescription</h3>
                    <div class="formError" v-for="(error, key) in errors" :key="key">
                        {{ error }}
                    </div>
                    <p><strong>Are you sure you wish to cancel this prescription? This action cannot be undone.</strong></p>
                    <p>If you'd like to pause your refills or adjust the refill frequency, you may do so by clicking on the <em>Refill Settings</em> button.</p>
                    <div class="modal__actions modal-actions">
                        <div class="modal-actions--left">
                            <button class="button button-skin--small button-icon" @click.prevent="cancel">
                                <i :class="isLoading('cancel') ? 'fa fa-spinner fa-spin' : 'fas fa-times'"></i>Cancel Prescription
                            </button>
                            <p><a href="#0" @click.prevent="closeModal">No, Return to Dashboard</a></p>
                        </div><!-- .modal-actions--left -->
                    </div><!-- .modal__actions -->
                </div>
            </div>
        </div>
        <a href="#" class="card-action card-action--cancel" @click.prevent="openModal"><i class="fas fa-times-circle"></i>Cancel</a>
    </div>
</template>
<script>
import axios from 'axios';
export default {
    props: {
        prescriptionId: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            modalOpen: false,
            loading: {},
            errors: []
        }
    },
    mounted() {
        this.initializeLoadingObject();
    },
    computed: {
        initialLoadingObject() {
            return {
                cancel: false,
            };
        }
    },
    methods: {
        initializeLoadingObject() {
            this.loading = this.initialLoadingObject;
        },
        isLoading(action) {
            return this.loading[action] === true;
        },
        openModal() {
            this.modalOpen = true;
        },
        closeModal() {
            this.modalOpen = false;
        },
        cancel() {
            this.loading.cancel = true;
            let url = `/api/v1/prescriptions/${this.prescriptionId}/cancel`;
            axios.post(url, {
                
            }).then((response) => {
                if (response.data.success) {
                    if (response.data.redirect) {
                        location.href = response.data.redirect;
                    } else {
                        location.reload();
                    }
                }
            });
        },
        resetLoader() {
            this.loading = this.initialLoadingObject;
        },

    }
}
</script>