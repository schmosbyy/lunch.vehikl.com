<template>
  <div v-if="modelValue" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity z-[100]">
    <div class="fixed inset-0 z-[100] overflow-y-auto">
      <div class="flex min-h-full items-center justify-center p-4">
        <div 
          class="relative transform overflow-hidden rounded-2xl bg-white w-full text-left shadow-xl transition-all sm:my-8 border border-gray-100"
          :class="[maxWidth]"
          @click.stop
        >
          <div class="absolute right-0 top-0 pr-4 pt-4 z-10">
            <button type="button" 
                    @click="$emit('update:modelValue', false)"
                    class="rounded-full bg-gray  text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-[#e26700] focus:ring-offset-2 p-1 hover:bg-gray-100 transition-all duration-200">
              <span class="sr-only">Close</span>
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <div class="relative">
            <!-- Header -->
            <div v-if="$slots.header" class="bg-white border-b border-gray-100 px-6 py-4">
              <slot name="header"></slot>
            </div>

            <!-- Content -->
            <div class="p-6">
              <slot></slot>
            </div>

            <!-- Footer -->
            <div v-if="$slots.footer" class="bg-gray-50 px-6 py-4 border-t border-gray-100">
              <slot name="footer"></slot>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  modelValue: {
    type: Boolean,
    required: true
  },
  maxWidth: {
    type: String,
    default: 'sm:max-w-lg',
    validator: (value: string) => [
      'sm:max-w-sm',
      'sm:max-w-md',
      'sm:max-w-lg',
      'sm:max-w-xl',
      'sm:max-w-2xl',
      'sm:max-w-3xl',
      'sm:max-w-4xl',
      'sm:max-w-5xl',
      'sm:max-w-6xl',
      'sm:max-w-7xl',
    ].includes(value)
  }
})

defineEmits(['update:modelValue'])
</script> 