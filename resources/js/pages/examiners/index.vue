<script setup lang="ts">
import ExaminerAddEditDialog from './components/ExaminerAddEditDialog.vue'
import ExaminerDeleteDialog from './components/ExaminerDeleteDialog.vue'
import ExaminerFilterForm from './components/ExaminerFilterForm.vue'
import ExaminerMessageDialog from './components/ExaminerMessageDialog.vue'
import ExaminerTable from './components/ExaminerTable.vue'
import { useExaminerLogic } from './useExaminerLogic'

const {
  // State
  examinerNames,
  nameSelect, filterForm,
  editDialog, addDialog, deleteDialog, messageDialog,
  refForm, refEditForm,
  editedItem, editedIndex, addedItem,
  examinerList, totalItems, itemsPerPage, page, loading,
  messageTitle, messageText, messageIcon, messageColor, messageErrors,
  
  // Methods
  fetchTableData, editItem, addItem, deleteItem, close, closeDelete,
  save, deleteItemConfirm, searchData, resetFilter
} = useExaminerLogic()

const headers = [
  { title: 'ID', key: 'id' },
  { title: 'NAME', key: 'name' },
  { title: 'ACTIONS', key: 'actions', sortable: false },
]
</script>

<template>
  <div>
    <!-- (1) FILTER FORM -->
    <ExaminerFilterForm
      ref="filterForm"
      v-model:nameSelect="nameSelect"
      :examiner-names="examinerNames"
      @search="searchData"
      @reset="resetFilter"
    />

    <!-- (2) DATA TABLE -->
    <ExaminerTable
      v-model:items-per-page="itemsPerPage"
      v-model:page="page"
      :headers="headers"
      :items="examinerList"
      :total-items="totalItems"
      :loading="loading"
      @update:options="fetchTableData"
      @add-item="addItem"
      @edit-item="editItem"
      @delete-item="deleteItem"
    />

    <!-- 👉 Add Dialog -->
    <ExaminerAddEditDialog
      ref="refForm"
      v-model="addDialog"
      :is-edit="false"
      :item="addedItem"
      @save="save"
      @close="close"
    />

    <!-- 👉 Edit Dialog -->
    <ExaminerAddEditDialog
      ref="refEditForm"
      v-model="editDialog"
      :is-edit="true"
      :item="editedItem"
      @save="save"
      @close="close"
    />

    <!-- 👉 Delete Dialog -->
    <ExaminerDeleteDialog
      v-model="deleteDialog"
      @confirm="deleteItemConfirm"
      @close="closeDelete"
    />

    <!-- 👉 Message Dialog -->
    <ExaminerMessageDialog
      v-model="messageDialog"
      :title="messageTitle"
      :text="messageText"
      :icon="messageIcon"
      :color="messageColor"
      :errors="messageErrors"
    />
  </div>
</template>
