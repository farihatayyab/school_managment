<template>
  <Head title="School Management System" />

  <!-- 🌐 Header Section -->
  <header class="bg-gray-900 text-gray-200 py-3 px-6 text-sm border-b border-gray-700">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-2 md:gap-0">
      <div class="flex items-center gap-4">
        <a href="mailto:farihatayyab09@gmail.com" class="flex items-center gap-2 hover:text-yellow-400 transition">
          <i class="fa-solid fa-envelope"></i>
          farihatayyab09@gmail.com
        </a>
        <a href="tel:031012101388" class="flex items-center gap-2 hover:text-yellow-400 transition">
          <i class="fa-solid fa-phone"></i>
          0310-12101388
        </a>
      </div>
      <div>
        <a
          href="https://www.linkedin.com/in/fariha-tayyab/"
          target="_blank"
          class="flex items-center gap-2 hover:text-yellow-400 transition"
        >
          <i class="fa-brands fa-linkedin text-blue-400"></i>
          linkedin.com
        </a>
      </div>
    </div>
  </header>

  <!-- 🌅 Hero Section -->
  <section class="relative overflow-hidden bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-500 text-white py-28 px-6">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-16">
      <div class="md:w-1/2 space-y-6">
        <h1 class="text-5xl md:text-6xl font-extrabold leading-tight drop-shadow-lg">
          Simplify Your <span class="text-yellow-300">School Management</span>
        </h1>
        <p class="text-lg text-indigo-100">
          Manage students, teachers, attendance, and results — all from a single smart dashboard.
          Save time. Boost productivity. Go digital.
        </p>
        <div class="flex gap-4 mt-6">
          <Link
            :href="route('login')"
            class="bg-yellow-400 text-gray-900 font-semibold px-6 py-3 rounded-xl hover:bg-yellow-300 transition-all"
          >
            Get Started
          </Link>
          <Link
            :href="route('register')"
            class="border border-white/70 px-6 py-3 rounded-xl hover:bg-white/10 transition-all"
          >
            Learn More
          </Link>
        </div>
      </div>

      <div class="md:w-1/2">
        <img
          src="@/assets/image.jpg"
          alt="Dashboard Preview"
          class="rounded-3xl shadow-2xl border border-white/20"
        />
      </div>
    </div>

    <div class="absolute top-0 left-0 w-72 h-72 bg-pink-400 rounded-full mix-blend-overlay blur-3xl opacity-30 animate-pulse"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-400 rounded-full mix-blend-overlay blur-3xl opacity-30 animate-pulse"></div>
  </section>

  <!-- ⚡ Stats Section -->
  <section class="bg-white py-20 text-center">
    <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8">
      <div v-for="(stat, i) in stats" :key="i" class="space-y-2">
        <h3 class="text-4xl font-extrabold text-purple-600">{{ stat.number }}</h3>
        <p class="text-gray-500">{{ stat.label }}</p>
      </div>
    </div>
  </section>

  <!-- 🎯 Features Section -->
  <section class="bg-gradient-to-b from-purple-50 to-blue-50 py-24 px-6 text-center">
    <h2 class="text-4xl font-bold text-gray-800 mb-4">Powerful Features</h2>
    <p class="text-gray-600 mb-16 max-w-2xl mx-auto">
      Everything you need to manage your school efficiently — beautifully organized in one place.
    </p>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 max-w-6xl mx-auto">
      <div
        v-for="(feature, i) in features"
        :key="i"
        class="p-10 bg-white rounded-3xl shadow-xl hover:shadow-2xl transition transform hover:-translate-y-2"
      >
        <div class="bg-purple-100 w-14 h-14 flex items-center justify-center rounded-2xl mx-auto mb-6">
          <i :class="feature.icon" class="text-2xl text-purple-600"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-800 mb-3">{{ feature.title }}</h3>
        <p class="text-gray-600 text-sm">{{ feature.desc }}</p>
      </div>
    </div>
  </section>

  <!-- 💼 CTA Section -->
  <section class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20 px-6 text-center rounded-t-3xl">
    <h2 class="text-3xl md:text-4xl font-bold mb-6">Upgrade Your School Today</h2>
    <p class="text-indigo-100 mb-8 max-w-2xl mx-auto">
      Join hundreds of schools already using our management system to automate their workflow.
    </p>
    <Link
      :href="route('register')"
      class="bg-yellow-400 text-gray-900 font-semibold px-8 py-4 rounded-xl hover:bg-yellow-300 transition"
    >
      Get Started Now
    </Link>
  </section>

  <!-- 🦶 Footer -->
  <footer class="bg-gray-900 text-gray-400 text-center py-6">
    <p>© {{ new Date().getFullYear() }} Smart School Management System. All Rights Reserved.</p>
  </footer>
</template>

<script setup>
import { Link, Head } from '@inertiajs/vue3'

const stats = [
  { number: '150+', label: 'Schools Registered' },
  { number: '4k', label: 'Students Managed' },
  { number: '98%', label: 'Client Satisfaction' },
  { number: '24/7', label: 'Active Support' },
]

const features = [
  {
    icon: 'fa-solid fa-user-graduate',
    title: 'Student Management',
    desc: 'Easily add, edit, and track student records with advanced search and filters.',
  },
  {
    icon: 'fa-solid fa-chalkboard-teacher',
    title: 'Teacher Dashboard',
    desc: 'Dedicated teacher panels to manage classes, assignments, and student progress.',
  },
  {
    icon: 'fa-solid fa-money-check-dollar',
    title: 'Fee Management',
    desc: 'Automate fee collection and generate detailed payment reports in seconds.',
  },
  {
    icon: 'fa-solid fa-calendar-days',
    title: 'Attendance Tracking',
    desc: 'Mark daily attendance for students and staff and export records easily.',
  },
  {
    icon: 'fa-solid fa-chart-line',
    title: 'Exam & Results',
    desc: 'Enter marks, generate report cards, and share results instantly with parents.',
  },
  {
    icon: 'fa-solid fa-bell',
    title: 'Notifications',
    desc: 'Send instant SMS or email alerts to parents, teachers, and students.',
  },
]
</script>

<style scoped>
@keyframes pulse {
  0%, 100% {
    opacity: 0.3;
    transform: scale(1);
  }
  50% {
    opacity: 0.5;
    transform: scale(1.1);
  }
}
.animate-pulse {
  animation: pulse 6s infinite;
}
</style>
