<script setup lang="ts">
import SupplierAddEditDialog from './components/SupplierAddEditDialog.vue'
import SupplierDeleteDialog from './components/SupplierDeleteDialog.vue'
import SupplierFilterForm from './components/SupplierFilterForm.vue'
import SupplierMessageDialog from './components/SupplierMessageDialog.vue'
import SupplierTable from './components/SupplierTable.vue'
import { useSupplierLogic } from './useSupplierLogic'

const {
  // State
  statuses, supplierNames, supplierCodes,
  nameSelect, codeSelect, statusSelect, filterForm,
  editDialog, addDialog, deleteDialog, messageDialog,
  refForm, refEditForm,
  editedItem, editedIndex, addedItem,
  userList, totalItems, itemsPerPage, page, loading,
  messageTitle, messageText, messageIcon, messageColor, messageErrors,
  
  // Methods
  fetchTableData, editItem, addItem, deleteItem, close, closeDelete,
  save, deleteItemConfirm, searchData, resetFilter
} = useSupplierLogic()

const headers = [
  { title: 'SUPPLIER NAME', key: 'supplier_name' },
  { title: 'CODE', key: 'supplier_code' },
  { title: 'CONTACT PERSON', key: 'contact_person' },
  { title: 'EMAIL', key: 'email' },
  { title: 'PHONE', key: 'phone' },
  { title: 'ADDRESS', key: 'address' },
  { title: 'STATUS', key: 'status' },
  { title: 'ACTIONS', key: 'actions', sortable: false },
]
</script>

<template>
  <div>
    <!-- (1) FILTER FORM -->
    <SupplierFilterForm
      ref="filterForm"
      v-model:nameSelect="nameSelect"
      v-model:codeSelect="codeSelect"
      v-model:statusSelect="statusSelect"
      :supplier-names="supplierNames"
      :supplier-codes="supplierCodes"
      :statuses="statuses"
      @search="searchData"
      @reset="resetFilter"
    />

    <!-- (2) DATA TABLE -->
    <SupplierTable
      v-model:items-per-page="itemsPerPage"
      v-model:page="page"
      :headers="headers"
      :items="userList"
      :total-items="totalItems"
      :loading="loading"
      @update:options="fetchTableData"
      @add-item="addItem"
      @edit-item="editItem"
      @delete-item="deleteItem"
    />

    <!-- 👉 Add Dialog -->
    <SupplierAddEditDialog
      ref="refForm"
      v-model="addDialog"
      :is-edit="false"
      :item="addedItem"
      :statuses="statuses"
      @save="save"
      @close="close"
    />

    <!-- 👉 Edit Dialog -->
    <SupplierAddEditDialog
      ref="refEditForm"
      v-model="editDialog"
      :is-edit="true"
      :item="editedItem"
      :statuses="statuses"
      @save="save"
      @close="close"
    />

    <!-- 👉 Delete Dialog -->
    <SupplierDeleteDialog
      v-model="deleteDialog"
      @confirm="deleteItemConfirm"
      @close="closeDelete"
    />

    <!-- 👉 Message Dialog -->
    <SupplierMessageDialog
      v-model="messageDialog"
      :title="messageTitle"
      :text="messageText"
      :icon="messageIcon"
      :color="messageColor"
      :errors="messageErrors"
    />
  </div>
</template>
