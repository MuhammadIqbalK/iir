import { $api } from '@/utils/api'
import { nextTick, onMounted, ref } from 'vue'
import type { VForm } from 'vuetify/components/VForm'

export const useExaminerLogic = () => {
    // 👉 Search State
    const examinerNames = ref([])

    const nameSelect = ref('')
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
        name: '',
    }

    const editedItem = ref({ ...defaultItem })
    const editedIndex = ref(-1)
    const addedItem = ref({ ...defaultItem })

    // 👉 Table State
    const examinerList = ref([])
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
            const response = await $api('/api/examiners-dropdown')
            examinerNames.value = response.data.map((s: any) => ({ title: s.name, value: s.name }))
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

            if (nameSelect.value) params.name = nameSelect.value

            const response = await $api('/api/examiners', { params })

            examinerList.value = response.data
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
                await $api(`/api/examiners/${editedItem.value.id}`, {
                    method: 'PUT',
                    body: editedItem.value
                })
                showMessage('Success', 'Examiner updated successfully', 'success')
            } else {
                await $api('/api/examiners', {
                    method: 'POST',
                    body: addedItem.value
                })
                showMessage('Success', 'Examiner created successfully', 'success')
            }
            close()
            fetchTableData()
            fetchDropdowns()
        } catch (error: any) {
            console.error('Error saving examiner:', error)
            const errorData = error.data
            let errorMessages: string[] = []

            if (errorData && errorData.errors) {
                errorMessages = Object.values(errorData.errors).flat() as string[]
            }

            showMessage('Error', errorData?.message || 'Failed to save examiner', 'error', errorMessages)
        }
    }

    const deleteItemConfirm = async () => {
        try {
            await $api(`/api/examiners/${editedItem.value.id}`, {
                method: 'DELETE'
            })
            showMessage('Success', 'Examiner deleted successfully', 'success')
            closeDelete()
            fetchTableData()
            fetchDropdowns()
        } catch (error) {
            console.error('Error deleting examiner:', error)
            showMessage('Error', 'Failed to delete examiner', 'error')
        }
    }

    const editItem = (item: any) => {
        editedIndex.value = examinerList.value.indexOf(item)
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
        editedIndex.value = examinerList.value.indexOf(item)
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

        if (page.value === 1) {
            fetchTableData()
        } else {
            page.value = 1
        }
    }

    // 👉 Watchers
    // Watchers on page/itemsPerPage are removed to prevent double fetch.
    // VDataTableServer already triggers fetchTableData via @update:options.

    onMounted(() => {
        fetchDropdowns()
        // fetchTableData() is NOT called here because VDataTableServer 
        // triggers its first @update:options event automatically on mount.
    })

    return {
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
    }
}
