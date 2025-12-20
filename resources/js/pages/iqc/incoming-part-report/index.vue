<script setup lang="ts">
import { betweenValidator, integerValidator, requiredValidator } from '@/@core/utils/validators'
import { $api } from '@/utils/api'
import { nextTick, onMounted, ref, watch } from 'vue'
import type { VForm } from 'vuetify/components/VForm'

// 👉 Search 
const items = ref([])
const itemsDialog = ref([])
const rawItems = ref([])
const suppliers = ref([])
const examiners = ref([])
const options = ['Local', 'Import'] as const

const itemsSelect = ref()
const itemsDialogSelect = ref()
const suppliersSelect = ref()
const optionsSelect = ref()
const form = ref<VForm>()
const refForm = ref<VForm>()
const refEditForm = ref<VForm>()

const dateRange = ref('')

// 👉 Data
const editDialog = ref(false)
const addDialog = ref(false)
const deleteDialog = ref(false)

const defaultItem = ref({
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
})

const editViewItem = ref({
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
})

const editedItem = ref({ ...editViewItem.value })
const editedIndex = ref(-1)
const addedItem = ref({ ...defaultItem.value })
const userList = ref([])
const totalItems = ref(0)
const itemsPerPage = ref(10)
const page = ref(1)
const loading = ref(false)

// headers
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
  { title: 'ACTIONS', key: 'actions', sortable: false },
]

