<template>
    <transition name="fade">
        <div
            v-if="show"
            :class="[
                'p-4 rounded-lg text-sm fixed bottom-4 right-4 z-50 shadow-lg',
                alertType === 'success'
                    ? 'bg-green-100 text-green-700'
                    : alertType === 'error'
                    ? 'bg-red-100 text-red-700'
                    : alertType === 'warning'
                    ? 'bg-yellow-100 text-yellow-700'
                    : 'bg-blue-100 text-blue-700',
            ]"
            role="alert"
        >
            <span>{{ message }}</span>
            <button @click="closeAlert" class="ml-2 font-bold text-lg">
                &times;
            </button>
        </div>
    </transition>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            default: "Alert",
        },
        message: {
            type: String,
            required: true,
        },
        alertType: {
            type: String,
            default: "info", // Can be 'success', 'error', 'warning', 'info'
        },
        show: {
            type: Boolean,
            required: true, // Control the visibility from the parent
        },
    },
    methods: {
        closeAlert() {
            this.$emit("update:show", false); // Emit event to parent to hide alert
        },
    },
};
</script>

<style scoped>
/* Optional: Fade effect when showing or hiding the alert */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>