<template>
  <Modal 
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
  >
    <template #header>
      <ModalHeader
        icon="arrows-right-left"
        title="Request a Ride"
        subtitle="Let us know where to pick you up"
      />
    </template>

    <div class="space-y-6">
      <div>
        <label for="pickup-location" class="block text-sm font-medium text-gray-700 mb-2">Pickup Location</label>
        <input type="text"
               id="pickup-location"
               v-model="location"
               placeholder="Enter your pickup location"
               class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-[#e26700] focus:border-[#e26700]" />
      </div>
      <div>
        <label for="pickup-notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
        <textarea id="pickup-notes"
                  v-model="notes"
                  rows="3"
                  placeholder="Any additional details that might help (optional)"
                  class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-[#e26700] focus:border-[#e26700]"></textarea>
      </div>
      <Alert v-if="error" variant="error">
        {{ error }}
      </Alert>
    </div>

    <template #footer>
      <div class="flex justify-end gap-3">
        <Button 
          type="button"
          variant="secondary"
          @click="$emit('update:modelValue', false)"
          class="w-full sm:w-auto"
          icon="close"
        >
          Cancel
        </Button>
        <Button 
          type="submit"
          variant="primary"
          class="w-full sm:w-auto"
          icon="arrows-right-left"
          :disabled="!location"
          @click="submitRequest"
        >
          Request a Ride
        </Button>
      </div>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Modal from '@/Components/UI/Modal.vue'
import ModalHeader from '@/Components/UI/ModalHeader.vue'
import Button from '@/Components/UI/Button.vue'
import Alert from '@/Components/UI/Alert.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  }
})

const emit = defineEmits(['update:modelValue'])

const location = ref('')
const notes = ref('')
const error = ref('')

function submitRequest() {
  error.value = ''
  router.post('/ride-request', {
    location: location.value,
    notes: notes.value
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      emit('update:modelValue', false)
      location.value = ''
      notes.value = ''
    },
    onError: (errors) => {
      error.value = errors.rsvp || errors.request || errors.location || 'Failed to submit ride request'
    }
  })
}
</script> 