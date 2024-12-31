<template>
  <img 
    :src="src" 
    :alt="alt"
    :class="[
      'rounded-full ring-2 ring-white',
      sizeClasses[size],
      className
    ]"
  >
</template>

<script setup lang="ts">
const props = defineProps({
  src: {
    type: String,
    required: true
  },
  alt: {
    type: String,
    required: true
  },
  size: {
    type: String,
    default: 'md',
    validator: (value: string) => ['sm', 'md', 'lg'].includes(value)
  },
  className: {
    type: String,
    default: ''
  }
})

const sizeClasses = {
  sm: 'h-8 w-8',
  md: 'h-10 w-10',
  lg: 'h-12 w-12'
}
</script> 