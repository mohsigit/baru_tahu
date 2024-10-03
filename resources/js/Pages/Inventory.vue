<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, watchEffect } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Modal from "@/Components/Modal.vue";
import inventoryService from "@/Services/inventory.service";
import AlertComponent from "@/Components/Alert.vue";
import AkuTable from "@/Components/AkuTable.vue";

// Define the correct prop to receive the inventory data
const props = defineProps({
    inventory: Array, // Inventory, not posts
});

const columns = [
    { field: "name", label: "Name", sortable: true },
    { field: "qty", label: "Deskripsi", sortable: true },
    { field: "action", width: "250px", label: "Action", sortable: false },
]; // untuk kolom table
const AkuTableRef = ref(null);

</script>

<template>
    <Head title="Inventory" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1>Inventory List</h1>
                        <AkuTable
                            :columns="columns"
                            ref="AkuTableRef"
                            :urls="'inventory/page'"
                        ></AkuTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
