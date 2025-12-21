<script setup lang="ts">
import { ref } from 'vue'
import type { VForm } from 'vuetify/components/VForm'

interface Props {
  supplierNames: any[];
  supplierCodes: any[];
  statuses: readonly string[]
}

const props = defineProps<Props>()

const nameSelect = defineModel('nameSelect')
const codeSelect = defineModel('codeSelect')
const statusSelect = defineModel('statusSelect')

const emit = defineEmits(['search', 'reset'])

const refVForm = ref<VForm>()

defineExpose({
  reset: () => refVForm.value?.reset(),
})
</script>

<template>
  <VCol cols="12" md="12">
    <VCard title="Filter Form">
      <VCardText>
        <VForm ref="refVForm" lazy-validation>
          <VRow>
            <VCol cols="4">
              <AppAutocomplete id="filter-name" v-model="nameSelect" :items="props.supplierNames" placeholder="Select Supplier Name" label="Supplier Name" name="supplierName" clearable :disabled="!!codeSelect" :hint="codeSelect ? 'Clear Supplier Code to enable' : ''" persistent-hint />
            </VCol>

            <VCol cols="4">
              <AppAutocomplete id="filter-code" v-model="codeSelect" :items="props.supplierCodes" placeholder="Select Supplier Code" label="Supplier Code" name="supplierCode" clearable :disabled="!!nameSelect" :hint="nameSelect ? 'Clear Supplier Name to enable' : ''" persistent-hint />
            </VCol>

            <VCol cols="4">
              <AppAutocomplete id="filter-status" v-model="statusSelect" :items="props.statuses"
                placeholder="Select Status" label="Status" name="status" clearable />
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
