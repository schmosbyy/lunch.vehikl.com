<template>
  <Modal
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
    max-width="sm:max-w-4xl"
  >
    <template #header>
      <ModalHeader
        icon="home"
        title="Customize RSVP Food Order"
        subtitle="Let us know your lunch preferences!"
      />
    </template>

    <div class="w-full overflow-hidden rounded-xl border border-gray-100 shadow-sm" style="height: 80vh;">
      <iframe
        ref="formIframe"
        :src="`https://docs.google.com/forms/d/e/1FAIpQLSdZQCkmwCXexF_F-7r54F05HU8VcHea7OLppKlThWt-KdMTmA/viewform?embedded=true&usp=pp_url&entry.1234=${userEmail || ''}`"
        class="w-full h-full"
        style="width: 100%; min-width: 100%;"
        @load="handleIframeLoad"
        frameborder="0"
        marginheight="0"
        marginwidth="0">
        Loading...
      </iframe>
    </div>

    <div class="mt-4 flex justify-end">
      <Button
        type="submit"
        variant="primary"
        class="w-full sm:w-auto"
        icon="home"
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
let formLoaded = false

function handleIframeLoad() {
  if (!formLoaded) {
    formLoaded = true
    return
  }

  // Listen for messages from the Google Form
  window.addEventListener('message', (event) => {
    if (event.origin === 'https://docs.google.com') { // Ensure the message is from Google Forms
      if (event.data === 'success') { // Check for success message
        emit('update:modelValue', false)

        // Send the response to the Laravel endpoint
        router.post('/rsvp/out-of-town', {}, {
          preserveScroll: true,
          preserveState: true,
          onSuccess: () => {
            router.reload()
          }
        })
      }
    }
  });
}
</script>
