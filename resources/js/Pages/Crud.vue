<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, watchEffect } from "vue";
import { Inertia } from "@inertiajs/inertia";
import Modal from "@/Components/Modal.vue";
import postService from "@/Services/post.service";
import AlertComponent from "@/Components/Alert.vue";
import AkuTable from "@/Components/AkuTable.vue";

// Buat Narik data dari controller
const props = defineProps({
    posts: Array,
});

// Untuk Modal
const showModal = ref(false);
const showDeleteModal = ref(false);
const form = ref({
    id: null,
    title: "",
    description:"",
}); // untuk form modal

const alertModel = ref({
    show: false,
    title: "",
    message: "",
    alertType: "",
});
const columns = [
    { field: "title", label: "Name", sortable: true },
    { field: "description", label: "Deskripsi", sortable: true },
    {
        field: "created_at",
        width: "250px",
        label: "Created At",
        sortable: true,
    },
    { field: "action", width: "250px", label: "Action", sortable: false },
]; // untuk kolom table
const AkuTableRef = ref(null); // ambil tittle buat edit atau tambah

// reset model
const resetModel = () => {
    form.value.id = null;
    form.value.title = "";
};

// Biar modal nya nongol
const openModal = () => {
    resetModel();
    showModal.value = true;
};

// Kalo gak jadi edit (Pencet tombol cancel nnti batal edit)
const closeModal = () => {
    resetModel();
    showModal.value = false;
};

// If Else klo mau edit atau tambah
const savePost = async () => {
    try {
        const response = await postService.store(form.value);
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

// Function Edit
const editPost = (post) => {
    form.value.id = post.id;
    form.value.title = post.title;
    form.value.description = post.description;
    showModal.value = true;
};

// Function Confirm Hapus
const confirmDeletePost = (id) => {
    form.value.id = id;
    showDeleteModal.value = true;
};

// Function untuk menghapus post
const deletePost = async () => {
    try {
        const response = await postService.destroy(form.value.id);
        if (response.status) {
            alertModel.value.show = true;
            alertModel.value.title = "Success";
            alertModel.value.message = response.message;
            alertModel.value.alertType = "success";
            loadItems();
            closeDeleteModal();
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

// Tutup Modal
const closeDeleteModal = () => {
    showDeleteModal.value = false;
};

// Load Items AkuTable
const loadItems = async () => {
    await AkuTableRef.value["loadItems"]();
};
// format date dari timestamp
function formatDate(timestamp) {
    const date = new Date(timestamp);

    // Format the date and time using toLocaleString
    const formattedDate = date.toLocaleString("en-US", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
        second: "numeric",
        hour12: false, // Use 12-hour format, set to false for 24-hour format
    });

    return formattedDate;
}

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
    <Head title="CRUD Page" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg shadow-lg"
                >
                    <div class="p-6 text-gray-900">
                        <h2 class="text-xl font-semibold">Data Posts</h2>
                        <div
                            class="md:px-3 md:py-4 flex flex-col md:flex-row gap-1.5 md:gap-1 md:justify-end"
                        >
                            <button
                                @click="openModal"
                                class="bg-blue-500 text-white px-4 py-2 rounded-xl"
                            >
                                Add
                            </button>
                        </div>
                        <AkuTable
                            :columns="columns"
                            ref="AkuTableRef"
                            :urls="'post/page'"
                        >
                            <template #table-row="props">
                                <span
                                    v-if="
                                        props['column'].field === 'created_at'
                                    "
                                >
                                    {{ formatDate(props["row"]["created_at"]) }}
                                </span>
                                <div v-if="props['column'].field === 'action'">
                                    <div class="flex gap-2">
                                        <button
                                            class="btn btn-warning btn-sm"
                                            @click="editPost(props['row'])"
                                        >
                                            edit
                                        </button>
                                        <button
                                            class="btn btn-error btn-sm"
                                            @click="
                                                confirmDeletePost(
                                                    props['row'].id
                                                )
                                            "
                                        >
                                            delete
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
                    {{ form.id ? "Edit Post" : "Add New Post" }}
                </h3>
            </div>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="mt-3 text-center sm:mt-0 sm:text-left">
                    <div class="mt-2">
                        <input
                            v-model="form.title"
                            type="text"
                            placeholder="Enter post title"
                            class="border border-gray-300 p-2 w-full rounded-md"
                        />
                    </div>
                    <div class="mt-2">
                        <input
                            v-model="form.description"
                            type="text"
                            placeholder="Enter post Description"
                            class="border border-gray-300 p-2 w-full rounded-md"
                        />
                    </div>
                </div>
            </div>
            <div
                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
            >
                <button
                    @click="savePost"
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
        <!-- Modal Dialog untuk Konfirmasi Hapus -->
        <Modal :show="showDeleteModal" @close="closeDeleteModal">
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Confirm Delete
                </h3>
            </div>
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="mt-1 text-center sm:mt-0 sm:text-left">
                    <div class="mt-2">
                        <p>Are you sure you want to delete this post?</p>
                    </div>
                </div>
            </div>
            <div
                class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse"
            >
                <button
                    @click="deletePost"
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