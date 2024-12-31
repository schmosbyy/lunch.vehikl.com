<template>
  <Link
    :href="href"
    :class="[
      'inline-flex items-center transition-all duration-200',
      variantClasses[variant],
      sizeClasses[size],
      disabled ? 'opacity-50 cursor-not-allowed' : '',
      className
    ]"
    v-bind="$attrs"
  >
    <slot name="icon-left"></slot>
    <slot></slot>
    <slot name="icon-right"></slot>
  </Link>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  href: {
    type: String,
    required: true
  },
  variant: {
    type: String,
    default: 'primary',
    validator: (value: string) => ['primary', 'secondary', 'nav'].includes(value)
  },
  size: {
    type: String,
    default: 'md',
    validator: (value: string) => ['sm', 'md', 'lg'].includes(value)
  },
  disabled: {
    type: Boolean,
    default: false
  },
  className: {
    type: String,
    default: ''
  }
})

const variantClasses = {
  primary: 'text-[#e26700] hover:text-[#d25600] active:text-[#c24500] focus:outline-none focus:ring-2 focus:ring-[#e26700] focus:ring-offset-2',
  secondary: 'text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white',
  nav: 'px-1 pt-1 border-b-2 text-sm font-medium'
}

const sizeClasses = {
  sm: 'text-sm gap-1.5',
  md: 'text-base gap-2',
  lg: 'text-lg gap-2.5'
}
</script> 