<script setup lang="ts">
import { betweenValidator, integerValidator, requiredValidator } from '@/@core/utils/validators';
import AppTimePicker24 from '@core/components/AppTimePicker24.vue'; // Tambahkan import
import { ref } from 'vue';

interface Props {
  modelValue: boolean
  isEdit: boolean
  item: any
  itemsDialog: any[]
  examiners: any[]
  suppliers: any[]
  options: readonly string[]
  statuses: readonly string[]
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue', 'save', 'close'])

const formRef = ref()

defineExpose({
  validate: () => formRef.value?.validate(),
  resetValidation: () => formRef.value?.resetValidation()
})
</script>

<template>
  <VDialog :model-value="props.modelValue" @update:model-value="emit('update:modelValue', $event)" max-width="600px"
    persistent>
    <VCard :title="props.isEdit ? 'Edit Item' : 'Add Item'">
      <VCardText>
        <VForm ref="formRef" @submit.prevent="emit('save')" validate-on="submit">
          <VRow>
            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.nodoc" label="No Doc" :rules="[requiredValidator]" required />
            </VCol>

            <VCol cols="12" sm="6">
              <AppDateTimePicker v-model="props.item.iirdate" label="IIR Date" placeholder="YYYY-MM-DD" />
            </VCol>

            <VCol cols="12" sm="6">
              <AppAutocomplete v-model="props.item.partname" :items="props.itemsDialog" label="Part Name"
                :rules="[requiredValidator]" required />
            </VCol>

            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.itemnc" label="Nomor NC" readonly />
            </VCol>

            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.quantity" label="Quantity" type="number"
                :rules="[requiredValidator, integerValidator, v => betweenValidator(v, 1, 999999)]" required />
            </VCol>

            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.samplesize" label="Sample Size" type="number"
                :rules="[requiredValidator, integerValidator, v => betweenValidator(v, 1, 999999)]" required />
            </VCol>

            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.gilevel" label="GI Level" type="number"
                :rules="[requiredValidator, integerValidator, v => betweenValidator(v, 1, 999999)]" required />
            </VCol>

            <VCol cols="12" sm="6">
              <AppAutocomplete v-model="props.item.examiner_id" :items="props.examiners" :rules="[requiredValidator]"
                required label="Examiner" />
            </VCol>

            <VCol cols="12" sm="6">
              <AppTimePicker24 v-model="props.item.start" :rules="[requiredValidator]" required label="Start Time"
                placeholder="HH:MM" />
            </VCol>

            <VCol cols="12" sm="6">
              <AppTimePicker24 v-model="props.item.end" :rules="[requiredValidator]" required label="End Time"
                placeholder="HH:MM" />
            </VCol>

            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.duration" label="Duration" placeholder="HH:MM" readonly />
            </VCol>

            <VCol cols="12" sm="6">
              <AppAutocomplete v-model="props.item.supplier_code" :items="props.suppliers" :rules="[requiredValidator]"
                required label="Supplier" />
            </VCol>

            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.batch" label="Batch"
                :rules="[requiredValidator, integerValidator, v => betweenValidator(v, 1, 999999)]" required />
            </VCol>

            <VCol cols="12" sm="6">
              <AppAutocomplete v-model="props.item.status" :items="props.statuses" :rules="[requiredValidator]" required
                label="Status" />
            </VCol>

            <VCol cols="12" sm="12">
              <AppAutocomplete v-model="props.item.option" :items="props.options" :rules="[requiredValidator]" required
                label="Option" />
            </VCol>
          </VRow>
        </VForm>
      </VCardText>

      <VCardText>
        <div class="self-align-end d-flex gap-4 justify-end">
          <VBtn color="error" variant="outlined" @click="emit('close')">
            Cancel
          </VBtn>
          <VBtn color="success" variant="elevated" @click="emit('save')">
            Save
          </VBtn>
        </div>
      </VCardText>
    </VCard>
  </VDialog>
</template>
