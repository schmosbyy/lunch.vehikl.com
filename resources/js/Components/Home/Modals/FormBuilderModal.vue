<template>
  <Modal
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
  >
    <template #header>
      <ModalHeader
        icon="arrows-right-left"
        title="Kelly's Korner"
        subtitle="To modify links for this weeks Food Orders"
      />
    </template>

    <div class="space-y-6">
      <div>
        <label for="inoffice-orders" class="block text-sm font-medium text-gray-700 mb-2">In Office Lunch Order</label>
        <input type="text"
               id="inoffice-orders"
               v-model="formUrls['inoffice-orders']"
               placeholder="In Office Lunch Order URL"
               class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-[#e26700] focus:border-[#e26700]" />
      </div>
        <div>
            <label for="outoftown-orders" class="block text-sm font-medium text-gray-700 mb-2">Out of Town Lunch Order</label>
            <input type="text"
                   id="outoftown-orders"
                   v-model="formUrls['outoftown-orders']"
                   placeholder="Out of Town Lunch Order URL"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-[#e26700] focus:border-[#e26700]" />
        </div>
        <div>
            <label for="nextweek-votes" class="block text-sm font-medium text-gray-700 mb-2">Next Weeks Votes</label>
            <input type="text"
                   id="nextweek-votes"
                   v-model="formUrls['nextweek-votes']"
                   placeholder="Next Weeks Votes URL"
                   class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:ring-2 focus:ring-[#e26700] focus:border-[#e26700]" />
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
          :disabled="!formUrls['inoffice-orders'] && !formUrls['outoftown-orders']"
          @click="submitRequest"
        >
          Update Links
        </Button>
      </div>
    </template>
  </Modal>
</template>

<script setup lang="ts">
import {reactive, ref} from 'vue'
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

const formUrls = reactive({
    'inoffice-orders':'',
    'outoftown-orders':'',
    'nextweek-votes':''
})
const notes = ref('')
const error = ref('')

function submitRequest() {
  error.value = ''
  router.post('/forms-update', {
      formUrls: formUrls,
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      emit('update:modelValue', false)
        formUrls["inoffice-orders"] = ''
        formUrls["outoftown-orders"] = ''
        formUrls["nextweek-votes"] = ''
    },
    onError: (errors) => {
      error.value = errors.request || errors.formUrls || 'Failed to Update Google Forms.'
    }
  })
}
</script>
