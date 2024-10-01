<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

// Props yang diterima dari controller
const props = defineProps({
    posts: Array
});

// State untuk modal
const showModal = ref(false);
const showDeleteModal = ref(false);
const newPostTitle = ref(''); // Title for new post
const postToDelete = ref(null); // ID post yang mau dihapus

// Function untuk menangani visibilitas modal
const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    newPostTitle.value = ''; // Clear the input when modal is closed
};

// Function untuk menangani penambahan post baru
const addPost = () => {
    if (newPostTitle.value) {
        Inertia.post('/post', { title: newPostTitle.value }); // Kirim permintaan POST ke server
        closeModal();
    } else {
        alert('Title is required');
    }
};

// Function untuk menangani konfirmasi penghapusan post
const confirmDeletePost = (id) => {
    postToDelete.value = id; // Simpan ID post yang akan dihapus
    showDeleteModal.value = true; // Tampilkan modal konfirmasi
};

// Function untuk menghapus post
const deletePost = () => {
    if (postToDelete.value) {
        Inertia.delete(`/post/${postToDelete.value}`); // Kirim permintaan DELETE ke server
        closeDeleteModal(); // Tutup modal konfirmasi setelah menghapus
    }
};

// Function untuk menutup modal konfirmasi
const closeDeleteModal = () => {
    showDeleteModal.value = false;
    postToDelete.value = null; // Reset ID post yang akan dihapus
};
</script>

<template>
    <Head title="CRUD Page" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h2 class="text-xl font-semibold">Data Posts</h2>
                        <ul>
                            <button @click="openModal" class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
                            <table class="min-w-full divide-y divide-gray-200 mt-4">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="post in props.posts" :key="post.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ post.id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ post.title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <button @click="confirmDeletePost(post.id)" class="bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                                            &nbsp;
                                            <button @click="editPost(post.id)" class="bg-green-500 text-white px-4 py-2 rounded">Edit</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Dialog untuk Tambah Post -->
        <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Post</h3>
                                <div class="mt-2">
                                    <input v-model="newPostTitle" type="text" placeholder="Enter post title" class="border border-gray-300 p-2 w-full rounded-md" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="addPost" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Save
                        </button>
                        <button @click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Tambah Post -->

        <!-- Modal Dialog untuk Konfirmasi Hapus -->
        <div v-if="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m6 0H9m6 0H9m0 0V9m0 0v3m0 0v3m0 0V9m0 0v3m0 0v3" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Confirm Delete</h3>
                                <div class="mt-2">
                                    <p>Are you sure you want to delete this post?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="deletePost" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                            Delete
                        </button>
                        <button @click="closeDeleteModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End of Modal Konfirmasi Hapus -->
    </AuthenticatedLayout>
</template>

<style scoped>
/* Optional styles to enhance the appearance of the list */
</style>
