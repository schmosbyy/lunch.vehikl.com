<template>
  <div class="bg-white dark:bg-gray-800 rounded-xl p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Friday Lunch RSVP</h3>
    <p class="text-gray-600 dark:text-gray-300 mb-6 text-lg">
      Next lunch is on <span class="font-medium text-gray-900 dark:text-white">{{ nextFriday?.formatted }}</span>
    </p>

    <RsvpConfirmation
      v-if="hasRsvped && !hasSubmittedOutOfTownForm && currentUserRsvp?.status === 'attending'"
      :user="user"
      :show-resend-message="showResendMessage"
      @resend-email="resendEmail"
      @cancel-rsvp="cancelRsvp"
      @toggle-game-challenges="$emit('toggle-game-challenges')"
      @show-invite-modal="showInviteModal = true"
      @show-order-modal="showOrderModal = true"
      @show-ride-request-modal="showRideRequestModal = true"
      :has-ride-request="hasRideRequest"
      :is-vehikl-member="isVehiklMember"
      :calendar-url="generateCalendarUrl"
    />

    <RsvpForm
      v-else
      :has-submitted-out-of-town-form="hasSubmittedOutOfTownForm"
      :is-rsvp-disabled="isRsvpDisabled"
      :is-out-of-town-disabled="isOutOfTownDisabled"
      :user="user"
      :rsvp-button-text="rsvpButtonText"
      @submit="submit"
      @show-out-of-town-modal="showOutOfTownModal = true"
      @reset-out-of-town-form="resetOutOfTownForm"
    />

    <OutOfTownModal
      v-model="showOutOfTownModal"
      :user-email="user?.email"
    />
      <CustomizeOrderModal
          v-model="showOrderModal"
          :user-email="user?.email"
      />
    <InviteModal
      v-model="showInviteModal"
      :organization-members="organizationMembers"
      :rsvp-list="rsvpList"
      :current-user-id="user?.id"
    />
    <RideRequestModal
      v-model="showRideRequestModal"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, nextTick } from 'vue'
import RsvpConfirmation from './RsvpConfirmation.vue'
import RsvpForm from './RsvpForm.vue'
import OutOfTownModal from './Modals/OutOfTownModal.vue'
import InviteModal from './Modals/InviteModal.vue'
import RideRequestModal from './Modals/RideRequestModal.vue'
import { router } from '@inertiajs/vue3'
import CustomizeOrderModal from "./Modals/CustomizeOrderModal.vue";

const props = defineProps({
  user: {
    type: Object,
    required: true
  },
  nextFriday: {
    type: Object,
    required: true
  },
  rsvpList: {
    type: Array,
    required: true
  },
  hasRsvped: {
    type: Boolean,
    required: true
  },
  organizationMembers: {
    type: Array,
    required: true
  },
  rideRequests: {
    type: Array,
    required: true
  }
})

const emit = defineEmits(['toggle-game-challenges'])

const showResendMessage = ref(false)
const showOutOfTownModal = ref(false)
const showInviteModal = ref(false)
const showOrderModal = ref(false)
const showRideRequestModal = ref(false)

const currentUserRsvp = computed(() => {
  return props.rsvpList?.find(rsvp => rsvp.user?.id === props.user?.id)
})

const hasRideRequest = computed(() =>
  props.rideRequests?.some(request => request.user?.id === props.user?.id)
)

const isVehiklMember = computed(() => {
  const orgs = props.user?.github_organizations || []
  return orgs.some(org => org.login === 'vehikl')
})

const generateCalendarUrl = computed(() => {
  const event = {
    text: 'Vehikl Friday Lunch',
    dates: props.nextFriday.date,
    details: 'Join us for lunch! RSVP confirmed.',
    location: 'Vehikl Office'
  }

  const url = new URL('https://calendar.google.com/calendar/render')
  url.searchParams.append('action', 'TEMPLATE')
  url.searchParams.append('text', event.text)
  url.searchParams.append('dates', `${event.dates.replace(/-/g, '')}T120000/${event.dates.replace(/-/g, '')}T133000`)
  url.searchParams.append('details', event.details)
  url.searchParams.append('location', event.location)

  return url.toString()
})

const isRsvpDisabled = computed(() => {
  return Boolean(currentUserRsvp.value &&
    (currentUserRsvp.value.status === 'out_of_town' || currentUserRsvp.value.status === 'attending'))
})

const isOutOfTownDisabled = computed(() => {
  return Boolean(currentUserRsvp.value &&
    (currentUserRsvp.value.status === 'out_of_town' || currentUserRsvp.value.status === 'attending'))
})

const rsvpButtonText = computed(() => {
  if (currentUserRsvp.value?.status === 'out_of_town') return 'Out of Town'
  if (currentUserRsvp.value?.status === 'attending') return 'Attending'
  return 'RSVP for Lunch'
})

const hasSubmittedOutOfTownForm = computed(() => {
  return Boolean(currentUserRsvp.value?.status === 'out_of_town')
})

function submit() {
  router.post('/rsvp', {}, {
    onSuccess: () => {
      window.location.href = '/home'
    }
  })
}

function resendEmail() {
  router.post('/rsvp/resend', {}, {
    onSuccess: () => {
      showResendMessage.value = false
      nextTick(() => {
        showResendMessage.value = true
        setTimeout(() => {
          showResendMessage.value = false
        }, 3000)
      })
    }
  })
}

function cancelRsvp() {
  router.post('/rsvp/cancel', {}, {
    onSuccess: () => {
      window.location.href = '/home'
    }
  })
}

function resetOutOfTownForm() {
  router.post('/rsvp/cancel', {}, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      router.reload()
    }
  })
}
</script>
