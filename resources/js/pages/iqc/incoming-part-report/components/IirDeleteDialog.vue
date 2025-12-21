<script setup lang="ts">
interface Props {
  modelValue: boolean
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue', 'confirm', 'close'])
</script>

<template>
  <VDialog :model-value="props.modelValue" @update:model-value="emit('update:modelValue', $event)" max-width="500px">
    <VCard title="Are you sure you want to delete this item?">
      <VCardText>
        <div class="d-flex justify-center gap-4">
          <VBtn color="error" variant="outlined" @click="emit('close')">
            Cancel
          </VBtn>
          <VBtn color="success" variant="elevated" @click="emit('confirm')">
            OK
          </VBtn>
        </div>
      </VCardText>
    </VCard>
  </VDialog>
</template>
