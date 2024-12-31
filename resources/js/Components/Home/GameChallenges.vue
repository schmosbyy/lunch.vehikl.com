<template>
  <div v-if="challenges" class="bg-gray-50 rounded-lg p-6 border border-gray-200">
    <h3 class="text-xl font-semibold text-gray-900 mb-4">Game Challenges</h3>
    
    <!-- Received Challenges -->
    <div v-if="challenges.received.length > 0" class="mb-6">
      <h4 class="text-lg font-medium text-gray-800 mb-3">Challenges Received</h4>
      <div class="space-y-3">
        <div v-for="challenge in challenges.received" 
             :key="challenge.id"
             class="flex items-center justify-between bg-white p-3 rounded-md border border-gray-200">
          <div class="flex items-center space-x-3">
            <Avatar 
              :src="challenge.challenger.avatar_url" 
              :alt="challenge.challenger.name" 
              size="sm"
            />
            <div>
              <p class="text-sm text-gray-900">
                <span class="font-medium">{{ challenge.challenger.name }}</span> challenged you to
                <span class="font-medium">{{ challenge.game_type }}</span>
              </p>
              <p class="text-xs text-gray-500">Status: {{ challenge.status }}</p>
            </div>
          </div>
          <div class="flex items-center space-x-2" v-if="challenge.status === 'pending'">
            <Button 
              variant="secondary" 
              size="sm"
              @click="respondToChallenge(challenge.id, 'accepted')"
              class="!text-green-700 !border-green-700 hover:!bg-green-50"
              icon="check"
            >
              Accept
            </Button>
            <Button 
              variant="secondary" 
              size="sm"
              @click="respondToChallenge(challenge.id, 'declined')"
              class="!text-red-700 !border-red-700 hover:!bg-red-50"
              icon="close"
            >
              Decline
            </Button>
          </div>
          <Link 
            v-else-if="challenge.status === 'accepted'"
            :href="challenge.game_url"
            target="_blank"
            variant="secondary"
            size="sm"
            class="!text-blue-700 !border-blue-700 hover:!bg-blue-50"
          >
            Play Now
          </Link>
        </div>
      </div>
    </div>

    <!-- Sent Challenges -->
    <div v-if="challenges.sent.length > 0">
      <h4 class="text-lg font-medium text-gray-800 mb-3">Challenges Sent</h4>
      <div class="space-y-3">
        <div v-for="challenge in challenges.sent" 
             :key="challenge.id"
             class="flex items-center justify-between bg-white p-3 rounded-md border border-gray-200">
          <div class="flex items-center space-x-3">
            <Avatar 
              :src="challenge.challenged.avatar_url" 
              :alt="challenge.challenged.name" 
              size="sm"
            />
            <div>
              <p class="text-sm text-gray-900">
                You challenged <span class="font-medium">{{ challenge.challenged.name }}</span> to
                <span class="font-medium">{{ challenge.game_type }}</span>
              </p>
              <p class="text-xs text-gray-500">Status: {{ challenge.status }}</p>
            </div>
          </div>
          <Link 
            v-if="challenge.status === 'accepted'"
            :href="challenge.game_url"
            target="_blank"
            variant="secondary"
            size="sm"
            class="!text-blue-700 !border-blue-700 hover:!bg-blue-50"
          >
            Play Now
          </Link>
        </div>
      </div>
    </div>

    <p v-if="challenges.received.length === 0 && challenges.sent.length === 0" 
       class="text-gray-500 italic">
      No active game challenges.
    </p>
  </div>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import Avatar from '@/Components/UI/Avatar.vue'
import Button from '@/Components/UI/Button.vue'
import Link from '@/Components/UI/Link.vue'

interface Challenger {
  avatar_url: string;
  name: string;
}

interface Challenge {
  id: number;
  challenger: Challenger;
  challenged: Challenger;
  game_type: string;
  status: string;
  game_url: string;
}

interface Challenges {
  received: Challenge[];
  sent: Challenge[];
}

defineProps<{
  challenges: Challenges;
}>();

const respondToChallenge = (challengeId: number, status: string) => {
  router.post(`/game-challenge/${challengeId}`, {
    _method: 'PUT',
    status
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      console.log('Challenge response sent successfully');
    },
    onError: (errors) => {
      console.error('Failed to respond to challenge:', errors);
    }
  });
};
</script>
