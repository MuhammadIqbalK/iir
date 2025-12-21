<script setup lang="ts">
import { requiredValidator } from '@/@core/utils/validators'
import { ref } from 'vue'
import type { VForm } from 'vuetify/components/VForm'

interface Props {
  modelValue: boolean
  isEdit: boolean
  item: any
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
    <VCard :title="props.isEdit ? 'Edit Supplier' : 'Add Supplier'">
      <VCardText>
        <VForm ref="refForm" @submit.prevent="emit('save')" validate-on="submit">
          <VRow>
            <!-- Supplier Name -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.supplier_name" label="Supplier Name" :rules="[requiredValidator]" required />
            </VCol>
            
            <!-- Supplier Code -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.supplier_code" label="Supplier Code" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Contact Person -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.contact_person" label="Contact Person" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Email -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.email" label="Email" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Phone -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.phone" label="Phone" :rules="[requiredValidator]" required />
            </VCol>

            <!-- Address -->
            <VCol cols="12" sm="6">
              <AppTextField v-model="props.item.address" label="Address" :rules="[requiredValidator]" required />
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
