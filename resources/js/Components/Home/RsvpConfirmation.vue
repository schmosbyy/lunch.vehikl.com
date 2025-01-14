<template>
  <div class="bg-[#fff8f3] border border-[#e26700] rounded-xl p-4">
    <div class="flex">
      <div class="flex-shrink-0">
        <Icon name="check" size="lg" class="text-[#e26700]" />
      </div>
      <div class="ml-3">
        <p class="text-base font-medium text-[#e26700]">
          You're all set! We've got your RSVP for this Friday's lunch.
        </p>
        <p v-if="showResendMessage" class="mt-2 text-sm text-[#e26700] animate-fade-out">
          A confirmation email has been sent to {{ user?.email }}
        </p>
        <div class="mt-6 flex flex-wrap items-center gap-2">
            <Button
                variant="secondary"
                @click="$emit('show-order-modal')"
                icon="food"
            >
                Customize Order
            </Button>
          <Button
            variant="secondary"
            @click="$emit('resend-email')"
            icon="mail"
          >
            Resend Email
          </Button>

          <form @submit.prevent="$emit('cancel-rsvp')" class="inline">
            <Button
              type="submit"
              variant="secondary"
              class="!text-red-600 !border-red-600 hover:!bg-red-50"
              icon="close"
            >
             RSVP
            </Button>
          </form>

          <Button
            variant="secondary"
            tag="a"
            :href="calendarUrl"
            target="_blank"
            icon="calendar"
          >
            + to Calendar
          </Button>

          <Button
            v-if="isVehiklMember"
            variant="secondary"
            @click="$emit('show-invite-modal')"
            icon="plus"
          >
            Invite Others
          </Button>
          <Button
            v-if="!hasRideRequest"
            variant="secondary"
            @click="$emit('show-ride-request-modal')"
            icon="arrows-right-left"
          >
            Ask for a Ride
          </Button>

          <Button
            variant="secondary"
            @click="$emit('toggle-game-challenges')"
            icon="puzzle"
          >
            Challenges
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import Button from '@/Components/UI/Button.vue'
import Icon from '@/Components/UI/Icon.vue'
import Link from '@/Components/UI/Link.vue'

defineProps({
  user: {
    type: Object,
    required: true
  },
  showResendMessage: {
    type: Boolean,
    required: true
  },
  hasRideRequest: {
    type: Boolean,
    required: true
  },
  isVehiklMember: {
    type: Boolean,
    required: true
  },
  calendarUrl: {
    type: String,
    required: true
  }
})

defineEmits([
  'resend-email',
  'cancel-rsvp',
  'toggle-game-challenges',
  'show-invite-modal',
  'show-ride-request-modal'
])
</script>

<style>
.animate-fade-out {
  animation: fadeOut 3s ease-in-out forwards;
  opacity: 0;
}

@keyframes fadeOut {
  0% { opacity: 1; }
  70% { opacity: 1; }
  100% { opacity: 0; visibility: hidden; }
}
</style>
