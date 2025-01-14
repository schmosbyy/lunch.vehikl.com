<template>
  <component
    :is="tag"
    :type="type"
    :disabled="disabled"
    :class="[
      'inline-flex items-center px-4 py-2 rounded-lg font-medium transition-all duration-200',
      sizeClasses[size],
      variantClasses[variant],
      disabled ? 'opacity-50 cursor-not-allowed' : '',
      className
    ]"
    v-bind="$attrs"
    @click="$emit('click', $event)"
  >
    <Icon v-if="icon" :name="icon" class="mr-2" :size="size" />
    <slot></slot>
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Icon from './Icon.vue'

const props = defineProps({
  variant: {
    type: String,
    default: 'primary',
    validator: (value: string) => ['primary', 'secondary', 'danger', 'disabled'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value: string) => ['sm', 'md', 'lg'].includes(value)
  },
  type: {
    type: String,
    default: 'button'
  },
  disabled: {
    type: Boolean,
    default: false
  },
  className: {
    type: String,
    default: ''
  },
  tag: {
    type: String,
    default: 'button'
  },
  icon: {
    type: String,
    default: ''
  }
})

const variantClasses = {
  primary: 'bg-[#e26700] hover:bg-[#d25600] active:bg-[#c24500] text-white focus:outline-none focus:ring-2 focus:ring-[#e26700] focus:ring-offset-2',
  secondary: 'text-[#e26700] bg-white dark:bg-gray-800 border border-[#e26700] hover:bg-[#fff8f3] dark:hover:bg-[#fff8f3]/10',
  danger: 'bg-gray-600 hover:bg-red-500 active:bg-red-700 border border-red-900 text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2',
  disabled: 'bg-gray-300 dark:bg-gray-700 text-gray-500 dark:text-gray-400 cursor-not-allowed border border-gray-400 dark:border-gray-600'
}

const sizeClasses = {
  sm: 'text-sm px-3 py-1.5',
  md: 'text-base px-4 py-2',
  lg: 'text-base px-6 py-3'
}

defineEmits(['click'])
</script> 