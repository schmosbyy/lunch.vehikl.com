<template>
  <div class="relative notification-bell">
    <button
      @click.stop="isOpen = !isOpen"
      class="relative p-2 text-gray-600 dark:text-gray-300 hover:text-[#e26700] dark:hover:text-[#e26700] transition-colors duration-200"
      :class="{ 'text-[#e26700]': hasUnreadNotifications }"
    >
      <Icon name="bell" size="lg" />
      <span
        v-if="hasUnreadNotifications"
        class="absolute top-1 right-1 w-2.5 h-2.5 bg-red-500 rounded-full"
      ></span>
    </button>

    <div
      v-if="isOpen"
      class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg py-1 z-20 border border-gray-100 dark:border-gray-700"
      @click.stop
    >
      <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
        <h3 class="text-sm font-medium text-gray-900 dark:text-white">
          Game Challenges
        </h3>
      </div>

      <div v-if="localChallenges.length === 0" class="px-4 py-3">
        <p class="text-sm text-gray-500 dark:text-gray-400">
          No game challenges at the moment
        </p>
      </div>

      <div v-else class="max-h-96 overflow-y-auto">
        <div
          v-for="challenge in localChallenges"
          :key="challenge.id"
          class="px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700"
        >
          <div class="flex items-start space-x-3">
            <img
              :src="challenge.challenger.avatar_url"
              :alt="challenge.challenger.name"
              class="w-8 h-8 rounded-full"
            />
            <div class="flex-1">
              <p class="text-sm text-gray-900 dark:text-white">
                <span class="font-medium">{{ challenge.challenger.name }}</span>
                challenged you to a game of
                <span class="font-medium">{{ challenge.game_type }}</span>
              </p>
              <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ challenge.created_at }}
              </p>
            </div>
          </div>
          <div class="mt-2 flex space-x-2">
            <Button
              variant="primary"
              size="sm"
              @click="acceptChallenge(challenge.id)"
              class="flex-1"
            >
              Accept
            </Button>
            <Button
              variant="secondary"
              size="sm"
              @click="declineChallenge(challenge.id)"
              class="flex-1"
            >
              Decline
            </Button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, onUnmounted, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import Icon from './Icon.vue'
import Button from './Button.vue'

interface Challenger {
  name: string
  avatar_url: string
}

interface Challenge {
  id: number
  challenger: Challenger
  game_type: string
  created_at: string
  read: boolean
}

const props = defineProps<{
  challenges: Challenge[]
}>()

const localChallenges = ref<Challenge[]>([])

// Initialize local challenges from props
watch(() => props.challenges, (newChallenges) => {
  localChallenges.value = [...newChallenges]
}, { immediate: true })

const isOpen = ref(false)

const hasUnreadNotifications = computed(() => {
  return localChallenges.value.some(challenge => !challenge.read)
})

watch(isOpen, (newValue) => {
  if (newValue && hasUnreadNotifications.value) {
    // Mark all unread notifications as read
    localChallenges.value.forEach(challenge => {
      if (!challenge.read) {
        router.post(`/game-challenge/${challenge.id}/read`, {
          _method: 'PUT'
        }, {
          preserveScroll: true,
          preserveState: true
        })
      }
    })
  }
})

const acceptChallenge = (challengeId: number) => {
  router.post(`/game-challenge/${challengeId}`, {
    status: 'accepted',
    _method: 'PUT'
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      // Remove the challenge from local list
      localChallenges.value = localChallenges.value.filter(c => c.id !== challengeId)
      
      // Close dropdown if no more challenges
      if (localChallenges.value.length === 0) {
        isOpen.value = false
      }
    }
  })
}

const declineChallenge = (challengeId: number) => {
  router.post(`/game-challenge/${challengeId}`, {
    status: 'declined',
    _method: 'PUT'
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      // Remove the challenge from local list
      localChallenges.value = localChallenges.value.filter(c => c.id !== challengeId)
      
      // Close dropdown if no more challenges
      if (localChallenges.value.length === 0) {
        isOpen.value = false
      }
    }
  })
}

// Close dropdown when clicking outside
onMounted(() => {
  const handleClickOutside = (event: MouseEvent) => {
    const target = event.target as HTMLElement
    if (!target.closest('.notification-bell')) {
      isOpen.value = false
    }
  }

  document.addEventListener('click', handleClickOutside)
  
  onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside)
  })
})
</script>
