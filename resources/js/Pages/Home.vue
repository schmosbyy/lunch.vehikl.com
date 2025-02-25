<template>
  <AppLayout
      @toggle-form-builder="showFormBuilder=!showFormBuilder"
      v-slot="{ showGameChallenges, toggleGameChallenges }">
    <div v-if="showGameChallenges" class="mb-6">
      <Button
        @click="toggleGameChallenges"
        variant="secondary"
        icon="arrow-left"
      >
        Back to Home
      </Button>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-visible shadow-sm sm:rounded-xl border border-gray-200 dark:border-gray-700">
      <div class="p-8 bg-white dark:bg-gray-800 overflow-visible">
        <div v-if="page.props.isLoggedIn" class="overflow-visible">
          <FormBuilderModal
              @form-update-success="handleSuccessToast"
              v-model="showFormBuilder"/>
            <Alert
                v-if="showSuccessToast"
                variant="success"
                class="mb-4"
            >
                Form Urls Updated Successfully
            </Alert>
          <template v-if="!showGameChallenges">
            <div class="space-y-8 overflow-visible">
              <WelcomeSection :user="page.props.user" />

              <RsvpCard
                :user="page.props.user"
                :next-friday="page.props.nextFriday"
                :rsvp-list="page.props.rsvpList"
                :has-rsvped="page.props.hasRsvped"
                :organization-members="page.props.organizationMembers"
                :ride-requests="page.props.rideRequests"
                @toggle-game-challenges="toggleGameChallenges"
              />

              <RideRequests
                v-if="page.props.hasRsvped"
                :ride-requests="page.props.rideRequests"
                :current-user-id="page.props.user?.id"
              />

              <RsvpList
                :rsvp-list="page.props.rsvpList"
                :current-user-id="page.props.user?.id"
                :games="page.props.games"
              />
            </div>
          </template>

          <GameChallenges v-else
            :challenges="page.props.challenges"
          />
        </div>
        <div v-else class="text-center py-16">
          <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">Welcome to Friday Lunch</h2>
          <p class="text-lg text-gray-600 dark:text-gray-300 mb-10 max-w-2xl mx-auto">Join us for our weekly team lunch and gaming sessions. Connect with your colleagues and have fun!</p>
          <Button
            tag="a"
            href="/auth/github"
            variant="primary"
            size="lg"
            class="inline-flex items-center gap-3"
          >
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
            </svg>
            Sign in with GitHub
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import WelcomeSection from '@/Components/Home/WelcomeSection.vue'
import RsvpCard from '@/Components/Home/RsvpCard.vue'
import RsvpList from '@/Components/Home/RsvpList.vue'
import GameChallenges from '@/Components/Home/GameChallenges.vue'
import RideRequests from '@/Components/Home/RideRequests.vue'
import Button from '@/Components/UI/Button.vue'
import { usePage } from '@inertiajs/vue3'
import {ref} from "vue";
import FormBuilderModal from "../Components/Home/Modals/FormBuilderModal.vue";
import Alert from "../Components/UI/Alert.vue";

const page = usePage()
const showFormBuilder = ref(false)
const showSuccessToast = ref(false)
const handleSuccessToast = () => {
    showSuccessToast.value=true;
    setTimeout(() => {
        showSuccessToast.value = false;
    }, 4000);
}
</script>
