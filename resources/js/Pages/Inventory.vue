<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, watchEffect } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Modal from "@/Components/Modal.vue";
import inventoryService from "@/Services/inventory.service";
import AlertComponent from "@/Components/Alert.vue";
import AkuTable from "@/Components/AkuTable.vue";
import PrimaryButton
    from "../../../vendor/laravel/breeze/stubs/inertia-vue-ts/resources/js/Components/PrimaryButton.vue";
import DangerButton from "../../../vendor/laravel/breeze/stubs/inertia-vue-ts/resources/js/Components/DangerButton.vue";

// Define the correct prop to receive the inventory data
const props = defineProps({
    inventory: Array, // Inventory, not posts
});

const columns = [
    { field: "name", label: "Name", sortable: true },
    { field: "qty", label: "Quantity", sortable: true },
    { field: "balance", label: "Balance", sortable: true },
    { field: "remarks", label: "Remarks", sortable: true },
    { field: "color", label: "Color", sortable: true },
    { field: "size", label: "Size", sortable: true },
    { field: "description", label: "Description", sortable: true },
    { field: "action", width: "250px", label: "Action", sortable: false },
]; // for table columns
const AkuTableRef = ref(null);

const showModal = ref(false);
const showDeleteModal = ref(false);
const form = ref({
    id: null,
    name: "",
    qty: 0,
    balance: 0,
    remarks: "",
    color: "",
    size: "",
    description:"",
});
const alertModel = ref({
    show: false,
    title: "",
    message: "",
    alertType: "",
});
const resetModel = () => {
    form.value.id = null;
    form.value.name = "";
    form.value.qty = 0;
    form.value.balance = 0;
    form.value.remarks = "";
    form.value.color = "";
    form.value.size = "";
    form.value.description = "";
};
const openModal = () => {
    resetModel();
    showModal.value = true;
};
const closeModal = () => {
    resetModel();
    showModal.value = false;
};
const editInventory = (inventory) => {
    form.value.id = inventory.id;
    form.value.name = inventory.name;
    form.value.qty = inventory.qty;
    form.value.balance = inventory.balance;
    form.value.remarks = inventory.remarks;
    form.value.color = inventory.color;
    form.value.size = inventory.size;
    form.value.description = inventory.description;
    showModal.value = true;
};
const saveInventory = async () => {
    try {
        const response = await inventoryService.store(form.value);
        if (response.status) {
            alertModel.value.show = true;
            alertModel.value.title = "Success";
            alertModel.value.message = response.message;
            alertModel.value.alertType = "success";
            loadItems();
            closeModal();
        } else {
            alertModel.value.show = true;
            alertModel.value.title = "Error";
            alertModel.value.message = response.message;
            alertModel.value.alertType = "error";
        }
    } catch (error) {
        alertModel.value.show = true;
        alertModel.value.title = "Error";
        alertModel.value.message = error.message;
        alertModel.value.alertType = "error";
    }
};
const arrDelete = ref([]);
const confirmDeleteInventory = () => {
    const getRows = AkuTableRef['value'].$refs.dataTableRemote.selectedRows
    if (getRows.length === 0) {
        alertModel.value.show = true
        alertModel.value.title = "Error";
        alertModel.value.message = 'Please select at least one item';
        alertModel.value.alertType = "error";
        return;
    }
    showDeleteModal.value = true;
    arrDelete.value = getRows
};

const deleteInventory = async () => {
    try {
        let loopEnd = false
        for (let i = 0; i < arrDelete.value.length; i++) {
            const response = await inventoryService.destroy(arrDelete.value[i]['id']);
            if (response.status) {
                alertModel.value.show = true;
                alertModel.value.title = "Success";
                alertModel.value.message = response.message;
                alertModel.value.alertType = "success";
            } else {
                alertModel.value.show = true;
                alertModel.value.title = "Error";
                alertModel.value.message = response.message;
                alertModel.value.alertType = "error";
            }
            if(i+1 === arrDelete.value.length){
                loopEnd = true
            }
        }
        if(loopEnd){
            await loadItems();
            closeDeleteModal();
        }
    } catch (error) {
        alertModel.value.show = true;
        alertModel.value.title = "Error";
        alertModel.value.message = error.message;
        alertModel.value.alertType = "error";
    }
};

