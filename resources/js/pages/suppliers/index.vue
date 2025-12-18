<script setup lang="ts">
import { requiredValidator } from '@/@core/utils/validators'
import { onMounted, ref } from 'vue'
import type { VForm } from 'vuetify/components/VForm'
import data from './data'

// 👉 Search 
const supplierNames = ['Acme Corp', 'Globex Corporation', 'Soylent Corp', 'Initech', 'Umbrella Corp', 'Stark Industries', 'Wayne Enterprises', 'Cyberdyne Systems', 'Massive Dynamic', 'Aperture Science'] as const
const supplierCodes = ['SUP001', 'SUP002', 'SUP003', 'SUP004', 'SUP005', 'SUP006', 'SUP007', 'SUP008', 'SUP009', 'SUP010'] as const
const statuses = ['Active', 'Inactive', 'Blocked'] as const

const nameSelect = ref<typeof supplierNames[number]>()
const codeSelect = ref<typeof supplierCodes[number]>()
const statusSelect = ref<typeof statuses[number]>()
const form = ref<VForm>()

const originalData = ref([])

// 👉 Data

const editDialog = ref(false)
const addDialog = ref(false)
const deleteDialog = ref(false)

const defaultItem = ref({
  id: -1,
  supplierName: '',
  supplierCode: '',
  contactPerson: '',
  email: '',
  phone: '',
  address: '',
  status: 'Active',
})

const editedItem = ref(defaultItem.value)
const editedIndex = ref(-1)
const addedItem = ref(defaultItem.value)
const addIndex = ref(+1)
const userList = ref([])

// headers
const headers = [
  { title: 'SUPPLIER NAME', key: 'supplierName' },
  { title: 'CODE', key: 'supplierCode' },
  { title: 'CONTACT PERSON', key: 'contactPerson' },
  { title: 'EMAIL', key: 'email' },
  { title: 'PHONE', key: 'phone' },
  { title: 'ADDRESS', key: 'address' },
  { title: 'STATUS', key: 'status' },
  { title: 'ACTIONS', key: 'actions' },
]

// 👉 methods
const editItem = item => {
  editedIndex.value = userList.value.indexOf(item)
  editedItem.value = { ...item }
  editDialog.value = true
}

const addItem = () => {
  addIndex.value = userList.value.length + 1
  addedItem.value = { ...defaultItem.value }
  addDialog.value = true
}

const deleteItem = item => {
  editedIndex.value = userList.value.indexOf(item)
  editedItem.value = { ...item }
  deleteDialog.value = true
}

const close = () => {
  editDialog.value = false
  addDialog.value = false
  editedIndex.value = -1
  editedItem.value = { ...defaultItem.value }
  addedItem.value = { ...defaultItem.value }
}

const closeDelete = () => {
  deleteDialog.value = false
  editedIndex.value = -1
  editedItem.value = { ...defaultItem.value }
}

const save = () => {
  if (editedIndex.value > -1)
    Object.assign(userList.value[editedIndex.value], editedItem.value)
  else
    userList.value.push(editedItem.value)
  close()
}

const saveNew = () => {
  userList.value.push({ ...addedItem.value, id: userList.value.length + 1 })
  close()
}

const deleteItemConfirm = () => {
  userList.value.splice(editedIndex.value, 1)
  closeDelete()
}

const searchData = async () => {
  const { valid } = await form.value?.validate()
  if (!valid)
    return

  // Start with original data
  let filtered = JSON.parse(JSON.stringify(originalData.value))

  // Filter by Supplier Name
  if (nameSelect.value) {
    filtered = filtered.filter(item => item.supplierName.includes(nameSelect.value))
  }

  // Filter by Supplier Code
  if (codeSelect.value) {
    filtered = filtered.filter(item => item.supplierCode.includes(codeSelect.value))
  }

  // Filter by Status
  if (statusSelect.value) {
    filtered = filtered.filter(item => item.status === statusSelect.value)
  }

  userList.value = filtered
}

const resetFilter = () => {
  form.value?.reset()
  nameSelect.value = undefined
  codeSelect.value = undefined
  statusSelect.value = undefined
  userList.value = JSON.parse(JSON.stringify(originalData.value))
}

