<script setup lang="ts">
import { requiredValidator } from '@/@core/utils/validators'
import { $api } from '@/utils/api'
import { nextTick, onMounted, ref, watch } from 'vue'
import type { VForm } from 'vuetify/components/VForm'

// 👉 Search 
const statuses = ['Active', 'Inactive', 'Blocked'] as const
const supplierNames = ref([])
const supplierCodes = ref([])

const nameSelect = ref('')
const codeSelect = ref('')
const statusSelect = ref()
const form = ref<VForm>()
const refForm = ref<VForm>()
const refEditForm = ref<VForm>()

// 👉 Data
const editDialog = ref(false)
const addDialog = ref(false)
const deleteDialog = ref(false)

const defaultItem = ref({
  id: -1,
  supplier_name: '',
  supplier_code: '',
  contact_person: '',
  email: '',
  phone: '',
  address: '',
  status: 'Active',
})

const editedItem = ref({ ...defaultItem.value })
const editedIndex = ref(-1)
const addedItem = ref({ ...defaultItem.value })
const userList = ref([])
const totalItems = ref(0)
const itemsPerPage = ref(10)
const page = ref(1)
const loading = ref(false)

// headers
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

// 👉 methods
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

const editItem = (item: any) => {
  editedIndex.value = userList.value.indexOf(item)
  editedItem.value = { ...item }
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
    editedItem.value = { ...defaultItem.value }
    addedItem.value = { ...defaultItem.value }
  })
}

const closeDelete = () => {
  deleteDialog.value = false
  nextTick(() => {
    editedIndex.value = -1
    editedItem.value = { ...defaultItem.value }
  })
}

const messageDialog = ref(false)
const messageTitle = ref('')
const messageText = ref('')
const messageIcon = ref('')
const messageColor = ref('')
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
  } catch (error) {
    console.error('Error deleting supplier:', error)
    showMessage('Error', 'Failed to delete supplier', 'error')
  }
}

const searchData = () => {
  page.value = 1
  fetchTableData()
}

const resetFilter = () => {
  form.value?.reset()
  nameSelect.value = ''
  codeSelect.value = ''
  statusSelect.value = undefined
  page.value = 1
  fetchTableData()
}

watch([page, itemsPerPage], () => {
  fetchTableData()
})

watch(nameSelect, newVal => {
  if (newVal) codeSelect.value = ''
})

watch(codeSelect, newVal => {
  if (newVal) nameSelect.value = ''
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
            <VCol cols="4">
              <AppAutocomplete id="filter-name" v-model="nameSelect" :items="supplierNames" placeholder="Select Supplier Name" label="Supplier Name" name="supplierName" clearable :disabled="!!codeSelect" :hint="codeSelect ? 'Clear Supplier Code to enable' : ''" persistent-hint />
            </VCol>

            <VCol cols="4">
              <AppAutocomplete id="filter-code" v-model="codeSelect" :items="supplierCodes" placeholder="Select Supplier Code" label="Supplier Code" name="supplierCode" clearable :disabled="!!nameSelect" :hint="nameSelect ? 'Clear Supplier Name to enable' : ''" persistent-hint />
            </VCol>

            <VCol cols="4">
              <AppAutocomplete id="filter-status" v-model="statusSelect" :items="statuses"
                placeholder="Select Status" label="Status" name="status" clearable />
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
    <VCard title="Suppliers List">
      <VCardText>
        <div class="d-flex flex-wrap gap-4 mb-4">
          <VBtn color="success" @click="addItem()">Add Supplier</VBtn>
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
          <!-- Status -->
          <template #item.status="{ item }">
            <VChip
              :color="item.status === 'Active' ? 'success' : item.status === 'Inactive' ? 'secondary' : 'error'"
              size="small"
              class="text-capitalize"
            >
              {{ item.status }}
            </VChip>
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
    <VCard title="Add Supplier">
      <VCardText>
        <VForm ref="refForm" @submit.prevent="save" validate-on="submit">
          <VRow>
            <!-- Supplier Name -->
            <VCol cols="12" sm="6">
              <AppTextField id="add-name" v-model="addedItem.supplier_name" label="Supplier Name" :rules="[requiredValidator]" required />
            </VCol>
            
            <!-- Supplier Code -->
            <VCol cols="12" sm="6">
              <AppTextField id="add-code" v-model="addedItem.supplier_code" label="Supplier Code" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Contact Person -->
            <VCol cols="12" sm="6">
              <AppTextField id="add-contact" v-model="addedItem.contact_person" label="Contact Person" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Email -->
            <VCol cols="12" sm="6">
              <AppTextField id="add-email" v-model="addedItem.email" label="Email" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Phone -->
            <VCol cols="12" sm="6">
              <AppTextField id="add-phone" v-model="addedItem.phone" label="Phone" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Address -->
            <VCol cols="12" sm="6">
              <AppTextField id="add-address" v-model="addedItem.address" label="Address" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Status -->
            <VCol cols="12" sm="6">
              <AppSelect id="add-status" v-model="addedItem.status" :items="statuses" label="Status" :rules="[requiredValidator]" required />
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
    <VCard title="Edit Supplier">
      <VCardText>
        <VForm ref="refEditForm" @submit.prevent="save" validate-on="submit">
          <VRow>
            <!-- Supplier Name -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.supplier_name" label="Supplier Name" :rules="[requiredValidator]" required />
            </VCol>
            
            <!-- Supplier Code -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.supplier_code" label="Supplier Code" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Contact Person -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.contact_person" label="Contact Person" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Email -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.email" label="Email" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Phone -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.phone" label="Phone" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Address -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="editedItem.address" label="Address" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Status -->
            <VCol cols="12" sm="6">
              <AppSelect v-model="editedItem.status" :items="statuses" label="Status" :rules="[requiredValidator]" required />
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
    <VCard title="Are you sure you want to delete this supplier?">
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
