<template>
  <div v-if="rsvpList" class="bg-white dark:bg-gray-800 rounded-xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Who's Coming?</h3>
    <div class="space-y-4">
      <div v-for="rsvp in attendingUsers" :key="rsvp.id" class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <Avatar 
            :src="rsvp.user.avatar_url" 
            :alt="rsvp.user.name" 
            size="sm" 
          />
          <span class="text-gray-900 dark:text-white">{{ rsvp.user.name }}</span>
        </div>
        <div v-if="rsvp.user.id !== currentUserId" class="relative">
          <Button 
            v-if="hasUserRsvped"
            @click="toggleDropdown($event, rsvp.user.id)"
            variant="secondary"
            size="sm"
            icon="plus"
          >
            Challenge to
          </Button>

          <!-- Desktop Dropdown -->
          <div v-if="!isMobile && openDropdownId === rsvp.user.id"
               class="absolute right-0 bottom-full mb-2 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-1 z-[100] border border-gray-200 dark:border-gray-700 w-56">
            <Button 
              v-for="game in games" 
              :key="game.id"
              @click="challengeToGame(rsvp.user, game)"
              variant="secondary"
              class="flex items-center w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#fff8f3] dark:hover:bg-[#fff8f3]/10 !border-0"
            >
              <img :src="game.icon" :alt="game.name" class="w-4 h-4 mr-2">
              <span class="truncate">{{ game.name }}</span>
            </Button>
          </div>
        </div>
      </div>
      <p v-if="attendingUsers.length === 0" class="text-gray-500 dark:text-gray-400 italic">
        No RSVPs yet. Be the first to sign up!
      </p>
    </div>

    <!-- Mobile Modal -->
    <Modal
      v-if="isMobile && openDropdownId !== null"
      @close="closeDropdown"
      :show="true"
      max-width="sm"
      class="sm:!hidden"
    >
      <ModalHeader>
        <template #title>Select a Game</template>
        <template #close>
          <Button
            @click="closeDropdown"
            variant="secondary"
            icon="close"
            class="!p-1 !border-0"
          >
            <span class="sr-only">Close</span>
          </Button>
        </template>
      </ModalHeader>

      <div class="p-2 max-h-[60vh] overflow-y-auto">
        <Button 
          v-for="game in games" 
          :key="game.id"
          @click="challengeToGame(selectedUser, game)"
          variant="secondary"
          class="flex items-center w-full px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-[#fff8f3] dark:hover:bg-[#fff8f3]/10 !border-0"
        >
          <img :src="game.icon" :alt="game.name" class="w-5 h-5 mr-3">
          <span>{{ game.name }}</span>
        </Button>
      </div>
    </Modal>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Avatar from '@/Components/UI/Avatar.vue'
import Button from '@/Components/UI/Button.vue'
import Icon from '@/Components/UI/Icon.vue'
import Modal from '@/Components/UI/Modal.vue'
import ModalHeader from '@/Components/UI/ModalHeader.vue'

interface User {
  id: number;
  name: string;
  avatar_url: string;
}

interface Rsvp {
  id: number;
  user: User;
  status: string;
}

interface Game {
  id: string;
  name: string;
  icon: string;
  url: string;
}

const props = defineProps<{
  rsvpList: Rsvp[];
  currentUserId: number;
  games: Game[];
}>();

const openDropdownId = ref<number | null>(null);
const selectedUser = ref<User | null>(null);
const isMobile = ref(false);

const checkMobile = () => {
  isMobile.value = window.innerWidth < 640;
};

const toggleDropdown = (event: MouseEvent, userId: number) => {
  event.stopPropagation();
  
  // If clicking the same button, close the dropdown
  if (openDropdownId.value === userId) {
    closeDropdown();
    return;
  }
  
  // Find the selected user
  const user = props.rsvpList.find(rsvp => rsvp.user.id === userId)?.user;
  if (!user) return;

  // Open dropdown/modal for this user
  selectedUser.value = user;
  openDropdownId.value = userId;
};

const closeDropdown = () => {
  openDropdownId.value = null;
  selectedUser.value = null;
};

const challengeToGame = (user: User, game: Game) => {
  router.post('/game-challenge', {
    challenged_id: user.id,
    game_type: game.id,
    game_url: game.url
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      closeDropdown();
    },
    onError: (errors) => {
      console.error('Failed to create challenge:', errors);
    }
  });
};

onMounted(() => {
  checkMobile();
  window.addEventListener('resize', checkMobile);
  document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
  window.removeEventListener('resize', checkMobile);
  document.removeEventListener('click', closeDropdown);
});

const attendingUsers = computed(() => {
  return props.rsvpList?.filter(rsvp => rsvp.status === 'attending') || [];
});

const hasUserRsvped = computed(() => {
  return props.rsvpList.some(rsvp => 
    rsvp.user.id === props.currentUserId && 
    rsvp.status === 'attending'
  );
});
</script>
