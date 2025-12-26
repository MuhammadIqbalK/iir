<script setup lang="ts">
import { ref } from 'vue';
import type { VForm } from 'vuetify/components/VForm';

interface Props {
  item12ncs: any[];
  partnames: any[];
  qualities: readonly string[]
}

const props = defineProps<Props>()

const item12ncSelect = defineModel('item12ncSelect')
const partnameSelect = defineModel('partnameSelect')
const qualitySelect = defineModel('qualitySelect')

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
              <AppAutocomplete id="filter-partname" v-model="partnameSelect" :items="props.partnames" placeholder="Select Part Name" label="Part Name" name="partname" clearable :disabled="!!item12ncSelect" :hint="item12ncSelect ? 'Clear Partname to enable' : ''" persistent-hint/>
            </VCol>

            <VCol cols="4">
              <AppAutocomplete id="filter-item12nc" v-model="item12ncSelect" :items="props.item12ncs" placeholder="Select Item12NC" label="Item12NC" name="item12nc" clearable :disabled="!!partnameSelect" :hint="partnameSelect ? 'Clear Item12NC to enable' : ''" persistent-hint/>
            </VCol>

            <VCol cols="4">
              <AppAutocomplete id="filter-quality" v-model="qualitySelect" :items="props.qualities"
                placeholder="Select Quality" label="Quality" name="quality" clearable />
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
