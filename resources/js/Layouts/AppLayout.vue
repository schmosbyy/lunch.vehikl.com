<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
              <Link :href="route('home')" class="transition hover:opacity-80">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 931.42 235.11" class="h-8">
                  <path d="M39.43 235.11C17.32 235.11 0 217.3 0 194.56v-154C0 17.81 17.32 0 39.43 0a39.7 39.7 0 0 1 4.48.25L169.7 14c18.17 2 32.4 19.72 32.4 40.38v126.39c0 20.66-14.23 38.39-32.4 40.37L43.91 234.86a39.7 39.7 0 0 1-4.48.25zm0-215.87c-11.51 0-20.19 9.16-20.19 21.31v154c0 12.15 8.68 21.31 20.19 21.31a21.57 21.57 0 0 0 2.4-.13L167.61 202c8.27-.9 15.25-10.63 15.25-21.25V54.34c0-10.62-7-20.35-15.25-21.25L41.83 19.37a21.7 21.7 0 0 0-2.4-.13z" style="fill: #e26700;" />
                  <path d="M154.54 70c-1.28-7.18-6.74-14-14-15.54-6.8-1.4-13.89 2.32-17.07 9.3-3.84 8.42-1.35 19.2 5.73 24.79a2.4 2.4 0 0 1 1 2.38c-.13 1.42-.15 2.87-.29 4.29-.89 8.65-4.63 15.16-11.69 19.08a77.63 77.63 0 0 1-8.86 4.15c-5.52 2.22-11.17 4.26-16.86 6.45-1.14.43-2.23 1-3.44 1.58 0-.49-.06-.82-.06-1.15V75.91A2 2 0 0 1 90.06 74c7.56-5.21 10.21-15.87 6.26-24.9-4.07-9.33-13.86-14.66-22.84-12-7.41 2.22-12 7.59-12.91 16A21.28 21.28 0 0 0 68.92 73a2.67 2.67 0 0 1 1.22 2.37q-.06 42.15 0 84.3a2.64 2.64 0 0 1-1.14 2.41c-7.72 5.82-10.76 16.84-7 25.5 3.83 8.9 13.16 13.17 22 10 7.4-2.63 12.14-8.14 13.73-16.2s-.61-14.71-6.5-19.4c-2-1.56-2.3-3.13-2.2-5.46.24-5.3 2.4-9.2 6.87-11.44 3-1.5 6-3 9.05-4.22 6.84-2.76 13.69-5.13 20-8.74a40.38 40.38 0 0 0 17.74-19.79A56.71 56.71 0 0 0 146.75 92c0-1.08 0-2 1-2.76 5.49-4.04 8.13-11.69 6.79-19.24zM79.73 188.36c-5.26.44-9.6-3.85-9.59-9.53s4.25-10.35 9.49-10.67 9.3 4 9.32 9.45-4.1 10.33-9.22 10.75zm0-121.4c-5.28-.26-9.58-5.07-9.57-10.77s4.27-9.8 9.5-9.44S89 51.92 89 57.41s-4.18 9.81-9.29 9.59zm58.8 15.79c-4.58-.24-8.3-4.67-8.29-9.94s3.77-9.29 8.35-9 8.19 4.68 8.2 9.84-3.73 9.35-8.28 9.1z" style="fill: #e26700;" />
                  <path d="M299 192.29l-63.34-151h43.74l30.23 84.49q.4 1.33 1.7 6.45t3.34 13.77q1.65-6.91 3.1-12.48t2.16-7.74l30-84.49h43.74l-63.34 151zM393.89 192.29v-151h92.53v33.1H434v26.2h52.42V133H434v25.48h52.4v33.83zM498.06 192.29v-151h41.16V100h54.87V41.27h41.27v151h-41.27v-61.46h-54.87v61.48zM647.34 192.29v-151h41.57v151zM701 192.29v-151h40.75v68.6l47.76-68.6h48l-55.81 73.66 57.64 77.36h-51.41l-46.18-67.77v67.77z" style="fill: #e26700;" />
                  <path d="M839.1 192.29v-151h41.16V157h51.16v35.27z" style="fill: #e26700;" />
                </svg>
              </Link>
            </div>
          </div>

          <div class="flex items-center">
            <NotificationBell
              v-if="$page.props.isLoggedIn"
              :notification-challenges="$page.props.notificationChallenges || []"
              class="mr-4"
            />

            <!-- Profile Dropdown -->
            <div class="relative">
              <Button
                v-if="$page.props.isLoggedIn"
                variant="secondary"
                @click="showProfileDropdown = !showProfileDropdown"
                class="flex items-center space-x-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl px-3 py-2 !border-0"
              >
                <img
                  :src="$page.props.user?.avatar_url"
                  :alt="$page.props.user?.name"
                  class="h-9 w-9 rounded-full ring-2 ring-white"
                >
                <span class="text-gray-700 dark:text-gray-200 font-medium">{{ $page.props.user?.name }}</span>
                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </Button>

              <div v-if="showProfileDropdown"
                   class="absolute right-0 mt-2 w-72 bg-white dark:bg-gray-800 rounded-xl shadow-lg py-1 z-10 border border-gray-100 dark:border-gray-700 backdrop-blur-sm">
                <!-- GitHub Profile Info -->
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                  <h3 class="text-sm font-medium text-gray-900 dark:text-white mb-3">Your GitHub Profile</h3>
                  <div class="space-y-2.5 text-sm text-gray-600 dark:text-gray-400">
                    <p><span class="font-medium">Email:</span> {{ $page.props.user?.email }}</p>
                    <p><span class="font-medium">GitHub Username:</span> {{ $page.props.user?.github_nickname }}</p>
                    <div v-if="$page.props.user?.github_organizations?.length">
                      <p class="font-medium mb-2">Organizations:</p>
                      <div class="flex flex-wrap gap-2">
                        <Link
                          v-for="org in $page.props.user?.github_organizations"
                          :key="org.id"
                          :href="'https://github.com/' + org.login"
                          target="_blank"
                          class="inline-flex items-center px-2.5 py-1.5 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg text-xs transition-colors"
                        >
                          <img :src="org.avatar_url" :alt="org.login" class="w-4 h-4 rounded-sm mr-1.5">
                          {{ org.login }}
                        </Link>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Dark Mode Toggle -->
                <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                  <Button
                    variant="secondary"
                    @click="toggleDarkMode"
                    class="w-full text-left flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white !border-0"
                    :icon="isDarkMode ? 'sun' : 'moon'"
                  >
                    {{ isDarkMode ? 'Light Mode' : 'Dark Mode' }}
                  </Button>
                </div>

                <!-- Logout -->
                <div>
                  <Button
                    variant="secondary"
                    @click="logout"
                    class="w-full text-left px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/50 !border-0"
                    icon="logout"
                  >
                    Logout
                  </Button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Flash Messages -->
    <div v-if="$page.props.flash?.success || $page.props.flash?.error" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
      <Alert v-if="$page.props.flash.success" variant="success">
        {{ $page.props.flash.success }}
      </Alert>
      <Alert v-if="$page.props.flash.error" variant="error">
        {{ $page.props.flash.error }}
      </Alert>
    </div>

    <!-- Page Content -->
    <main>
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <slot
            :show-game-challenges="showGameChallenges"
            :toggle-game-challenges="toggleGameChallenges"
          />
        </div>
      </div>
    </main>
    <FloatingActionButton v-if="$page.props.isLoggedIn" />
  </div>
