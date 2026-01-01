<script setup lang="ts">
import { ref } from 'vue';
import type { VForm } from 'vuetify/components/VForm';

interface Props {
  examinerNames: any[]
}

const props = defineProps<Props>()

const nameSelect = defineModel('nameSelect')

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
            <VCol cols="12">
              <AppAutocomplete id="filter-name" v-model="nameSelect" :items="props.examinerNames" placeholder="Select Examiner Name" label="Examiner Name" name="examinerName" clearable />
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
