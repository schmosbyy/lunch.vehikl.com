<template>
  <div class="bg-white dark:bg-gray-800 rounded-xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
    <div class="flex items-center gap-4">
      <img :src="user?.avatar_url" :alt="user?.name" class="h-12 w-12 rounded-full ring-2 ring-white">
      <div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Hungry, {{ user?.name }}?</h2>
        <p class="text-gray-600 dark:text-gray-300">Let us know if you'll be joining us for lunch this Friday!</p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  user?: {
    name?: string;
    avatar_url?: string;
  };
}>();
</script>
