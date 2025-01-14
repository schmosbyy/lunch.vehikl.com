<template>
  <svg
    :class="[
      sizeClasses[size],
      className
    ]"
    fill="none"
    viewBox="0 0 24 24"
    stroke-width="1.5"
    stroke="currentColor"
    aria-hidden="true"
  >
    <path
      v-if="name === 'close'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M6 18L18 6M6 6l12 12"
    />
    <path
      v-else-if="name === 'check'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M5 13l4 4L19 7"
    />
    <path
      v-else-if="name === 'plus'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M12 4v16m8-8H4"
    />
    <path
      v-else-if="name === 'home'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
    />
    <path
      v-else-if="name === 'calendar'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
    />
    <path
      v-else-if="name === 'mail'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
    />
    <path
      v-else-if="name === 'arrow-left'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M10 19l-7-7m0 0l7-7m-7 7h18"
    />
    <path
      v-else-if="name === 'puzzle'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z"
    />
    <path
      v-else-if="name === 'arrows-right-left'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
    />
    <path
      v-else-if="name === 'warning'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
    />
    <path
      v-else-if="name === 'info'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"
    />
    <path
      v-else-if="name === 'refresh'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
    />
    <path
      v-else-if="name === 'sun'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"
    />
    <path
      v-else-if="name === 'moon'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"
    />
    <path
      v-else-if="name === 'chevron-down'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M19 9l-7 7-7-7"
    />
    <path
      v-else-if="name === 'logout'"
      stroke-linecap="round"
      stroke-linejoin="round"
      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
    />
      <path
          v-else-if="name === 'food'"
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M4 6h16M6 6v12h12V6M8 18h8a2 2 0 002-2v-6a2 2 0 00-2-2H8a2 2 0 00-2 2v6a2 2 0 002 2z"
      />
  </svg>
</template>

<script setup lang="ts">
const props = defineProps({
  name: {
    type: String,
    required: true,
    validator: (value: string) => [
      'close',
      'check',
      'plus',
      'home',
      'calendar',
      'mail',
      'arrow-left',
      'puzzle',
      'arrows-right-left',
      'warning',
      'info',
      'refresh',
      'sun',
      'moon',
      'chevron-down',
      'logout',
        'food'
    ].includes(value)
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
  sm: 'w-4 h-4',
  md: 'w-5 h-5',
  lg: 'w-6 h-6'
}
</script>
