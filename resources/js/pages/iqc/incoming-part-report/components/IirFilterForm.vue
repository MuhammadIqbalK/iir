<script setup lang="ts">
import { ref } from 'vue';

interface Props {
  items: any[]
  suppliers: any[]
  options: readonly string[]
}

const props = defineProps<Props>()

const itemsSelect = defineModel<string | undefined>('itemsSelect')
const suppliersSelect = defineModel<string | undefined>('suppliersSelect')
const optionsSelect = defineModel<string | undefined>('optionsSelect')
const dateRange = defineModel<string | undefined>('dateRange')

const emit = defineEmits(['search', 'reset', 'Global', 'Recap'])

const showPrintConfirmation = ref(false)
const printType = ref<'Global' | 'Recap'>('Global')
const filterSummary = ref('')

const getFilterSummary = () => {
  const filters = []
  
  if (itemsSelect.value) {
    filters.push(`Item: ${itemsSelect.value}`)
  }
  
  if (suppliersSelect.value) {
    filters.push(`Supplier: ${suppliersSelect.value}`)
  }
  
  if (optionsSelect.value) {
    filters.push(`Option: ${optionsSelect.value}`)
  }
  
  if (dateRange.value) {
    const dates = (dateRange.value as string).split(' to ')
    if (dates.length === 2) {
      filters.push(`Date Range: ${dates[0]} to ${dates[1]}`)
    } else if (dates.length === 1) {
      filters.push(`Date: ${dates[0]}`)
    }
  }
  
  return filters.length > 0 ? filters.join(', ') : 'No filters applied'
}

const handlePrint = (type: 'Global' | 'Recap') => {
  printType.value = type
  filterSummary.value = getFilterSummary()
  showPrintConfirmation.value = true
}

const confirmPrint = () => {
  emit(printType.value)
  showPrintConfirmation.value = false
}

const cancelPrint = () => {
  showPrintConfirmation.value = false
}
</script>

<template>
  <VCol cols="12" md="12">
    <VCard title="Filter Form">
      <VCardText>
        <VForm lazy-validation>
          <VRow>
            <VCol cols="6">
              <AppAutocomplete id="filter-item" v-model="itemsSelect" :items="props.items" placeholder="Select an 12 NC" label="Item" name="item" clearable />
            </VCol>

            <VCol cols="6">
              <AppAutocomplete id="filter-supplier" v-model="suppliersSelect" :items="props.suppliers" placeholder="Select a Supplier" label="Supplier"
                name="supplier" clearable />
            </VCol>

            <VCol cols="6">
              <AppAutocomplete id="filter-option" v-model="optionsSelect" :items="props.options"
                placeholder="Select an Option" label="Option" name="option" clearable />
            </VCol>

            <VCol cols="6">
              <AppDateTimePicker v-model="dateRange" label="Date Range" placeholder="Select date range"
                :config="{ mode: 'range' }" name="dateRange" clearable />
            </VCol>

            <VCol cols="12" class="d-flex flex-wrap gap-4">
              <VBtn color="success" @click="emit('search')">
                Search
              </VBtn>

              <VBtn color="primary" @click="handlePrint('Global')">
                Print Global
              </VBtn>

              <VBtn color="warning" @click="handlePrint('Recap')">
                Print Recap
              </VBtn>

              <VBtn color="error" @click="emit('reset')">
                Reset Filter
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VCol>

  <!-- Print Confirmation Dialog -->
  <VDialog :model-value="showPrintConfirmation" @update:model-value="showPrintConfirmation = $event" max-width="500">
    <VCard>
      <VCardTitle>
        Confirm {{ printType }} Print
      </VCardTitle>
      <VCardText>
        <p>Are you sure you want to print {{ printType.toLowerCase() }} report with the following filters?</p>
        <div v-if="filterSummary" class="mt-2">
          <VAlert type="info" variant="tonal">
            <strong>Applied Filters:</strong> {{ filterSummary }}
          </VAlert>
        </div>
        <div v-else class="mt-2">
          <VAlert type="warning" variant="tonal">
            No filters applied. This will print all data.
          </VAlert>
        </div>
      </VCardText>
      <VCardActions>
        <VBtn color="error" variant="text" @click="cancelPrint">Cancel</VBtn>
        <VBtn color="primary" @click="confirmPrint">Confirm Print</VBtn>
      </VCardActions>
    </VCard>
  </VDialog>
</template>
