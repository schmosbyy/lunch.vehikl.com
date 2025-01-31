<template>
  <Modal
    :model-value="modelValue"
    @update:model-value="$emit('update:modelValue', $event)"
    max-width="sm:max-w-2xl"
  >
    <template #header>
      <ModalHeader
        icon="plus"
        title="Invite Vehikl Members"
        subtitle="Search and invite your colleagues to join Friday lunch"
      />
    </template>

    <div class="max-h-[calc(100vh-16rem)] overflow-y-auto">
      <div class="space-y-6">
        <div class="relative">
          <input type="text"
                 v-model="searchQuery"
                 placeholder="Search members to invite..."
                 class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#e26700] focus:border-[#e26700] shadow-sm">
          <div v-if="filteredMembers.length > 0"
               class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 max-h-[300px] overflow-y-auto divide-y divide-gray-100 z-[100]">
            <div v-for="member in filteredMembers"
                 :key="member.login"
                 class="flex items-center justify-between p-4 hover:bg-gray-50 cursor-pointer transition duration-150"
                 @click="inviteMember(member)">
              <div class="flex items-center gap-4 min-w-0">
                <Avatar
                  :src="member.avatar_url"
                  :alt="member.name"
                  size="md"
                  class="flex-shrink-0"
                />
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-medium text-gray-900 truncate">{{ member.name }}</p>
                  <p class="text-xs text-gray-500 truncate">@{{ member.login }}</p>
                </div>
              </div>
              <Button variant="secondary" size="sm" class="flex-shrink-0">
                Invite
              </Button>
            </div>
          </div>
          <div v-else-if="searchQuery.trim() && filteredMembers.length === 0"
               class="absolute left-0 right-0 mt-2 bg-white rounded-xl shadow-lg border border-gray-100 p-4 z-[100]">
            <p class="text-sm text-gray-500 text-center">No members found</p>
          </div>
        </div>

        <!-- Add spacing to prevent overlap -->
        <div v-if="filteredMembers.length > 0 || (searchQuery.trim() && filteredMembers.length === 0)" class="h-[320px]"></div>

        <!-- Invited Members List -->
        <div v-if="invitedMembers.length > 0" class="mt-8">
          <h4 class="text-base font-medium text-gray-900 mb-4">Invited Members</h4>
          <div class="space-y-3">
            <div v-for="member in invitedMembers"
                 :key="member.login"
                 class="flex items-center justify-between bg-gray-50 p-4 rounded-xl border border-gray-100">
              <div class="flex items-center gap-4 min-w-0">
                <Avatar
                  :src="member.avatar_url"
                  :alt="member.name"
                  size="md"
                  class="flex-shrink-0"
                />
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-medium text-gray-900 truncate">{{ member.name }}</p>
                  <p class="text-xs text-gray-500 truncate">@{{ member.login }}</p>
                </div>
              </div>
              <Button
                variant="danger"
                size="sm"
                @click="cancelInvite(member)"
                class="flex-shrink-0"
              >
                Cancel
              </Button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-6 flex justify-end">
      <Button
        type="button"
        variant="secondary"
        @click="$emit('update:modelValue', false)"
        class="w-full sm:w-auto"
        icon="close"
      >
        Close
      </Button>
    </div>
  </Modal>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Modal from "../../UI/Modal.vue";
import ModalHeader from "../../UI/ModalHeader.vue";
import Button from "../../UI/Button.vue";
import Avatar from "../../UI/Avatar.vue";

interface Member {
  id?: number
  login: string
  name: string
  avatar_url: string
}

interface Rsvp {
  id: number
  user: {
    id: number
    name: string
    avatar_url: string
    github_nickname: string
  }
}

const props = defineProps<{
  modelValue: boolean
  organizationMembers: Member[]
  rsvpList: Rsvp[]
  currentUserId: number
}>()
const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()

const searchQuery = ref('')
const invitedMembers = ref<Member[]>([])

const existingRsvpUserIds = computed(() => {
  return props.rsvpList.map(rsvp => rsvp.user.github_nickname)
})

const filteredMembers = computed(() => {
  const query = searchQuery.value.toLowerCase()

  return props.organizationMembers
    .filter(member => {
      // Filter out members who already have an RSVP
      const hasRsvp = existingRsvpUserIds.value.includes(member.login)
      if (hasRsvp) {
        return false
      }

      // Filter by search query
      if (!query) {
        return true
      }

      const matchesName = (member.name || '').toLowerCase().includes(query)
      const matchesLogin = (member.login || '').toLowerCase().includes(query)

      return matchesName || matchesLogin
    })
    .sort((a, b) => (a.name || '').localeCompare(b.name || ''))
})

function inviteMember(member: Member) {
  router.post('/invite', {
      github_id: member.id,
      github_nickname: member.login
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      invitedMembers.value.push(member)
      searchQuery.value = ''
    }
  })
}

function cancelInvite(member: Member) {
  router.post('/invite/cancel', {
    github_id: member.id
  }, {
    preserveScroll: true,
    preserveState: true,
    onSuccess: () => {
      invitedMembers.value = invitedMembers.value.filter(m => m.login !== member.login)
    }
  })
}
</script>
