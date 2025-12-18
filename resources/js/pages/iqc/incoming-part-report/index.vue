<script setup lang="ts">
import { requiredValidator } from '@/@core/utils/validators';
import { onMounted, ref } from 'vue';
import type { VForm } from 'vuetify/components/VForm';
import data from './data';

// 👉 Search 
const items = [
  '4206 136 58775',
  '4206 136 58776',
  '4206 136 58777',
  '4206 136 58778',
  '4206 136 58779',
  '4206 136 58780',
  '4206 136 58781',
  '4206 136 58782',
  '4206 136 58783',
  '4206 136 58784',
  '4206 136 58785',
  '4206 136 58786',
  '4206 136 58787',
  '4206 136 58788',
  '4206 136 58789',
  '4206 136 58790',
  '4206 136 58791',
  '4206 136 58792',
  '4206 136 58793',
  '4206 136 58794',
] as const;
const suppliers = [
  'PT Maju Jaya',
  'Global Parts Ltd',
  'PT Sinar Elektronik',
  'Asia Cooling Corp',
  'PT Daya Listrik',
  'Mechanical World Inc',
  'PT Kabel Nusantara',
  'Tech Board Co',
  'PT Tekanan Abadi',
  'Motion System GmbH',
  'PT Mesin Presisi',
  'SKF Industrial',
  'PT Seal Indonesia',
  'SensorTech Ltd',
  'PT Baja Ringan',
  'Metal Works Co',
  'PT Kabelindo',
  'Heat Protection Inc',
  'PT Filter Sejahtera',
  'Oil System Ltd',
] as const;
const options = ['Local', 'Import'] as const
const itemsSelect = ref<typeof items[number]>()
const suppliersSelect = ref<typeof suppliers[number]>()
const optionsSelect = ref<typeof options[number]>()
const form = ref<VForm>()

const dateRange = ref('')
const originalData = ref([])

// 👉 Data

const editDialog = ref(false)
const addDialog = ref(false)
const deleteDialog = ref(false)

const defaultItem = ref({
  iirdate: '',
  itemnc: '',
  partname: '',
  nodoc: '',
  quantity: 0,
  samplesize: 0,
  gilevel: 1,
  examiner: '',
  start: '',
  end: '',
  duration: '',
  supplier: '',
  option: '',
})

const editedItem = ref(defaultItem.value)
const editedIndex = ref(-1)
const addedItem = ref(defaultItem.value)
const addIndex = ref(+1)
const userList = ref([])

// status options
const selectedOptions = [
  { text: 'Current', value: 1 },
  { text: 'Professional', value: 2 },
  { text: 'Rejected', value: 3 },
  { text: 'Resigned', value: 4 },
  { text: 'Applied', value: 5 },
]

// headers
const headers = [
  { title: 'IIR DATE', key: 'iirdate' },
  { title: 'ITEM NC', key: 'itemnc' },
  { title: 'PART NAME', key: 'partname' },
  { title: 'NODOC', key: 'nodoc' },
  { title: 'QTY', key: 'quantity' },
  { title: 'SAMPLE SIZE', key: 'samplesize' },
  { title: 'GIL LEVEL', key: 'gilevel' },
  { title: 'EXAMINER', key: 'examiner' },
  { title: 'START', key: 'start' },
  { title: 'END', key: 'end' },
  { title: 'SUPPLIER', key: 'supplier' },
  { title: 'OPTION', key: 'option' },
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

  // Filter by item (12 NC)
  if (itemsSelect.value) {
    filtered = filtered.filter(item => item.itemnc.includes(itemsSelect.value))
  }

  // Filter by supplier
  if (suppliersSelect.value) {
    filtered = filtered.filter(item => item.supplier.includes(suppliersSelect.value))
  }

  // Filter by option
  if (optionsSelect.value) {
    filtered = filtered.filter(item => item.option.toLowerCase() === optionsSelect.value.toLowerCase())
  }

  // Filter by date range
  if (dateRange.value) {
    const dates = dateRange.value.split(' to ')
    if (dates.length === 2) {
      const startDate = new Date(dates[0])
      const endDate = new Date(dates[1])
      
      filtered = filtered.filter(item => {
        const itemDate = new Date(item.iirdate)
        return itemDate >= startDate && itemDate <= endDate
      })
    }
  }

  userList.value = filtered
}

