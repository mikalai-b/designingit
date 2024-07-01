<template>
    <div class="offerManagerModal modal__wrapper">
        <div class="modal__screen" @click.prevent="cancel"></div>
        <div class="modal">
            <div class="modal__header">
                <a href="#" class="modal__close" @click.prevent="cancel"><i class="fa fa-times"></i></a>
            </div>
            <div class="modal__body">
                <p><slot>Are you sure you wish to perform this action?</slot></p>
                <button
                    class="button button-primary"
                    @click.prevent="confirm"
                >Yes</button>
                <button
                    class="button button-secondary"
                    @click.prevent="cancel"
                >No, Cancel!</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    methods: {
        confirm() {
            this.$emit('confirm')
        },
        cancel() {
            this.$emit('cancel')
        },
    }
}
</script>