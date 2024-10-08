<script setup>
import { onBeforeMount, ref, defineExpose } from "vue";
import Loading from "./Loading.vue";
import axios from "axios";

const props = defineProps({
    columns: {
        type: Array,
        default: [],
    },
    urls: {
        type: String,
        default: "",
    },
    defaultSortField: {
        type: String,
        default: "created_at",
    },
    defaultSortType: {
        type: String,
        default: "desc",
    },
    selectOptions: {
        type: Boolean,
        default: false
    }
});
const isLoading = ref(false);
const totalRecords = ref(0);
const rows = ref([]);
const page = ref(1);
const perPage = ref(10);
const sortField = ref(props.defaultSortField);
const sortType = ref(props.defaultSortType);
const searchQuery = ref("");

const loadItems = async () => {
    isLoading.value = true;
    const response = await axios.get("/api/" + props.urls, {
        params: {
            page: page.value,
            perPage: perPage.value,
            sortField: sortField.value,
            sortType: sortType.value,
            searchQuery: searchQuery.value,
        },
        headers: {
            Accept: "application/json",
        },
    });
    if(response.status){
        rows.value = response.data.data.data;
        totalRecords.value = response.data.total;
        isLoading.value = false;
    }else{
        rows.value = []
        totalRecords.value = 0
        isLoading.value = false;
    }
};

const onPageChange = (params) => {
    page.value = params.currentPage;
    loadItems();
};
const onPerPageChange = (params) => {
    perPage.value = params.currentPerPage;
    loadItems();
};
const onSortChange = (params) => {
    sortField.value = params[0].field;
    sortType.value = params[0].type;
    loadItems();
};
const onSearch = (params) => {
    page.value = 1;
    searchQuery.value = params.searchTerm;
    loadItems();
};
onBeforeMount(() => {
    loadItems();
});

defineExpose({ loadItems });
</script>

<template>
    <div class="z-10">
        <vue-good-table
            ref="dataTableRemote"
            mode="remote"
            :totalRows="totalRecords"
            v-model:isloading="isLoading"
            :columns="columns"
            :line-numbers="true"
            :select-options="{
                enabled: selectOptions
            }"
            :search-options="{
                enabled: true,
                placeholder: 'Search this table, and press enter',
                trigger: 'enter',
            }"
            :pagination-options="{
                enabled: true,
                mode: 'records',
                perPage: perPage,
                setCurrentPage: page,
                perPageDropdown: [10, 20, 30, 40, 50],
            }"
            :sortable="true"
            :sort-options="{
                enable: true,
                multipleColumns: false,
            }"
            @page-change="onPageChange"
            @per-page-change="onPerPageChange"
            @sortChange="onSortChange"
            @search="onSearch"
            @load="loadItems"
            :rows="rows"
            theme="polar-bear"
        >
            <template #table-row="props">
                <slot name="table-row" v-bind="props"></slot>
            </template>
            <template #table-column="props">
                <slot name="table-column" v-bind="props"></slot>
            </template>
            <template #column-filter="props">
                <slot name="column-filter" v-bind="props"></slot>
            </template>
            <template #emptystate="props">
                <slot name="emptystate" v-bind="props"
                    >This will show up when there are no rows</slot
                >
            </template>
        </vue-good-table>
    </div>
    <loading :loading="isLoading" />
</template>

<style scoped></style>