const resetFilter = () => {
  form.value?.reset()
  itemsSelect.value = undefined
  suppliersSelect.value = undefined
  optionsSelect.value = undefined
  dateRange.value = ''
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
            <VCol cols="6">
              <AppAutocomplete id="filter-item" v-model="itemsSelect" :items="items" placeholder="Select an 12 NC" label="Item" name="item" />
            </VCol>

            <VCol cols="6">
              <AppAutocomplete id="filter-supplier" v-model="suppliersSelect" :items="suppliers" placeholder="Select an Supplier" label="Supplier"
                name="supplier" />
            </VCol>

            <VCol cols="6">
              <AppAutocomplete id="filter-option" v-model="optionsSelect" :items="options" :rules="[requiredValidator]"
                placeholder="Select an Option" label="Option" name="option" required />
            </VCol>

            <VCol cols="6">
              <AppDateTimePicker v-model="dateRange" label="Date Range" placeholder="Select date range"
                :config="{ mode: 'range' }" name="dateRange" />
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
    <VCard title="Data Table" noAction="true">
      <VCol cols="12" class="d-flex flex-wrap gap-4">
        <VBtn color="success" @click="addItem()">Add IIR</VBtn>
        <VBtn color="primary" @click="form?.reset()">Global Print</VBtn>
      </VCol>
      <VDataTable :headers="headers" :items="userList" :items-per-page="5">

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


  <!-- 👉 Add Dialog  -->
  <VDialog v-model="addDialog" max-width="600px">
    <VCard title="Add Item">
      <VCardText>
        <VRow>
          
      <!-- No Doc -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="addedItem.nodoc" label="No Doc" />
          </VCol>
          
          <!-- IIR Date -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="addedItem.iirdate" label="IIR Date" placeholder="MM/DD/YYYY" />
          </VCol>

          <!-- Item NC -->
          <VCol cols="12" sm="6">
            <AppAutocomplete v-model="addedItem.itemnc" :items="items" label="Item NC" />
          </VCol>

          <!-- Part Name -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="addedItem.partname" label="Part Name" />
          </VCol>

          <!-- Quantity -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="addedItem.quantity" label="Quantity" type="number" />
          </VCol>

          <!-- Sample Size -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="addedItem.samplesize" label="Sample Size" type="number" />
          </VCol>

          <!-- GI Level -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="addedItem.gilevel" label="GI Level" type="number" />
          </VCol>

          <!-- Examiner -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="addedItem.examiner" label="Examiner" />
          </VCol>

          <!-- Start Time -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="addedItem.start" label="Start Time" placeholder="HH:MM" />
          </VCol>

          <!-- End Time -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="addedItem.end" label="End Time" placeholder="HH:MM" />
          </VCol>

          <!-- Duration -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="addedItem.duration" label="Duration" placeholder="HH:MM" />
          </VCol>

          <!-- Supplier -->
          <VCol cols="12" sm="6">
            <AppAutocomplete v-model="addedItem.supplier" :items="suppliers" label="Supplier" />
          </VCol>

          <!-- Option -->
          <VCol cols="12" sm="6">
            <AppAutocomplete v-model="addedItem.option" :items="options" label="Option" />
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


  <!-- 👉 Edit Dialog  -->
  <VDialog v-model="editDialog" max-width="600px">
    <VCard title="Edit Item">
      <VCardText>
        <VRow>
          
          <!-- No Doc -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.nodoc" label="No Doc" />
          </VCol>

          <!-- IIR Date -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.iirdate" label="IIR Date" placeholder="MM/DD/YYYY" />
          </VCol>

          <!-- Item NC -->
          <VCol cols="12" sm="6">
            <AppAutocomplete v-model="editedItem.itemnc" :items="items" label="Item NC" />
          </VCol>

          <!-- Part Name -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.partname" label="Part Name" />
          </VCol>

          <!-- Quantity -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.quantity" label="Quantity" type="number" />
          </VCol>

          <!-- Sample Size -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.samplesize" label="Sample Size" type="number" />
          </VCol>

          <!-- GI Level -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.gilevel" label="GI Level" type="number" />
          </VCol>

          <!-- Examiner -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.examiner" label="Examiner" />
          </VCol>

          <!-- Start Time -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.start" label="Start Time" placeholder="HH:MM" />
          </VCol>

          <!-- End Time -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.end" label="End Time" placeholder="HH:MM" />
          </VCol>

          <!-- Duration -->
          <VCol cols="12" sm="6">
            <AppTextField v-model="editedItem.duration" label="Duration" placeholder="HH:MM" />
          </VCol>

          <!-- Supplier -->
          <VCol cols="12" sm="6">
            <AppAutocomplete v-model="editedItem.supplier" :items="suppliers" label="Supplier" />
          </VCol>

          <!-- Option -->
          <VCol cols="12" sm="6">
            <AppAutocomplete v-model="editedItem.option" :items="options" label="Option" />
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

</template>