// 👉 methods
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
    
    if (response.total !== undefined) {
      totalItems.value = response.total
    } else if (response.meta && response.meta.total) {
      totalItems.value = response.meta.total
    } else {
      totalItems.value = response.total || 0
    }

  } catch (error) {
    console.error('Error fetching table data:', error)
  } finally {
    loading.value = false
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
  addedItem.value = { ...defaultItem.value }
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

const close = () => {
  editDialog.value = false
  addDialog.value = false
  
  nextTick(() => {
    refForm.value?.resetValidation()
    refEditForm.value?.resetValidation()
    editedIndex.value = -1
    editedItem.value = { ...editViewItem.value }
    addedItem.value = { ...defaultItem.value }
  })
}

const closeDelete = () => {
  deleteDialog.value = false
  nextTick(() => {
    editedIndex.value = -1
    editedItem.value = { ...editViewItem.value }
  })
}

const messageDialog = ref(false)
const messageTitle = ref('')
const messageText = ref('')
const messageIcon = ref('')
const messageColor = ref('')

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

const messageErrors = ref<string[]>([])

const showMessage = (title: string, text: string, type: 'success' | 'error' | 'warning' | 'info' = 'success', errors: string[] = []) => {
  messageTitle.value = title
  messageText.value = text
  messageErrors.value = errors
  messageDialog.value = true
  
  switch (type) {
    case 'success':
      messageIcon.value = 'tabler-circle-check'
      messageColor.value = 'success'
      break;
    case 'error':
      messageIcon.value = 'tabler-alert-circle'
      messageColor.value = 'error'
      break;
    case 'warning':
      messageIcon.value = 'tabler-alert-triangle'
      messageColor.value = 'warning'
      break;
    case 'info':
      messageIcon.value = 'tabler-info-circle'
      messageColor.value = 'info'
      break
  }
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

const searchData = () => {
  page.value = 1
  fetchTableData()
}

const resetFilter = () => {
  form.value?.reset()
  itemsSelect.value = undefined
  suppliersSelect.value = undefined
  optionsSelect.value = undefined
  dateRange.value = ''
  page.value = 1
  fetchTableData()
}

watch([page, itemsPerPage], () => {
  fetchTableData()
})

watch(() => addedItem.value.itemnc, (newVal) => {
  const item = rawItems.value.find((i: any) => i.item12nc === newVal)
  if (item) {
    addedItem.value.partname = item.partname
  }
})

watch(() => editedItem.value.itemnc, (newVal) => {
  const item = rawItems.value.find((i: any) => i.item12nc === newVal)
  if (item) {
    editedItem.value.partname = item.partname
  }
})

watch([() => addedItem.value.start, () => addedItem.value.end], ([newStart, newEnd]) => {
  addedItem.value.duration = calculateDuration(newStart, newEnd)
})

watch([() => editedItem.value.start, () => editedItem.value.end], ([newStart, newEnd]) => {
  editedItem.value.duration = calculateDuration(newStart, newEnd)
})

onMounted(() => {
  fetchDropdowns()
  fetchTableData()
})
</script>

<template>

  <!-- (1) FILTER FORM -->
  <VCol cols="12" md="12">
    <VCard title="Filter Form">
      <VCardText>
        <VForm ref="form" lazy-validation>
          <VRow>
            <VCol cols="6">
              <AppAutocomplete id="filter-item" v-model="itemsSelect" :items="items" placeholder="Select an 12 NC" label="Item" name="item" clearable />
            </VCol>

            <VCol cols="6">
              <AppAutocomplete id="filter-supplier" v-model="suppliersSelect" :items="suppliers" placeholder="Select a Supplier" label="Supplier"
                name="supplier" clearable />
            </VCol>

            <VCol cols="6">
              <AppAutocomplete id="filter-option" v-model="optionsSelect" :items="options"
                placeholder="Select an Option" label="Option" name="option" clearable />
            </VCol>

            <VCol cols="6">
              <AppDateTimePicker v-model="dateRange" label="Date Range" placeholder="Select date range"
                :config="{ mode: 'range' }" name="dateRange" clearable />
            </VCol>

            <VCol cols="12" class="d-flex flex-wrap gap-4">
              <VBtn color="success" @click="searchData">
                Search
              </VBtn>

              <VBtn color="error" @click="resetFilter">
                Reset Filter
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VCol>

  <!-- (2) DATA TABLE -->
  <VCol cols="12" md="12">
    <VCard title="Data Table">
      <VCardText>
        <div class="d-flex flex-wrap gap-4 mb-4">
          <VBtn color="success" @click="addItem()">Add IIR</VBtn>
          <VBtn color="primary" @click="form?.reset()">Global Print</VBtn>
        </div>
        
        <VDataTable 
          :headers="headers" 
          :items="userList" 
          :items-per-page="itemsPerPage"
          :page="page"
          :items-length="totalItems"
          :loading="loading"
          @update:options="fetchTableData"
          server-items-length
        >

            <template #item.iirdate="{ item }">
              <div class="w-200 align-center">
                {{ item.iirdate }}
              </div>
            </template>

            <template #item.itemnc="{ item }">
              <div class="w-200 align-center">
                {{ item.itemnc }}
              </div>
            </template>

          <!-- Actions -->
          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <IconBtn @click="editItem(item)">
                <VIcon icon="tabler-edit" />
              </IconBtn>
              <IconBtn @click="deleteItem(item)">
                <VIcon icon="tabler-trash" />
              </IconBtn>
            </div>
          </template>

          <template #bottom>
            <VCardText class="pt-2">
              <div class="d-flex flex-wrap justify-center justify-sm-space-between gap-y-2 mt-2">
                <VSelect
                  v-model="itemsPerPage"
                  :items="[5, 10, 25, 50, 100]"
                  label="Rows per page:"
                  variant="underlined"
                  style="max-inline-size: 8rem;min-inline-size: 5rem;"
                />

                <VPagination
                  v-model="page"
                  :total-visible="$vuetify.display.smAndDown ? 3 : 5"
                  :length="Math.ceil(totalItems / itemsPerPage)"
                />
              </div>
            </VCardText>
          </template>
        </VDataTable>
      </VCardText>
    </VCard>
  </VCol>


  <!-- 👉 Add Dialog  -->
  <VDialog v-model="addDialog" max-width="600px" persistent>
    <VCard title="Add Item">
      <VCardText>
        <VForm ref="refForm" @submit.prevent="save" validate-on="submit">
          <VRow>
            <!-- No Doc -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="addedItem.nodoc" label="No Doc" :rules="[requiredValidator]" required />
            </VCol>
            
            <!-- IIR Date -->
            <VCol cols="12" sm="6">
              <AppDateTimePicker v-model="addedItem.iirdate" label="IIR Date" placeholder="YYYY-MM-DD" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Item NC -->
            <VCol cols="12" sm="6">
              <AppAutocomplete v-model="addedItem.itemnc" :items="itemsDialog" label="Item NC" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Part Name -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="addedItem.partname" label="Part Name *automatically filled" readonly />
            </VCol>

            <!-- Quantity -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="addedItem.quantity" label="Quantity" type="number" :rules="[requiredValidator, integerValidator, v => betweenValidator(v, 1, 999999)]" required />
            </VCol>

            <!-- Sample Size -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="addedItem.samplesize" label="Sample Size" type="number" :rules="[requiredValidator, integerValidator, v => betweenValidator(v, 1, 999999)]" required />
            </VCol>

            <!-- GI Level -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="addedItem.gilevel" label="GI Level" type="number" :rules="[requiredValidator, integerValidator, v => betweenValidator(v, 1, 999999)]" required />
            </VCol>

            <!-- Examiner -->
            <VCol cols="12" sm="6">
              <AppAutocomplete v-model="addedItem.examiner_id" :items="examiners" :rules="[requiredValidator]" required label="Examiner" />
            </VCol>

            <!-- Start Time -->
            <VCol cols="12" sm="6">
              <AppDateTimePicker v-model="addedItem.start" :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i' }" :rules="[requiredValidator]" required label="Start Time" placeholder="HH:MM" />
            </VCol>

            <!-- End Time -->
            <VCol cols="12" sm="6">
              <AppDateTimePicker v-model="addedItem.end" :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i' }" :rules="[requiredValidator]" required label="End Time" placeholder="HH:MM" />
            </VCol>

            <!-- Duration -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="addedItem.duration" label="Duration *automatically calculated" placeholder="HH:MM" readonly />
            </VCol>

            <!-- Supplier -->
            <VCol cols="12" sm="6">
              <AppAutocomplete v-model="addedItem.supplier_code" :items="suppliers" :rules="[requiredValidator]" required label="Supplier" />
            </VCol>

            <!-- Option -->
            <VCol cols="12" sm="12">
              <AppAutocomplete v-model="addedItem.option" :items="options" :rules="[requiredValidator]" required label="Option" />
            </VCol>
          </VRow>
        </VForm>
      </VCardText>

      <VCardText>
        <div class="self-align-end d-flex gap-4 justify-end">
          <VBtn color="error" variant="outlined" @click="close">
            Cancel
          </VBtn>
          <VBtn color="success" variant="elevated" @click="save">
            Save
          </VBtn>
        </div>
      </VCardText>
    </VCard>
  </VDialog>


  <!-- 👉 Edit Dialog  -->
  <VDialog v-model="editDialog" max-width="600px" persistent>
    <VCard title="Edit Item">
      <VCardText>
        <VForm ref="refEditForm" @submit.prevent="save" validate-on="submit">
          <VRow>
            <!-- No Doc -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.nodoc" label="No Doc" :rules="[requiredValidator]" required />
            </VCol>
            
            <!-- IIR Date -->
            <VCol cols="12" sm="6">
              <AppDateTimePicker v-model="editedItem.iirdate" label="IIR Date" placeholder="YYYY-MM-DD" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Item NC -->
            <VCol cols="12" sm="6">
              <AppAutocomplete v-model="editedItem.itemnc" :items="itemsDialog" label="Item NC" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Part Name -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.partname" label="Part Name" readonly />
            </VCol>

            <!-- Quantity -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.quantity" label="Quantity" :rules="[requiredValidator, integerValidator, v => betweenValidator(v, 1, 999999)]" required />
            </VCol>

            <!-- Sample Size -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.samplesize" label="Sample Size" :rules="[requiredValidator, integerValidator, v => betweenValidator(v, 1, 999999)]" required />
            </VCol>

            <!-- GI Level -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.gilevel" label="GI Level" :rules="[requiredValidator, integerValidator, v => betweenValidator(v, 1, 999999)]" required />
            </VCol>

            <!-- Examiner -->
            <VCol cols="12" sm="6">
              <AppAutocomplete v-model="editedItem.examiner_id" :items="examiners" label="Examiner" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Start Time -->
            <VCol cols="12" sm="6">
              <AppDateTimePicker v-model="editedItem.start" :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i' }" label="Start Time" placeholder="HH:MM" :rules="[requiredValidator]" required />
            </VCol>

            <!-- End Time -->
            <VCol cols="12" sm="6">
              <AppDateTimePicker v-model="editedItem.end" :config="{ enableTime: true, noCalendar: true, dateFormat: 'H:i' }" label="End Time" placeholder="HH:MM" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Duration -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.duration" label="Duration" placeholder="HH:MM" readonly />
            </VCol>

            <!-- Supplier -->
            <VCol cols="12" sm="6">
              <AppAutocomplete v-model="editedItem.supplier_code" :items="suppliers" label="Supplier" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Option -->
            <VCol cols="12" sm="12">
              <AppAutocomplete v-model="editedItem.option" :items="options" label="Option" :rules="[requiredValidator]" required />
            </VCol>
          </VRow>
        </VForm>
      </VCardText>

      <VCardText>
        <div class="self-align-end d-flex gap-4 justify-end">
          <VBtn color="error" variant="outlined" @click="close">
            Cancel
          </VBtn>
          <VBtn color="success" variant="elevated" @click="save">
            Save
          </VBtn>
        </div>
      </VCardText>
    </VCard>
  </VDialog>


  <!-- 👉 Delete Dialog  -->
  <VDialog v-model="deleteDialog" max-width="500px">
    <VCard title="Are you sure you want to delete this item?">
      <VCardText>
        <div class="d-flex justify-center gap-4">
          <VBtn color="error" variant="outlined" @click="closeDelete">
            Cancel
          </VBtn>
          <VBtn color="success" variant="elevated" @click="deleteItemConfirm">
            OK
          </VBtn>
        </div>
      </VCardText>
    </VCard>
  </VDialog>

  <!-- 👉 Message Dialog -->
  <VDialog v-model="messageDialog" width="500">
    <DialogCloseBtn @click="messageDialog = false" />
    <VCard>
      <VCardText class="d-flex flex-column align-center gap-4 pt-8">
        <VIcon :icon="messageIcon" :color="messageColor" size="48" />
        <h3 class="text-h3" :class="`text-${messageColor}`">{{ messageTitle }}</h3>
        <p class="text-body-1 text-center mb-0">{{ messageText }}</p>
        <div v-if="messageErrors.length" class="w-100 mt-2">
          <VAlert type="error" variant="tonal" density="compact">
            <ul class="mb-0 ps-4">
              <li v-for="(err, index) in messageErrors" :key="index" class="text-caption">
                {{ err }}
              </li>
            </ul>
          </VAlert>
        </div>
      </VCardText>
      <VCardText class="d-flex justify-center pb-8">
        <VBtn :color="messageColor" @click="messageDialog = false">
          OK
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>

</template>
