<template>
  <form @submit.prevent="$emit('submit')" class="mt-6 space-y-6">
    <!-- Out of town status indicator -->
    <div v-if="hasSubmittedOutOfTownForm" class="bg-[#fff8f3] border border-[#e26700] rounded-xl p-4">
      <div class="flex">
        <div class="flex-shrink-0">
          <Icon name="home" size="lg" class="text-[#e26700]" />
        </div>
        <div class="ml-3">
          <p class="text-base font-medium text-[#e26700]">
            You've marked yourself as out of town for this Friday's lunch.
          </p>
        </div>
      </div>
    </div>

    <div class="flex items-center gap-4">
      <Button 
        type="submit"
        :disabled="isRsvpDisabled"
        :variant="isRsvpDisabled ? 'disabled' : 'primary'"
        size="lg"
        icon="check"
      >
        {{ rsvpButtonText }}
      </Button>
      
      <Button 
        type="button"
        :disabled="isOutOfTownDisabled"
        :variant="isOutOfTownDisabled ? 'disabled' : 'primary'"
        size="lg"
        @click="$emit('show-out-of-town-modal')"
        icon="home"
      >
        Out of Town
      </Button>
      
      <!-- Show reset button only when out of town or attending -->
      <Button 
        v-if="isRsvpDisabled"
        type="button"
        variant="danger"
        size="lg"
        @click="$emit('reset-out-of-town-form')"
        icon="refresh"
      >
        Reset Status
      </Button>
    </div>
    <p v-if="!hasSubmittedOutOfTownForm" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
      A confirmation email will be sent to {{ user?.email }}
    </p>
  </form>
</template>

<script setup lang="ts">
import Button from '@/Components/UI/Button.vue'
import Icon from '@/Components/UI/Icon.vue'

defineProps({
  user: {
    type: Object,
    required: true
  },
  hasSubmittedOutOfTownForm: {
    type: Boolean,
    required: true
  },
  isRsvpDisabled: {
    type: Boolean,
    required: true
  },
  isOutOfTownDisabled: {
    type: Boolean,
    required: true
  },
  rsvpButtonText: {
    type: String,
    required: true
  }
})

defineEmits([
  'submit',
  'show-out-of-town-modal',
  'reset-out-of-town-form'
])
</script> 