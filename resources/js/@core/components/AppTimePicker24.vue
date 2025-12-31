<script setup lang="ts">
import { ref, watch } from 'vue'

interface Props {
    modelValue?: string
    label?: string
    placeholder?: string
    rules?: any[]
    required?: boolean
    readonly?: boolean
    disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: '',
    label: 'Time',
    placeholder: 'HH:MM',
    rules: () => [],
    required: false,
    readonly: false,
    disabled: false
})

const emit = defineEmits(['update:modelValue'])

const timeValue = ref(props.modelValue)
const menu = ref(false)
const hours = ref('00')
const minutes = ref('00')
const isTyping = ref(false)

// Parse initial value
const parseTime = (time: string) => {
    if (time && time.includes(':')) {
        const [h, m] = time.split(':')
        hours.value = h.padStart(2, '0')
        minutes.value = m.padStart(2, '0')
    }
}

// Initialize
parseTime(props.modelValue)

// Watch for external changes
watch(() => props.modelValue, (newVal) => {
    if (!isTyping.value) {
        timeValue.value = newVal
        parseTime(newVal)
    }
})

// Watch hours and minutes changes
watch([hours, minutes], ([newHours, newMinutes]) => {
    // Only update if not actively typing
    if (!isTyping.value) {
        const h = newHours.padStart(2, '0')
        const m = newMinutes.padStart(2, '0')
        const formattedTime = `${h}:${m}`
        timeValue.value = formattedTime
        emit('update:modelValue', formattedTime)
    }
})

const onDirectInput = (event: Event) => {
    const input = (event.target as HTMLInputElement).value

    // Allow typing with colon separator
    if (/^[\d:]*$/.test(input)) {
        timeValue.value = input
    }
}

const validateDirectInput = () => {
    isTyping.value = false
    const input = timeValue.value.trim()

    // Parse input
    if (input.includes(':')) {
        const [h, m] = input.split(':')
        let hourNum = parseInt(h) || 0
        let minNum = parseInt(m) || 0

        // Validate ranges
        if (hourNum > 23) hourNum = 23
        if (hourNum < 0) hourNum = 0
        if (minNum > 59) minNum = 59
        if (minNum < 0) minNum = 0

        hours.value = hourNum.toString().padStart(2, '0')
        minutes.value = minNum.toString().padStart(2, '0')
        timeValue.value = `${hours.value}:${minutes.value}`
    } else if (input.length > 0) {
        // If only numbers without colon, try to parse
        const numbers = input.replace(/\D/g, '')
        if (numbers.length <= 2) {
            // Treat as hours
            let h = parseInt(numbers) || 0
            if (h > 23) h = 23
            hours.value = h.toString().padStart(2, '0')
            timeValue.value = `${hours.value}:${minutes.value}`
        } else if (numbers.length <= 4) {
            // Treat as HHMM
            let h = parseInt(numbers.substring(0, 2)) || 0
            let m = parseInt(numbers.substring(2)) || 0
            if (h > 23) h = 23
            if (m > 59) m = 59
            hours.value = h.toString().padStart(2, '0')
            minutes.value = m.toString().padStart(2, '0')
            timeValue.value = `${hours.value}:${minutes.value}`
        }
    } else {
        // Empty input, set to 00:00
        hours.value = '00'
        minutes.value = '00'
        timeValue.value = '00:00'
    }

    emit('update:modelValue', timeValue.value)
}

const onDirectFocus = () => {
    isTyping.value = true
}

const onHoursInput = (event: Event) => {
    const input = (event.target as HTMLInputElement).value
    isTyping.value = true
    // Allow any numeric input while typing
    if (/^\d{0,2}$/.test(input)) {
        hours.value = input
    }
}

const onMinutesInput = (event: Event) => {
    const input = (event.target as HTMLInputElement).value
    isTyping.value = true
    // Allow any numeric input while typing
    if (/^\d{0,2}$/.test(input)) {
        minutes.value = input
    }
}

