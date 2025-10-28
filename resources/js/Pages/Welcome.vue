<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: {
    type: String,
    required: true,
  },
  phpVersion: {
    type: String,
    required: true,
  },
});

function handleImageError() {
  document.getElementById('screenshot-container')?.classList.add('!hidden');
  document.getElementById('docs-card')?.classList.add('!row-span-1');
  document.getElementById('docs-card-content')?.classList.add('!flex-row');
  document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
  <Head title="School Management System" />

  <div
    class="min-h-screen bg-cover bg-center flex flex-col"
    style="background-image: url('https://images.unsplash.com/photo-1600172454132-9608e99c635f?auto=format&fit=crop&w=1920&q=80');"
  >
    <div class="bg-black/60 flex flex-col flex-1">

      <!-- Navbar -->
      <header class="flex justify-between items-center px-8 py-5 text-white">
        <div class="flex items-center gap-3">
          <img
            src="https://cdn-icons-png.flaticon.com/512/201/201623.png"
            alt="Logo"
            class="w-10 h-10"
          />
          <h1 class="text-2xl font-bold">School Management System</h1>
        </div>

        <nav class="space-x-6 text-lg font-medium">
          <Link :href="route('login')" class="hover:text-yellow-300 transition">Login</Link>
          <Link :href="route('register')" class="hover:text-yellow-300 transition">Register</Link>
        </nav>
      </header>

      <!-- Hero Section -->
      <section class="flex flex-col items-center justify-center flex-1 text-center px-6 py-12">
        <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-4 drop-shadow-lg">
          Welcome to <span class="text-yellow-300">Our School Portal</span>
        </h2>
        <p class="max-w-2xl text-gray-200 mb-10 text-lg">
          Manage students, teachers, classes, and announcements — all in one secure and easy-to-use platform.
        </p>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl">
          
          <!-- Students -->
          <Link :href="route('register')" class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 hover:scale-105 transition transform duration-300 cursor-pointer">
            <div class="flex flex-col items-center">
              <div class="bg-blue-100 text-blue-600 p-4 rounded-full mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14c-4 0-8 2-8 6h16c0-4-4-6-8-6zM12 14a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Students</h3>
              <p class="text-sm text-gray-600 text-center">
                Manage student details, attendance, and academic progress.
              </p>
            </div>
          </Link>

          <!-- Teachers -->
          <Link :href="route('register')" class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 hover:scale-105 transition transform duration-300 cursor-pointer">
            <div class="flex flex-col items-center">
              <div class="bg-green-100 text-green-600 p-4 rounded-full mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14c-3.866 0-7 1.79-7 4v2h14v-2c0-2.21-3.134-4-7-4zm0-2a4 4 0 100-8 4 4 0 000 8z" />
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Teachers</h3>
              <p class="text-sm text-gray-600 text-center">
                Manage teacher profiles, schedules, and salaries.
              </p>
            </div>
          </Link>

          <!-- Classes -->
          <Link :href="route('register')" class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 hover:scale-105 transition transform duration-300 cursor-pointer">
            <div class="flex flex-col items-center">
              <div class="bg-yellow-100 text-yellow-600 p-4 rounded-full mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Classes</h3>
              <p class="text-sm text-gray-600 text-center">
                Organize subjects, sections, and schedules.
              </p>
            </div>
          </Link>

          <!-- Announcements -->
          <Link :href="route('register')" class="bg-white/90 backdrop-blur-md rounded-2xl shadow-xl p-6 hover:scale-105 transition transform duration-300 cursor-pointer">
            <div class="flex flex-col items-center">
              <div class="bg-red-100 text-red-600 p-4 rounded-full mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l4 2" />
                </svg>
              </div>
              <h3 class="text-lg font-semibold text-gray-800">Announcements</h3>
              <p class="text-sm text-gray-600 text-center">
                Post important news, updates, and upcoming events.
              </p>
            </div>
          </Link>

        </div>
      </section>

      <!-- Footer -->
      <footer class="py-4 text-center text-gray-200 text-sm bg-black/50">
        © {{ new Date().getFullYear() }} School Management System — All Rights Reserved
      </footer>
    </div>
  </div>
</template>
