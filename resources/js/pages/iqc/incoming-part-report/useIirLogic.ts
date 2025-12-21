import { $api } from '@/utils/api'
import { nextTick, onMounted, ref, watch } from 'vue'
import type { VForm } from 'vuetify/components/VForm'

export const useIirLogic = () => {
  // 👉 Search State
  const items = ref([])
  const itemsDialog = ref([])
  const rawItems = ref([])
  const suppliers = ref([])
  const examiners = ref([])
  const options = ['Local', 'Import'] as const

  const itemsSelect = ref()
  const suppliersSelect = ref()
  const optionsSelect = ref()
  const dateRange = ref('')
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
    iirdate: '',
    itemnc: '',
    partname: '',
    nodoc: '',
    quantity: 1,
    samplesize: 1,
    gilevel: 1,
    examiner_id: '',
    start: '',
    end: '',
    duration: '',
    supplier_code: '',
    option: '',
  }

  const editViewItem = {
    id: -1,
    iirdate: '',
    itemnc: '',
    partname: '',
    nodoc: '',
    quantity: 1,
    samplesize: 1,
    gilevel: 1,
    examiner_id: '',
    name: '',
    start: '',
    end: '',
    duration: '',
    supplier_code: '',
    supplier_name: '',
    option: '',
  }

  const editedItem = ref({ ...editViewItem })
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
      const [itemsRes, suppliersRes, examinersRes] = await Promise.all([
        $api('/api/itemncs-dropdown'),
        $api('/api/suppliers-dropdown'),
        $api('/api/examiners-dropdown')
      ])

      rawItems.value = itemsRes.data
      items.value = itemsRes.data.map((i: any) => ({ title: `${i.item12nc} (-) ${i.partname}`, value: i.item12nc }))
      itemsDialog.value = itemsRes.data.map((idialog: any) => ({ title: idialog.item12nc, value: idialog.item12nc }))
      suppliers.value = suppliersRes.data.map((s: any) => ({ title: s.supplier_name, value: s.supplier_code }))
      examiners.value = examinersRes.data.map((e: any) => ({ title: e.name, value: e.id }))
    } catch (error) {
      console.error('Error fetching dropdowns:', error)
    }
  }

  const fetchTableData = async (options: any = null) => {
    loading.value = true
    
    if (options && typeof options === 'object') {
      if ('page' in options) page.value = options.page
      if ('itemsPerPage' in options) itemsPerPage.value = options.itemsPerPage
    }

    try {
      const params: any = {
        page: page.value,
        per_page: itemsPerPage.value,
      }

      if (itemsSelect.value) params.itemnc = itemsSelect.value
      if (suppliersSelect.value) params.supplier = suppliersSelect.value
      if (optionsSelect.value) params.option = optionsSelect.value
      
      if (dateRange.value) {
        const dates = dateRange.value.split(' to ')
        if (dates.length === 2) {
          params.startdate = dates[0]
          params.enddate = dates[1]
        }
      }

      const response = await $api('/api/incoming-part-reports', { params })
      
      userList.value = response.data.map((item: any) => ({
        ...item,
        examiner_id: item.examiner_id ? Number(item.examiner_id) : null,
        supplier_code: item.supplier_code ? String(item.supplier_code) : null,
        option: item.option ? (item.option.charAt(0).toUpperCase() + item.option.slice(1).toLowerCase()) : null
      }))
      
      totalItems.value = response.total || response.meta?.total || 0

    } catch (error) {
      console.error('Error fetching table data:', error)
    } finally {
      loading.value = false
    }
  }

  const calculateDuration = (start: string, end: string) => {
    if (!start || !end) return ''
    const [startHours, startMinutes] = start.split(':').map(Number)
    const [endHours, endMinutes] = end.split(':').map(Number)
    if (isNaN(startHours) || isNaN(startMinutes) || isNaN(endHours) || isNaN(endMinutes)) return ''
    let diffMs = (endHours * 60 + endMinutes) - (startHours * 60 + startMinutes)
    if (diffMs < 0) diffMs += 24 * 60 
    const hours = Math.floor(diffMs / 60)
    const minutes = diffMs % 60
    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`
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
      editedItem.value = { ...editViewItem }
      addedItem.value = { ...defaultItem }
    })
  }

  const closeDelete = () => {
    deleteDialog.value = false
    nextTick(() => {
      editedIndex.value = -1
      editedItem.value = { ...editViewItem }
    })
  }

  const save = async () => {
    const { valid } = await (editedIndex.value > -1 ? refEditForm.value?.validate() : refForm.value?.validate()) || { valid: false }
    if (!valid) return

    try {
      if (editedIndex.value > -1) {
        await $api(`/api/incoming-part-reports/${editedItem.value.id}`, {
          method: 'PUT',
          body: editedItem.value
        })
        showMessage('Success', 'Item updated successfully', 'success')
      } else {
        await $api('/api/incoming-part-reports', {
          method: 'POST',
          body: addedItem.value
        })
        showMessage('Success', 'Item created successfully', 'success')
      }
      close()
      fetchTableData()
    } catch (error: any) {
      console.error('Error saving item:', error)
      const errorData = error.data
      let errorMessages: string[] = []
      if (errorData && errorData.errors) {
        errorMessages = Object.values(errorData.errors).flat() as string[]
      }
      showMessage('Error', errorData?.message || 'Failed to save item', 'error', errorMessages)
    }
  }

  const deleteItemConfirm = async () => {
    try {
      await $api(`/api/incoming-part-reports/${editedItem.value.id}`, {
        method: 'DELETE'
      })
      showMessage('Success', 'Item deleted successfully', 'success')
      closeDelete()
      fetchTableData()
    } catch (error) {
      console.error('Error deleting item:', error)
      showMessage('Error', 'Failed to delete item', 'error')
    }
  }

  const editItem = (item: any) => {
    editedIndex.value = userList.value.indexOf(item)
    const mappedItem = {
      ...item,
      examiner_id: item.examiner_id ? Number(item.examiner_id) : null,
      supplier_code: item.supplier_code ? String(item.supplier_code) : null,
      option: item.option ? (item.option.charAt(0).toUpperCase() + item.option.slice(1).toLowerCase()) : null
    }
    editedItem.value = { ...mappedItem }
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
    if (page.value === 1) {
      fetchTableData()
    } else {
      page.value = 1
    }
  }

  const resetFilter = () => {
    filterForm.value?.reset()
    itemsSelect.value = undefined
    suppliersSelect.value = undefined
    optionsSelect.value = undefined
    dateRange.value = ''
    if (page.value === 1) {
      fetchTableData()
    } else {
      page.value = 1
    }
  }

  // 👉 Watchers
  // Watchers removed to prevent double fetch with VDataTableServer @update:options

  watch(() => addedItem.value.itemnc, (newVal) => {
    const item = rawItems.value.find((i: any) => i.item12nc === newVal)
    if (item) addedItem.value.partname = item.partname
  })

  watch(() => editedItem.value.itemnc, (newVal) => {
    const item = rawItems.value.find((i: any) => i.item12nc === newVal)
    if (item) editedItem.value.partname = item.partname
  })

  watch([() => addedItem.value.start, () => addedItem.value.end], ([newStart, newEnd]) => {
    addedItem.value.duration = calculateDuration(newStart, newEnd)
  })

  watch([() => editedItem.value.start, () => editedItem.value.end], ([newStart, newEnd]) => {
    editedItem.value.duration = calculateDuration(newStart, newEnd)
  })

  onMounted(() => {
    fetchDropdowns()
    // fetchTableData() is handled by VDataTableServer @update:options on mount
  })

  return {
    // State
    items, itemsDialog, suppliers, examiners, options,
    itemsSelect, suppliersSelect, optionsSelect, dateRange, filterForm,
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