const incrementHours = () => {
    let h = parseInt(hours.value) || 0
    h = (h + 1) % 24
    hours.value = h.toString().padStart(2, '0')
}

const decrementHours = () => {
    let h = parseInt(hours.value) || 0
    h = h - 1
    if (h < 0) h = 23
    hours.value = h.toString().padStart(2, '0')
}

const incrementMinutes = () => {
    let m = parseInt(minutes.value) || 0
    m = (m + 1) % 60
    minutes.value = m.toString().padStart(2, '0')
}

const decrementMinutes = () => {
    let m = parseInt(minutes.value) || 0
    m = m - 1
    if (m < 0) m = 59
    minutes.value = m.toString().padStart(2, '0')
}

const validateHours = () => {
    isTyping.value = false
    if (hours.value === '') {
        hours.value = '00'
    } else {
        let h = parseInt(hours.value)
        if (isNaN(h) || h < 0) h = 0
        if (h > 23) h = 23
        hours.value = h.toString().padStart(2, '0')
    }
    // Update after validation
    const formattedTime = `${hours.value}:${minutes.value}`
    timeValue.value = formattedTime
    emit('update:modelValue', formattedTime)
}

const validateMinutes = () => {
    isTyping.value = false
    if (minutes.value === '') {
        minutes.value = '00'
    } else {
        let m = parseInt(minutes.value)
        if (isNaN(m) || m < 0) m = 0
        if (m > 59) m = 59
        minutes.value = m.toString().padStart(2, '0')
    }
    // Update after validation
    const formattedTime = `${hours.value}:${minutes.value}`
    timeValue.value = formattedTime
    emit('update:modelValue', formattedTime)
}

const confirmTime = () => {
    menu.value = false
}
</script>

<template>
    <VMenu v-model="menu" :close-on-content-click="false" location="bottom">
        <template #activator="{ props: menuProps }">
            <AppTextField :model-value="timeValue" :label="label" :placeholder="placeholder" :rules="rules"
                :required="required" :readonly="readonly || disabled" :disabled="disabled" v-bind="menuProps"
                append-inner-icon="tabler-clock" @input="onDirectInput" @blur="validateDirectInput"
                @focus="onDirectFocus" />
        </template>

        <VCard min-width="300">
            <VCardText>
                <div class="d-flex align-center justify-center ga-4">
                    <!-- Hours -->
                    <div class="d-flex flex-column align-center">
                        <VBtn icon size="small" variant="text" @click="incrementHours">
                            <VIcon icon="tabler-chevron-up" />
                        </VBtn>

                        <VTextField v-model="hours" variant="outlined" density="compact" hide-details
                            class="text-center time-input" style="width: 70px" maxlength="2" @input="onHoursInput"
                            @blur="validateHours" @focus="(e) => e.target.select()" />

                        <VBtn icon size="small" variant="text" @click="decrementHours">
                            <VIcon icon="tabler-chevron-down" />
                        </VBtn>
                    </div>

                    <div class="text-h4 mt-8">:</div>

                    <!-- Minutes -->
                    <div class="d-flex flex-column align-center">
                        <VBtn icon size="small" variant="text" @click="incrementMinutes">
                            <VIcon icon="tabler-chevron-up" />
                        </VBtn>

                        <VTextField v-model="minutes" variant="outlined" density="compact" hide-details
                            class="text-center time-input" style="width: 70px" maxlength="2" @input="onMinutesInput"
                            @blur="validateMinutes" @focus="(e) => e.target.select()" />

                        <VBtn icon size="small" variant="text" @click="decrementMinutes">
                            <VIcon icon="tabler-chevron-down" />
                        </VBtn>
                    </div>
                </div>
            </VCardText>

            <VCardActions>
                <VSpacer />
                <VBtn color="primary" variant="text" @click="confirmTime">
                    OK
                </VBtn>
            </VCardActions>
        </VCard>
    </VMenu>
</template>

<style scoped>
.time-input :deep(input) {
    text-align: center;
    font-size: 1.5rem;
    font-weight: 500;
}
</style>
