<template>
  <button 
    :type="type"
    :class="[
      'p-2 rounded-lg transition-colors duration-200',
      variant === 'default' && 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200',
      variant === 'primary' && 'text-[#e26700] hover:text-[#d25600]',
      variant === 'danger' && 'text-red-600 hover:text-red-700 dark:text-red-500 dark:hover:text-red-400',
      className
    ]"
    v-bind="$attrs"
  >
    <Icon :name="icon" class="w-5 h-5" />
    <span v-if="$slots.default" class="sr-only">
      <slot />
    </span>
  </button>
</template>

<script setup lang="ts">
import Icon from './Icon.vue'

defineProps({
  icon: {
    type: String,
    required: true
  },
  variant: {
    type: String,
    default: 'default',
    validator: (value: string) => ['default', 'primary', 'danger'].includes(value)
  },
  type: {
    type: String,
    default: 'button'
  },
  className: {
    type: String,
    default: ''
  }
})
</script> 