onMounted(() => {
  originalData.value = JSON.parse(JSON.stringify(data))
  userList.value = JSON.parse(JSON.stringify(data))
})
</script>

<template>

  <!-- (1) FILTER FORM -->
  <VCol cols="12" md="12">
    <VCard title="Filter Form" noAction="true">
      <VCol cols="12" md="12">
        <VForm ref="form" lazy-validation>
          <VRow>
            <VCol cols="4">
              <AppAutocomplete id="filter-name" v-model="nameSelect" :items="supplierNames" placeholder="Select Supplier Name" label="Supplier Name" name="supplierName" />
            </VCol>

            <VCol cols="4">
              <AppAutocomplete id="filter-code" v-model="codeSelect" :items="supplierCodes" placeholder="Select Supplier Code" label="Supplier Code"
                name="supplierCode" />
            </VCol>

            <VCol cols="4">
              <AppAutocomplete id="filter-status" v-model="statusSelect" :items="statuses"
                placeholder="Select Status" label="Status" name="status" />
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
      </VCol>
    </VCard>
  </VCol>

  <!-- (2) DATA TABLE -->
  <VCol cols="12" md="12">
    <VCard title="Suppliers List" noAction="true">
      <VCol cols="12" class="d-flex flex-wrap gap-4">
        <VBtn color="success" @click="addItem()">Add Supplier</VBtn>
      </VCol>
      <VDataTable :headers="headers" :items="userList" :items-per-page="5">
        
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
      </VDataTable>
    </VCard>
  </VCol>


  <!-- � Add Dialog  -->
  <VDialog v-model="addDialog" max-width="600px">
    <VCard title="Add Supplier">
      <VCardText>
        <VRow>
          
          <!-- Supplier Name -->
          <VCol cols="12" sm="6">
            <AppTextField id="add-name" v-model="addedItem.supplierName" label="Supplier Name" :rules="[requiredValidator]" />
          </VCol>
          
          <!-- Supplier Code -->
          <VCol cols="12" sm="6">
            <AppTextField id="add-code" v-model="addedItem.supplierCode" label="Supplier Code" :rules="[requiredValidator]" />
          </VCol>

          <!-- Contact Person -->
          <VCol cols="12" sm="6">
            <AppTextField id="add-contact" v-model="addedItem.contactPerson" label="Contact Person" />
          </VCol>

          <!-- Email -->
          <VCol cols="12" sm="6">
            <AppTextField id="add-email" v-model="addedItem.email" label="Email" />
          </VCol>

          <!-- Phone -->
          <VCol cols="12" sm="6">
            <AppTextField id="add-phone" v-model="addedItem.phone" label="Phone" />
          </VCol>

          <!-- Address -->
          <VCol cols="12" sm="6">
            <AppTextField id="add-address" v-model="addedItem.address" label="Address" />
          </VCol>

          <!-- Status -->
          <VCol cols="12" sm="6">
            <AppSelect id="add-status" v-model="addedItem.status" :items="statuses" label="Status" />
          </VCol>
        </VRow>
      </VCardText>

      <VCardText>
        <div class="self-align-end d-flex gap-4 justify-end">
          <VBtn color="error" variant="outlined" @click="close">
            Cancel
          </VBtn>
          <VBtn color="success" variant="elevated" @click="saveNew">
            Save
          </VBtn>
        </div>
      </VCardText>
    </VCard>
  </VDialog>


  <!-- 👉 Edit Dialog  -->
  <VDialog v-model="editDialog" max-width="600px">
    <VCard title="Edit Supplier">
      <VCardText>
        <VRow>
          <!-- Supplier Name -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.supplierName" label="Supplier Name" :rules="[requiredValidator]" />
          </VCol>
          
          <!-- Supplier Code -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.supplierCode" label="Supplier Code" :rules="[requiredValidator]" />
          </VCol>

          <!-- Contact Person -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.contactPerson" label="Contact Person" />
          </VCol>

          <!-- Email -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.email" label="Email" />
          </VCol>

          <!-- Phone -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.phone" label="Phone" />
          </VCol>

          <!-- Address -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.address" label="Address" />
          </VCol>

          <!-- Status -->
          <VCol cols="12" sm="6">
            <AppSelect v-model="editedItem.status" :items="statuses" label="Status" />
          </VCol>
        </VRow>
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

</template>
