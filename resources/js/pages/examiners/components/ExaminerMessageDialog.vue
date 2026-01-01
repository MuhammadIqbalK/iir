<script setup lang="ts">
interface Props {
  modelValue: boolean
  title: string
  text: string
  icon: string
  color: string
  errors: string[]
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue'])
</script>

<template>
  <VDialog v-model="props.modelValue" @update:model-value="emit('update:modelValue', $event)" width="500">
    <DialogCloseBtn @click="emit('update:modelValue', false)" />
    <VCard>
      <VCardText class="d-flex flex-column align-center gap-4 pt-8">
        <VIcon :icon="props.icon" :color="props.color" size="48" />
        <h3 class="text-h3" :class="`text-${props.color}`">{{ props.title }}</h3>
        <p class="text-body-1 text-center mb-0">{{ props.text }}</p>
        <div v-if="props.errors.length" class="w-100 mt-2">
          <VAlert type="error" variant="tonal" density="compact">
            <ul class="mb-0 ps-4">
              <li v-for="(err, index) in props.errors" :key="index" class="text-caption">
                {{ err }}
              </li>
            </ul>
          </VAlert>
        </div>
      </VCardText>
      <VCardText class="d-flex justify-center pb-8">
        <VBtn :color="props.color" @click="emit('update:modelValue', false)">
          OK
        </VBtn>
      </VCardText>
    </VCard>
  </VDialog>
</template>
