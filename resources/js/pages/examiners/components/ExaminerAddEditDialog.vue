<script setup lang="ts">
import { requiredValidator } from '@/@core/utils/validators'
import { ref } from 'vue'
import type { VForm } from 'vuetify/components/VForm'

interface Props {
  modelValue: boolean
  isEdit: boolean
  item: any
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
    <VCard :title="props.isEdit ? 'Edit Examiner' : 'Add Examiner'">
      <VCardText>
        <VForm ref="refForm" @submit.prevent="emit('save')" validate-on="submit">
          <VRow>
            <!-- Name -->
            <VCol cols="12">
              <AppTextField v-model="props.item.name" label="Name" :rules="[requiredValidator]" required />
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