</template>

<script setup lang="ts">
import { router, Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import Alert from '@/Components/UI/Alert.vue'
import Button from '@/Components/UI/Button.vue'
import NotificationBell from '@/Components/UI/NotificationBell.vue'
import FloatingActionButton from '../Components/FloatingActionButton.vue'

// Add NavLink component
const NavLink = Link

interface Organization {
  id: number;
  login: string;
  avatar_url: string;
}

interface User {
  id?: number;
  name?: string;
  email?: string;
  avatar_url?: string;
  github_nickname?: string;
  github_organizations?: Organization[];
}

interface Flash {
  success?: string;
  error?: string;
}

interface PageProps {
  user?: User;
  isLoggedIn: boolean;
  flash?: Flash;
}

const showProfileDropdown = ref(false)
const showGameChallenges = ref(false)
const isDarkMode = ref(false)

// Initialize dark mode from localStorage or system preference
onMounted(() => {
  const savedTheme = localStorage.getItem('theme')
  if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark')
    isDarkMode.value = true
  }
})

const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
  if (isDarkMode.value) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('theme', 'dark')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('theme', 'light')
  }
}

const toggleGameChallenges = () => {
  showGameChallenges.value = !showGameChallenges.value
}

const logout = () => {
  router.post('/logout', {}, {
    preserveScroll: true,
    onSuccess: () => {
      window.location.href = '/home'
    }
  })
}

// Add route function
const route = (name: string) => {
  return '/home'
}
</script>
