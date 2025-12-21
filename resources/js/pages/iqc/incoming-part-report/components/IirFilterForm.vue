<script setup lang="ts">
interface Props {
  items: any[]
  suppliers: any[]
  options: readonly string[]
}

const props = defineProps<Props>()

const itemsSelect = defineModel('itemsSelect')
const suppliersSelect = defineModel('suppliersSelect')
const optionsSelect = defineModel('optionsSelect')
const dateRange = defineModel('dateRange')

const emit = defineEmits(['search', 'reset'])
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

              <VBtn color="error" @click="emit('reset')">
                Reset Filter
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VCol>
</template>