// Close Delete Modal
const closeDeleteModal = () => {
    showDeleteModal.value = false;
};

// Load Items for AkuTable
const loadItems = async () => {
    await AkuTableRef.value["loadItems"]();
};

// Watch Effect
watchEffect(() => {
    if (alertModel.value.show) {
        setTimeout(() => {
            alertModel.value.show = false;
            alertModel.value.title = "";
            alertModel.value.message = "";
            alertModel.value.alertType = "";
        }, 5000);
    }
});
</script>

<template>
    <Head title="Inventory" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-8xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1>Inventory List</h1>
                        <div
                            class="md:px-3 md:py-4 flex flex-col md:flex-row gap-1.5 md:gap-1 md:justify-end"
                        >
                            <button
                                @click="openModal"
                                class="bg-blue-500 text-white px-4 py-2 rounded-xl"
                            >
                                Add
                            </button>
                            <danger-button
                                @click="confirmDeleteInventory"
                            >
                                delete
                            </danger-button>
                        </div>
                        <AkuTable
                            :columns="columns"
                            ref="AkuTableRef"
                            :urls="'inventory/page'"
                            :select-options="true"
                        >
                        <template #table-row="props">
                                <div v-if="props['column'].field === 'action'">
                                    <div class="flex gap-2">
                                        <button
                                            class="btn btn-warning btn-sm"
                                            @click="editInventory(props['row'])"
                                        >
                                            edit
                                        </button>
                                    </div>
                                </div>
                            </template>
                    </AkuTable>
                    </div>
                </div>
            </div>
        </div>
        <Modal :show="showModal" @close="closeModal">
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    {{ form.id ? "Edit Inventory" : "Add New Inventory" }}
                </h3>
            </div>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                    <div class="mt-2">
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Enter item name"
                            class="border border-gray-300 p-2 w-full rounded-md"
                        />
                    </div>
                    <div class="mt-2">
                        <input
                            v-model="form.qty"
                            type="number"
                            placeholder="Enter quantity"
                            class="border border-gray-300 p-2 w-full rounded-md"
                        />
                    </div>
                    <div class="mt-2">
                        <input
                            v-model="form.balance"
                            type="number"
                            placeholder="Enter balance"
                            class="border border-gray-300 p-2 w-full rounded-md"
                        />
                    </div>
                    <div class="mt-2">
                        <input
                            v-model="form.remarks"
                            type="text"
                            placeholder="Enter remarks"
                            class="border border-gray-300 p-2 w-full rounded-md"
                        />
                    </div>
                    <div class="mt-2">
                        <input
                            v-model="form.color"
                            type="text"
                            placeholder="Enter color"
                            class="border border-gray-300 p-2 w-full rounded-md"
                        />
                    </div>
                    <div class="mt-2">
                        <input
                            v-model="form.size"
                            type="text"
                            placeholder="Enter size"
                            class="border border-gray-300 p-2 w-full rounded-md"
                        />
                    </div>
                    <div class="mt-2">
                        <input
                            v-model="form.description"
                            type="text"
                            placeholder="Enter Description"
                            class="border border-gray-300 p-2 w-full rounded-md"
                        />
                    </div>
                </div>
            </div>
            <div
                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
            >
                <button
                    @click="saveInventory"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Save
                </button>
                <button
                    @click="closeModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm"
                >
                    Cancel
                </button>
            </div>
        </Modal>
        <!-- Modal Dialog for Delete Confirmation -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Confirm Delete
                </h3>
            </div>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="mt-1 text-center sm:mt-0 sm:text-left">
                    <div class="mt-2">
                        <p>Are you sure you want to delete this inventory item?</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
            >
                <button
                    @click="deleteInventory"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm"
                >
                    Delete
                </button>
                <button
                    @click="closeDeleteModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm"
                >
                    Cancel
                </button>
            </div>
        </Modal>

        <AlertComponent
            v-model:show="alertModel.show"
            :title="alertModel.title"
            :message="alertModel.message"
            :alertType="alertModel.alertType"
        />
    </AuthenticatedLayout>
</template>
