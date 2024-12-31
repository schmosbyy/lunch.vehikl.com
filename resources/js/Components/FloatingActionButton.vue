<template>
  <div>
    <Button
      @click="showModal = true"
      variant="primary"
      class="fixed bottom-6 right-6 flex items-center gap-2 !bg-indigo-600 hover:!bg-indigo-500 !text-white rounded-full shadow-lg transition-all duration-300 group overflow-hidden z-50 hover:scale-105 transform !border-0"
      :class="{ 'px-4 py-3': isHovered, 'p-3': !isHovered }"
      @mouseenter="isHovered = true"
      @mouseleave="isHovered = false"
      icon="plus"
    >
      Vote
      <span
        class="whitespace-nowrap overflow-hidden transition-all duration-300"
        :class="{ 'max-w-0 opacity-0': !isHovered, 'max-w-xs opacity-100': isHovered }"
      >
        for next friday's Food
      </span>
    </Button>

    <!-- Food Vote Modal -->
    <Modal
      v-model="showModal"
      :max-width="'sm:max-w-4xl'"
    >
      <div class="relative">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-6 py-4 rounded-t-2xl">
          <div class="flex items-center gap-4">
            <div class="flex-shrink-0 bg-white/10 backdrop-blur-sm rounded-full p-2">
              <Icon name="plus" class="h-6 w-6 text-white" />
            </div>
            <div>
              <h3 class="text-lg font-semibold text-white">Vote for next friday's Food</h3>
              <p class="text-indigo-100 text-sm mt-1">Help us choose what to eat next Friday!</p>
            </div>
          </div>
        </div>

        <!-- Form Content -->
        <div class="px-6 py-4">
          <div class="w-full overflow-hidden rounded-xl border border-gray-100 shadow-sm" style="height: 80vh;">
            <iframe 
              ref="formIframe"
              src="https://docs.google.com/forms/d/e/1FAIpQLSdZQCkmwCXexF_F-7r54F05HU8VcHea7OLppKlThWt-KdMTmA/viewform?embedded=true"
              class="w-full h-full"
              style="width: 100%; min-width: 100%;"
              @load="handleIframeLoad"
              frameborder="0" 
              marginheight="0" 
              marginwidth="0"
            >
              Loading...
            </iframe>
          </div>
        </div>
      </div>
    </Modal>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Button from '@/Components/UI/Button.vue'
import Icon from '@/Components/UI/Icon.vue'
import Modal from '@/Components/UI/Modal.vue'

const isHovered = ref(false)
const showModal = ref(false)
const formIframe = ref<HTMLIFrameElement | null>(null)
let formLoaded = false

const closeModal = () => {
  showModal.value = false
  formLoaded = false
}

const handleIframeLoad = () => {
  if (!formLoaded) {
    formLoaded = true
    return
  }
}
</script>
