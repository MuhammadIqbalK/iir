<script setup lang="ts">
import { requiredValidator } from '@/@core/utils/validators'
import { ref } from 'vue'
import type { VForm } from 'vuetify/components/VForm'

interface Props {
  modelValue: boolean
  isEdit: boolean
  item: any
  qualities: readonly string[]
  statuses: readonly string[]
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue', 'save', 'close'])

const refForm = ref<VForm>()

const validate = async () => {
  return await refForm.value?.validate()
}

const resetValidation = () => {
  refForm.value?.resetValidation()
}

defineExpose({
  validate,
  resetValidation
})
</script>

<template>
  <VDialog :model-value="props.modelValue" @update:model-value="emit('update:modelValue', $event)" max-width="600px" persistent>
    <VCard :title="props.isEdit ? 'Edit Material' : 'Add Material'">
      <VCardText>
        <VForm ref="refForm" @submit.prevent="emit('save')" validate-on="submit">
          <VRow>
            <!-- Item12NC -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.item12nc" label="Item12NC" :rules="[requiredValidator]" required />
            </VCol>
            
            <!-- Part Name -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.partname" label="Part Name" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Type -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.type" label="Type" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Quality -->
            <VCol cols="12" sm="6">
              <AppSelect v-model="props.item.quality" :items="props.qualities" label="Quality" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Quantity -->
            <VCol cols="12" sm="6">
              <AppTextField v-model.number="props.item.quantity" label="Quantity" type="number" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Unit -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.unit" label="Unit" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Status -->
            <VCol cols="12" sm="6">
              <AppSelect v-model="props.item.status" :items="props.statuses" label="Status" :rules="[requiredValidator]" required />
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
