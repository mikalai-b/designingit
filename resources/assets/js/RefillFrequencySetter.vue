<template>
    <div>
        <div class="modal__wrapper" v-if="modalOpen">
            <div class="modal__screen" @click.prevent="closeModal"></div>
            <div class="modal">
                <div class="modal__header">
                    <a href="#" class="modal__close" @click.prevent="closeModal"><i class="fa fa-times"></i></a>
                </div>
                <div class="modal__body">
                    <h3 class="modal__heading">Update Refill Frequency</h3>
                    <div class="formError" v-for="(error, key) in errors" :key="key">
                        {{ error }}
                    </div>
                    <template v-if="isPaused">
                        <p>Refills for this prescription are currently <em>paused</em>. You may resume refills by clicking the button below</p>
                    </template>
                    <template v-else>
                        <p>Select your desired refill frequency:</p>
                        <select v-model="selectedPeriod" @change="clearErrors">
                            <option v-for="period in availablePeriodsForDropdown" :key="period" :value="period">
                                <template v-if="isNaN(period)">
                                    {{ period }}
                                </template>
                                <template v-else>
                                    Every {{ period | toMonths }} month{{ period !== 30 ? 's' : null }}
                                </template>
                            </option>
                        </select>
                    </template>
                    <p>
                    <div class="modal__actions modal-actions">
                        <div class="modal-actions--left">
                            <button v-if="isPaused" class="modal-action--rightbutton button button-blue--small button-icon" @click.prevent="resume">
                                <i :class="isLoading('resume') ? 'fa fa-spinner fa-spin' : 'fas fa-play'"></i>Resume Refills
                            </button>
                            <button v-else class="button button-primary--small button-icon" @click.prevent="updateFrequency">
                                <i :class="isLoading('save') ? 'fa fa-spinner fa-spin' : 'fas fa-check'"></i>Save Settings
                            </button>
                        </div><!-- .modal-actions--left -->
                    </div><!-- .modal__actions -->
                </div>
            </div>
        </div>
        <a href="#" class="card-action" @click.prevent="openModal"><i class="fas fa-prescription-bottle-alt"></i>Refill Settings</a>
    </div>
</template>
<script>
import axios from 'axios';
const pauseLabel = 'Pause Refills';
export default {
    filters: {
        toMonths(days) {
            return parseInt(days / 30);
        }
    },
    props: {
        availablePeriods: {
            type: Array,
        },
        initialSelectedPeriod: {
            type: Number
        },
        prescriptionId: {
            type: Number,
            required: true,
        },
        isPaused: {
            type: Boolean
        },
        allowNull: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            modalOpen: false,
            selectedPeriod: null,
            loading: {},
            errors: []
        }
    },
    mounted() {
        this.selectedPeriod = this.initialSelectedPeriod;
        this.initializeLoadingObject();
    },
    computed: {
        availablePeriodsForDropdown() {
            let availablePeriods = this.availablePeriods;

            availablePeriods.push(pauseLabel);

            return availablePeriods;
        },
        initialLoadingObject() {
            return {
                save: false,
                resume: false,
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
        clearErrors() {
            this.errors = [];
        },
        updateFrequency() {
            this.clearErrors();
            if (this.selectedPeriod || this.allowNull) {
                this.loading.save = true;
                if (this.selectedPeriod === pauseLabel) {
                    this.pause();
                } else {
                    let url = `/api/v1/prescriptions/${this.prescriptionId}/refill-frequency`;
                    axios.post(url, {
                        period: this.selectedPeriod,
                        lineItemId: this.lineItemId
                    }).then((response) => {
                        this.finishSettingsUpdate(response);
                    });
                }
            } else {
                this.errors.push('Please select a refill frequency.');
            }
        },
        resetLoader() {
            this.loading = this.initialLoadingObject;
        },
        pause() {
            this.clearErrors();
            let url = `/api/v1/prescriptions/${this.prescriptionId}/pause`;
            axios.post(url, {

            }).then((response) => {
                this.finishSettingsUpdate(response);
            });
        },
        finishSettingsUpdate(response) {
            if (response.data.success) {
                if (response.data.redirect) {
                    location.href = response.data.redirect;
                } else {
                    location.reload();
                }
            }
        },
        resume() {
            this.clearErrors();
            this.loading.resume = true;
            let url = `/api/v1/prescriptions/${this.prescriptionId}/resume`;
            axios.post(url, {
                
            }).then((response) => {
                if (response.data.redirect) {
                    location.href = response.data.redirect;
                } else {
                    location.reload();
                }
            });
        }

    }
}
</script>