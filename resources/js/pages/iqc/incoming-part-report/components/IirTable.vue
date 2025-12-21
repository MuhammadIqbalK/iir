<script setup lang="ts">
interface Props {
  headers: any[];
  items: any[];
  totalItems: number;
  loading: boolean
}

const props = defineProps<Props>()

const itemsPerPage = defineModel('itemsPerPage', { default: 10 })
const page = defineModel('page', { default: 1 })

const emit = defineEmits(['update:options', 'addItem', 'editItem', 'deleteItem'])
</script>

<template>
  <VCol cols="12" md="12">
    <VCard title="Data Table">
      <VCardText>
        <div class="d-flex flex-wrap gap-4 mb-4">
          <VBtn color="success" @click="emit('addItem')">Add IIR</VBtn>
          <VBtn color="primary">Global Print</VBtn>
        </div>
        
        <VDataTableServer 
          :headers="props.headers" 
          :items="props.items" 
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items-length="props.totalItems"
          :loading="props.loading"
          @update:options="emit('update:options', $event)"
        >
          <template #item.iirdate="{ item }">
            <div class="w-200 align-center">
              {{ item.iirdate }}
            </div>
          </template>

          <template #item.itemnc="{ item }">
            <div class="align-center">
              {{ item.itemnc }}
            </div>
          </template>

          <!-- Actions -->
          <template #item.actions="{ item }">
            <div class="d-flex gap-1">
              <IconBtn @click="emit('editItem', item)">
                <VIcon icon="tabler-edit" />
              </IconBtn>
              <IconBtn @click="emit('deleteItem', item)">
                <VIcon icon="tabler-trash" />
              </IconBtn>
            </div>
          </template>

          <template #bottom>
            <VCardText class="pt-2">
              <div class="d-flex flex-wrap justify-center justify-sm-space-between gap-y-2 mt-2">
                <VSelect
                  v-model="itemsPerPage"
                  :items="[5, 10, 25, 50, 100]"
                  label="Rows per page:"
                  variant="underlined"
                  style="max-inline-size: 8rem; min-inline-size: 5rem;"
                />

                <VPagination
                  v-model="page"
                  :total-visible="$vuetify.display.smAndDown ? 3 : 5"
                  :length="Math.ceil(props.totalItems / itemsPerPage)"
                />
              </div>
            </VCardText>
          </template>
        </VDataTableServer>
      </VCardText>
    </VCard>
  </VCol>
</template>
