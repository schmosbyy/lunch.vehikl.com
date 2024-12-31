<template>
  <div class="bg-white dark:bg-gray-800 rounded-xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Ride Requests</h3>
    <div class="space-y-4">
      <div v-for="request in rideRequests" :key="request.id" class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
          <Avatar 
            :src="request.user.avatar_url" 
            :alt="request.user.name" 
            size="lg"
          />
          <div>
            <p class="text-gray-900 dark:text-white">{{ request.user.name }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ request.location }}</p>
            <p v-if="request.notes" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
              Note: {{ request.notes }}
            </p>
            <div v-if="request.helper" class="mt-2 flex items-center gap-2">
              <Avatar 
                :src="request.helper.avatar_url" 
                :alt="request.helper.name" 
                size="sm"
              />
              <p class="text-sm text-gray-600 dark:text-gray-300">
                <span class="font-medium">{{ request.helper.name }}</span> offered to help
              </p>
            </div>
          </div>
        </div>
        <div v-if="request.user.id === currentUserId">
          <Button 
            variant="secondary"
            @click="cancelRideRequest(request.id)"
            class="!text-red-600 !border-red-600 hover:!bg-red-50"
            icon="close"
          >
           Request
          </Button>
        </div>
        <div v-else-if="request.helper?.id === currentUserId">
          <Button 
            variant="secondary"
            @click="cancelRideOffer(request.id)"
            class="!text-red-600 !border-red-600 hover:!bg-red-50"
            icon="close"
          >
            Cancel Offer
          </Button>
        </div>
        <div v-else-if="!request.helper">
          <Button 
            variant="secondary"
            @click="offerRide(request.id)"
            icon="plus"
          >
            Offer to help
          </Button>
        </div>
      </div>
      <p v-if="rideRequests.length === 0" class="text-gray-500 dark:text-gray-400 italic">
        No ride requests yet.
      </p>
    </div>

    <Alert 
      v-if="showSuccessMessage"
      variant="success"
      class="mt-4"
    >
      Your offer to help has been sent!
    </Alert>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Avatar from '@/Components/UI/Avatar.vue'
import Button from '@/Components/UI/Button.vue'
import Icon from '@/Components/UI/Icon.vue'
import Alert from '@/Components/UI/Alert.vue'

interface User {
  id: number;
  name: string;
  avatar_url: string;
}

interface RideRequest {
  id: number;
  user: User;
  location: string;
  notes?: string;
  helper?: User;
}

const props = defineProps<{
  rideRequests: RideRequest[];
  currentUserId: number;
}>();

const showSuccessMessage = ref(false);

const offerRide = (requestId: number) => {
  router.post(`/ride-request/${requestId}/offer`, {}, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      showSuccessMessage.value = true;
      setTimeout(() => {
        showSuccessMessage.value = false;
      }, 3000);
    },
    onError: (errors) => {
      console.error('Failed to offer ride:', errors);
    }
  });
};

const cancelRideOffer = (requestId: number) => {
  router.post(`/ride-request/${requestId}/cancel-offer`, {}, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      console.log('Successfully cancelled ride offer');
    },
    onError: (errors) => {
      console.error('Failed to cancel ride offer:', errors);
    }
  });
};

const cancelRideRequest = (requestId: number) => {
  router.post(`/ride-request/${requestId}/cancel`, {}, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      console.log('Successfully cancelled ride request');
    },
    onError: (errors) => {
      console.error('Failed to cancel ride request:', errors);
    }
  });
};
</script>
