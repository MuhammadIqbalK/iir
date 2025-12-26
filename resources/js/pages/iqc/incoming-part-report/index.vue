<script setup lang="ts">
import IirAddEditDialog from './components/IirAddEditDialog.vue'
import IirDeleteDialog from './components/IirDeleteDialog.vue'
import IirFilterForm from './components/IirFilterForm.vue'
import IirMessageDialog from './components/IirMessageDialog.vue'
import IirTable from './components/IirTable.vue'
import { useIirLogic } from './useIirLogic'

const {
  // State
  items, itemsDialog, suppliers, examiners, options,statuses,
  itemsSelect, suppliersSelect, optionsSelect, statusSelect, dateRange, filterForm,
  editDialog, addDialog, deleteDialog, messageDialog,
  refForm, refEditForm,
  editedItem, editedIndex, addedItem,
  userList, totalItems, itemsPerPage, page, loading,
  messageTitle, messageText, messageIcon, messageColor, messageErrors,
  
  // Methods
  fetchTableData, editItem, addItem, deleteItem, close, closeDelete,
  save, deleteItemConfirm, searchData, resetFilter, printGlobal, printRecap
} = useIirLogic()

const headers = [
  { title: 'IIR DATE', key: 'iirdate' },
  { title: 'ITEM NC', key: 'itemnc' },
  { title: 'PART NAME', key: 'partname' },
  { title: 'NODOC', key: 'nodoc' },
  { title: 'QTY', key: 'quantity' },
  { title: 'SAMPLE SIZE', key: 'samplesize' },
  { title: 'GIL LEVEL', key: 'gilevel' },
  { title: 'EXAMINER', key: 'name' },
  { title: 'START', key: 'start' },
  { title: 'END', key: 'end' },
  { title: 'SUPPLIER', key: 'supplier_name' },
  { title: 'OPTION', key: 'option' },
  { title: 'BATCH', key: 'batch' },
  { title: 'STATUS', key: 'status' },
  { title: 'ACTIONS', key: 'actions', sortable: false },
]
</script>

<template>
  <div>
    <!-- (1) FILTER FORM -->
    <IirFilterForm
      v-model:items-select="itemsSelect"
      v-model:suppliers-select="suppliersSelect"
      v-model:options-select="optionsSelect"
      v-model:date-range="dateRange"
      :items="items"
      :suppliers="suppliers"
      :options="options"
      @search="searchData"
      @reset="resetFilter"
      @Global="printGlobal"
      @Recap="printRecap"
    />

    <!-- (2) DATA TABLE -->
    <IirTable
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
    <IirAddEditDialog
      ref="refForm"
      v-model="addDialog"
      :is-edit="false"
      :item="addedItem"
      :items-dialog="itemsDialog"
      :examiners="examiners"
      :suppliers="suppliers"
      :options="options"
      :statuses="statuses"
      @save="save"
      @close="close"
    />

    <!-- 👉 Edit Dialog -->
    <IirAddEditDialog
      ref="refEditForm"
      v-model="editDialog"
      :is-edit="true"
      :item="editedItem"
      :items-dialog="itemsDialog"
      :examiners="examiners"
      :suppliers="suppliers"
      :options="options"
      :statuses="statuses"
      @save="save"
      @close="close"
    />

    <!-- 👉 Delete Dialog -->
    <IirDeleteDialog
      v-model="deleteDialog"
      @confirm="deleteItemConfirm"
      @close="closeDelete"
    />

    <!-- 👉 Message Dialog -->
    <IirMessageDialog
      v-model="messageDialog"
      :title="messageTitle"
      :text="messageText"
      :icon="messageIcon"
      :color="messageColor"
      :errors="messageErrors"
    />
  </div>
</template>
