<template>
  <div :class="[
    'rounded-xl p-4 border',
    variantClasses[variant]
  ]">
    <div class="flex gap-3">
      <Icon 
        :name="icon" 
        :class="iconClasses[variant]"
      />
      <p :class="[
        'text-sm font-medium',
        textClasses[variant]
      ]">
        <slot></slot>
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Icon from './Icon.vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'error',
    validator: (value: string) => ['error', 'success', 'warning', 'info'].includes(value)
  }
})

const variantClasses = {
  error: 'bg-red-50 border-red-100',
  success: 'bg-green-50 border-green-100',
  warning: 'bg-yellow-50 border-yellow-100',
  info: 'bg-blue-50 border-blue-100'
}

const iconClasses = {
  error: 'text-red-400',
  success: 'text-green-400',
  warning: 'text-yellow-400',
  info: 'text-blue-400'
}

const textClasses = {
  error: 'text-red-800',
  success: 'text-green-800',
  warning: 'text-yellow-800',
  info: 'text-blue-800'
}

const icon = computed(() => {
  switch (props.variant) {
    case 'error':
      return 'close'
    case 'success':
      return 'check'
    case 'warning':
      return 'warning'
    case 'info':
      return 'info'
    default:
      return 'info'
  }
})
</script> 