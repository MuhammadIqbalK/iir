import { $api } from '@/utils/api'
import { nextTick, onMounted, ref, watch } from 'vue'
import type { VForm } from 'vuetify/components/VForm'

export const useSupplierLogic = () => {
  // 👉 Search State
  const statuses = ['Active', 'Inactive', 'Blocked'] as const
  const supplierNames = ref([])
  const supplierCodes = ref([])

  const nameSelect = ref('')
  const codeSelect = ref('')
  const statusSelect = ref()
  const filterForm = ref<VForm>()

  // 👉 Dialog State
  const editDialog = ref(false)
  const addDialog = ref(false)
  const deleteDialog = ref(false)
  const messageDialog = ref(false)

  // 👉 Form State
  const refForm = ref<VForm>()
  const refEditForm = ref<VForm>()

  const defaultItem = {
    id: -1,
    supplier_name: '',
    supplier_code: '',
    contact_person: '',
    email: '',
    phone: '',
    address: '',
    status: 'Active',
  }

  const editedItem = ref({ ...defaultItem })
  const editedIndex = ref(-1)
  const addedItem = ref({ ...defaultItem })

  // 👉 Table State
  const userList = ref([])
  const totalItems = ref(0)
  const itemsPerPage = ref(10)
  const page = ref(1)
  const loading = ref(false)

  // 👉 Message State
  const messageTitle = ref('')
  const messageText = ref('')
  const messageIcon = ref('')
  const messageColor = ref('')
  const messageErrors = ref<string[]>([])

  // 👉 Methods
  const fetchDropdowns = async () => {
    try {
      const response = await $api('/api/suppliers-dropdown')
      supplierNames.value = response.data.map((s: any) => ({ title: s.supplier_name, value: s.supplier_name }))
      supplierCodes.value = response.data.map((s: any) => ({ title: s.supplier_code, value: s.supplier_code }))
    } catch (error) {
      console.error('Error fetching dropdowns:', error)
    }
  }

  const fetchTableData = async (options: any = null) => {
    // Guard to prevent concurrent fetches which cause double requests
    if (loading.value) return
    
    // Update local state from table options if provided (e.g. from @update:options)
    if (options && typeof options === 'object') {
      if ('page' in options) page.value = options.page
      if ('itemsPerPage' in options) itemsPerPage.value = options.itemsPerPage
    }

    loading.value = true
    try {
      const params: any = {
        page: page.value,
        per_page: itemsPerPage.value,
      }

      if (nameSelect.value) params.supplier_name = nameSelect.value
      if (codeSelect.value) params.supplier_code = codeSelect.value
      if (statusSelect.value) params.status = statusSelect.value

      const response = await $api('/api/suppliers', { params })
      
      userList.value = response.data
      totalItems.value = response.total || 0

    } catch (error) {
      console.error('Error fetching table data:', error)
    } finally {
      loading.value = false
    }
  }

  const showMessage = (title: string, text: string, type: 'success' | 'error' | 'warning' | 'info' = 'success', errors: string[] = []) => {
    messageTitle.value = title
    messageText.value = text
    messageErrors.value = errors
    messageDialog.value = true
    
    switch (type) {
      case 'success':
        messageIcon.value = 'tabler-circle-check'
        messageColor.value = 'success'
        break
      case 'error':
        messageIcon.value = 'tabler-alert-circle'
        messageColor.value = 'error'
        break
      case 'warning':
        messageIcon.value = 'tabler-alert-triangle'
        messageColor.value = 'warning'
        break
      case 'info':
        messageIcon.value = 'tabler-info-circle'
        messageColor.value = 'info'
        break
    }
  }

  const close = () => {
    editDialog.value = false
    addDialog.value = false
    
    nextTick(() => {
      refForm.value?.resetValidation()
      refEditForm.value?.resetValidation()
      editedIndex.value = -1
      editedItem.value = { ...defaultItem }
      addedItem.value = { ...defaultItem }
    })
  }

  const closeDelete = () => {
    deleteDialog.value = false
    nextTick(() => {
      editedIndex.value = -1
      editedItem.value = { ...defaultItem }
    })
  }

  const save = async () => {
    const { valid } = await (editedIndex.value > -1 ? refEditForm.value?.validate() : refForm.value?.validate()) || { valid: false }
    
    if (!valid) return

    try {
      if (editedIndex.value > -1) {
        await $api(`/api/suppliers/${editedItem.value.id}`, {
          method: 'PUT',
          body: editedItem.value
        })
        showMessage('Success', 'Supplier updated successfully', 'success')
      } else {
        await $api('/api/suppliers', {
          method: 'POST',
          body: addedItem.value
        })
        showMessage('Success', 'Supplier created successfully', 'success')
      }
      close()
      fetchTableData()
      fetchDropdowns()
    } catch (error: any) {
      console.error('Error saving supplier:', error)
      const errorData = error.data
      let errorMessages: string[] = []
      
      if (errorData && errorData.errors) {
        errorMessages = Object.values(errorData.errors).flat() as string[]
      }
      
      showMessage('Error', errorData?.message || 'Failed to save supplier', 'error', errorMessages)
    }
  }

  const deleteItemConfirm = async () => {
    try {
      await $api(`/api/suppliers/${editedItem.value.id}`, {
        method: 'DELETE'
      })
      showMessage('Success', 'Supplier deleted successfully', 'success')
      closeDelete()
      fetchTableData()
      fetchDropdowns()
    } catch (error) {
      console.error('Error deleting supplier:', error)
      showMessage('Error', 'Failed to delete supplier', 'error')
    }
  }

  const editItem = (item: any) => {
    editedIndex.value = userList.value.indexOf(item)
    editedItem.value = { ...item }
    editDialog.value = true
    
    nextTick(() => {
      refEditForm.value?.resetValidation()
    })
  }

  const addItem = () => {
    addedItem.value = { ...defaultItem }
    addDialog.value = true
    nextTick(() => {
      refForm.value?.resetValidation()
    })
  }

  const deleteItem = (item: any) => {
    editedIndex.value = userList.value.indexOf(item)
    editedItem.value = { ...item }
    deleteDialog.value = true
  }

  const searchData = () => {
    // If we are already on page 1, manually fetch data.
    // If not, setting page to 1 will trigger the table's @update:options event.
    if (page.value === 1) {
      fetchTableData()
    } else {
      page.value = 1
    }
  }

  const resetFilter = () => {
    filterForm.value?.reset()
    nameSelect.value = ''
    codeSelect.value = ''
    statusSelect.value = undefined
    
    if (page.value === 1) {
      fetchTableData()
    } else {
      page.value = 1
    }
  }

  // 👉 Watchers
  // Watchers on page/itemsPerPage are removed to prevent double fetch.
  // VDataTableServer already triggers fetchTableData via @update:options.

  watch(nameSelect, newVal => {
    if (newVal) codeSelect.value = ''
  })

  watch(codeSelect, newVal => {
    if (newVal) nameSelect.value = ''
  })

  onMounted(() => {
    fetchDropdowns()
    // fetchTableData() is NOT called here because VDataTableServer 
    // triggers its first @update:options event automatically on mount.
  })

  return {
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
  }
}
