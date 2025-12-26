<script setup lang="ts">
import MaterialAddEditDialog from './components/MaterialAddEditDialog.vue'
import MaterialDeleteDialog from './components/MaterialDeleteDialog.vue'
import MaterialFilterForm from './components/MaterialFilterForm.vue'
import MaterialMessageDialog from './components/MaterialMessageDialog.vue'
import MaterialTable from './components/MaterialTable.vue'
import { useMaterialsLogic } from './useMaterialsLogic'

const {
  // State
  types, item12ncs, partnames,
  typeSelect, qualitySelect, statusSelect, item12ncSelect, partnameSelect, filterForm,
  editDialog, addDialog, deleteDialog, messageDialog,
  refForm, refEditForm,
  editedItem, editedIndex, addedItem,
  materialList, totalItems, itemsPerPage, page, loading,
  messageTitle, messageText, messageIcon, messageColor, messageErrors,
  
  // Methods
  fetchTableData, editItem, addItem, deleteItem, close, closeDelete,
  save, deleteItemConfirm, searchData, resetFilter
} = useMaterialsLogic()

const headers = [
  { title: 'ITEM12NC', key: 'item12nc' },
  { title: 'PART NAME', key: 'partname' },
  { title: 'TYPE', key: 'type' },
  { title: 'QUANTITY', key: 'quantity' },
  { title: 'UNIT', key: 'unit' },
  { title: 'ACTIONS', key: 'actions', sortable: false },
]
</script>

<template>
  <div>
    <!-- (1) FILTER FORM -->
    <MaterialFilterForm
      ref="filterForm"
      v-model:partnameSelect="partnameSelect"
      v-model:item12ncSelect="item12ncSelect"
      :partnames="partnames"
      :item12ncs="item12ncs"
      @search="searchData"
      @reset="resetFilter"
    />

    <!-- (2) DATA TABLE -->
    <MaterialTable
      v-model:items-per-page="itemsPerPage"
      v-model:page="page"
      :headers="headers"
      :items="materialList"
      :total-items="totalItems"
      :loading="loading"
      @update:options="fetchTableData"
      @add-item="addItem"
      @edit-item="editItem"
      @delete-item="deleteItem"
    />

    <!-- 👉 Add Dialog -->
    <MaterialAddEditDialog
      ref="refForm"
      v-model="addDialog"
      :is-edit="false"
      :item="addedItem"
      @save="save"
      @close="close"
    />

    <!-- 👉 Edit Dialog -->
    <MaterialAddEditDialog
      ref="refEditForm"
      v-model="editDialog"
      :is-edit="true"
      :item="editedItem"
      @save="save"
      @close="close"
    />

    <!-- 👉 Delete Dialog -->
    <MaterialDeleteDialog
      v-model="deleteDialog"
      @confirm="deleteItemConfirm"
      @close="closeDelete"
    />

    <!-- 👉 Message Dialog -->
    <MaterialMessageDialog
      v-model="messageDialog"
      :title="messageTitle"
      :text="messageText"
      :icon="messageIcon"
      :color="messageColor"
      :errors="messageErrors"
    />
  </div>
</template>
