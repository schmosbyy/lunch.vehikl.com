<template>
  <div class="flex items-center gap-4">
    <div class="flex-shrink-0 bg-[#fff8f3] rounded-full p-2">
      <Icon :name="icon" size="lg" class="text-[#e26700]" />
    </div>
    <div>
      <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
      <p v-if="subtitle" class="text-gray-500 text-sm mt-1">{{ subtitle }}</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import Icon from './Icon.vue'

defineProps({
  icon: {
    type: String,
    required: true
  },
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  }
})
</script> 