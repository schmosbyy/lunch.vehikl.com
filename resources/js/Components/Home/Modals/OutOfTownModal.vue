<template>
  <Modal
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
    max-width="sm:max-w-4xl"
  >
    <template #header>
      <ModalHeader
        icon="home"
        title="Out of Town Lunch Order"
        subtitle="Let us know your lunch preferences while you're away!"
      />
    </template>

    <div class="w-full overflow-hidden rounded-xl border border-gray-100 shadow-sm" style="height: 80vh;">
      <iframe
        ref="formIframe"
        :src="`https://docs.google.com/forms/d/e/1FAIpQLSdZQCkmwCXexF_F-7r54F05HU8VcHea7OLppKlThWt-KdMTmA/viewform?embedded=true&usp=pp_url&entry.1234=${userEmail || ''}`"
        class="w-full h-full"
        style="width: 100%; min-width: 100%;"
        @load="handleIframeLoad">
        Loading...
      </iframe>
    </div>

    <div class="mt-4 flex justify-between items-center">
      <div class="flex items-center gap-2">
        <Icon name="info" class="text-gray-400" />
        <p class="text-sm text-gray-600 font-medium">
          Please submit the Google Form first, then click "Submit Form"
        </p>
      </div>
      <Button
        type="submit"
        variant="primary"
        class="w-full sm:w-auto"
        icon="home"
        @click="handleFormSubmit"
      >
        Submit Form
      </Button>
    </div>
  </Modal>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Modal from "../../UI/Modal.vue";
import ModalHeader from "../../UI/ModalHeader.vue";
import Button from "../../UI/Button.vue";
import Icon from "../../UI/Icon.vue"; // Added import statement for Icon component
import { router } from '@inertiajs/vue3'

const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  },
  userEmail: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

const formIframe = ref(null)
const formLoaded = ref(false)

function handleIframeLoad() {
  formLoaded.value = true
}

function handleFormSubmit() {
  // Send the response to the Laravel endpoint
  router.post('/rsvp/out-of-town', {}, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      emit('update:modelValue', false) // Close the modal
      router.reload() // Refresh the page to show updated status
    }
  })
}
</script>
