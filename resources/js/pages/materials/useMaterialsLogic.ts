import { $api } from '@/utils/api'
import { nextTick, onMounted, ref, watch } from 'vue'
import type { VForm } from 'vuetify/components/VForm'

export const useMaterialsLogic = () => {
  // 👉 Search State
  const types = ref([]) // Will be populated dynamically
  const item12ncs = ref([]) // Will be populated dynamically
  const partnames = ref([]) // Will be populated dynamically
  const categories = ref([]) // Will be populated dynamically

  const typeSelect = ref('')
  const qualitySelect = ref('')
  const statusSelect = ref()
  const item12ncSelect = ref('')
  const partnameSelect = ref('')
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
    id: null,
    item12nc: '',
    partname: '',
    type: '',
    whq: '',
    unit: 'Pcs',
    category: null,
  }

  const editedItem = ref({ ...defaultItem })
  const editedIndex = ref(-1)
  const addedItem = ref({ ...defaultItem })

  // 👉 Table State
  const materialList = ref([])
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
      const response = await $api('/api/itemncs-dropdown')
      types.value = response.data.map((s: any) => ({ title: s.type, value: s.type }))
      item12ncs.value = response.data.map((s: any) => ({ title: s.item12nc, value: s.item12nc }))
      partnames.value = response.data.map((s: any) => ({ title: s.partname, value: s.partname }))
      
      // Fetch categories
      const categoryResponse = await $api('/api/itemncs-category-dropdown')
      categories.value = categoryResponse.data.map((s: any) => ({ title: s.description, value: s.id }))
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

      if (partnameSelect.value) params.partname = partnameSelect.value
      if (item12ncSelect.value) params.item12nc = item12ncSelect.value
      if (qualitySelect.value) params.quality = qualitySelect.value
      if (statusSelect.value) params.status = statusSelect.value
      if (typeSelect.value) params.type = typeSelect.value

      const response = await $api('/api/itemncs', { params })
      
      materialList.value = response.data
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
        await $api(`/api/itemncs/${editedItem.value.id}`, {
          method: 'PUT',
          body: editedItem.value
        })
        showMessage('Success', 'Material updated successfully', 'success')
      } else {
        await $api('/api/itemncs', {
          method: 'POST',
          body: addedItem.value
        })
        showMessage('Success', 'Material created successfully', 'success')
      }
      close()
      fetchTableData()
      fetchDropdowns()
    } catch (error: any) {
      console.error('Error saving material:', error)
      const errorData = error.data
      let errorMessages: string[] = []
      
      if (errorData && errorData.errors) {
        errorMessages = Object.values(errorData.errors).flat() as string[]
      }
      
      showMessage('Error', errorData?.message || 'Failed to save material', 'error', errorMessages)
    }
  }

  const deleteItemConfirm = async () => {
    try {
      await $api(`/api/itemncs/${editedItem.value.id}`, {
        method: 'DELETE'
      })
      showMessage('Success', 'Material deleted successfully', 'success')
      closeDelete()
      fetchTableData()
      fetchDropdowns()
    } catch (error) {
      console.error('Error deleting material:', error)
      showMessage('Error', 'Failed to delete material', 'error')
    }
  }

  const editItem = (item: any) => {
    editedIndex.value = materialList.value.indexOf(item)
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
    editedIndex.value = materialList.value.indexOf(item)
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
    typeSelect.value = ''
    item12ncSelect.value = ''
    partnameSelect.value = ''
    
    if (page.value === 1) {
      fetchTableData()
    } else {
      page.value = 1
    }
  }

  // 👉 Watchers
  // Watchers on page/itemsPerPage are removed to prevent double fetch.
  // VDataTableServer already triggers fetchTableData via @update:options.

  watch(typeSelect, newVal => {
    if (newVal) item12ncSelect.value = ''
  })

  watch(item12ncSelect, newVal => {
    if (newVal) typeSelect.value = ''
  })

  watch(partnameSelect, newVal => {
    if (newVal) item12ncSelect.value = ''
  })

  watch(item12ncSelect, newVal => {
    if (newVal) partnameSelect.value = ''
  })

  onMounted(() => {
    fetchDropdowns()
    // fetchTableData() is NOT called here because VDataTableServer 
    // triggers its first @update:options event automatically on mount.
  })

  return {
    // State
    types, item12ncs, partnames, categories,
    typeSelect, qualitySelect, statusSelect, item12ncSelect, partnameSelect, filterForm,
    editDialog, addDialog, deleteDialog, messageDialog,
    refForm, refEditForm,
    editedItem, editedIndex, addedItem,
    materialList, totalItems, itemsPerPage, page, loading,
    messageTitle, messageText, messageIcon, messageColor, messageErrors,
    
    // Methods
    fetchTableData, editItem, addItem, deleteItem, close, closeDelete,
    save, deleteItemConfirm, searchData, resetFilter
  }
}
