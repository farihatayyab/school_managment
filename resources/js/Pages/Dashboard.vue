<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Megaphone } from 'lucide-vue-next';
import { ref } from 'vue';
defineProps({
  totalAnnouncements: Number,
    totalStudents: Number,
    totalTeachers: Number,
    totalClasses: Number,
     userRole: String
});
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="relative flex flex-col items-center justify-start min-h-screen bg-gradient-to-b from-purple-100 via-white to-purple-50 pt-20 px-6">
        
        <!-- 🏫 Page Heading -->
      <div class="text-center mb-16">
  <h1 class="text-5xl font-extrabold text-purple-700 mb-3 tracking-tight drop-shadow-sm">
    🎓 Welcome to 
    <span v-if="userRole === 'admin'">Admin Dashboard</span>
    <span v-else-if="userRole === 'teacher'">Teacher Dashboard</span>
    <span v-else-if="userRole === 'student'">Student Dashboard</span>
  </h1>
  <p class="text-gray-600 text-lg">
    Overview of your portal insights at a glance
  </p>
</div>

        <!-- 📊 Stats Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 w-full max-w-6xl -mt-8">
          
     
       <!-- 📢 Announcements Card -->
            <Link
            :href="route('announcements.index')" 
            class="group flex items-center justify-between bg-white rounded-2xl shadow-xl border border-purple-200 hover:border-purple-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 p-8 cursor-pointer"
            >
            <div>
                <p class="text-gray-500 text-sm uppercase font-semibold">Total Announcements</p>
                <h2 class="text-5xl font-extrabold text-purple-700 mt-2 group-hover:text-purple-800 transition-colors">
                {{ totalAnnouncements }}
                </h2>
            </div>
            <div class="bg-purple-100 p-4 rounded-full border border-purple-300 shadow-inner">
                <i class="fa-solid fa-bullhorn text-3xl text-purple-700"></i>
            </div>
            </Link>


          <!-- 👩‍🎓 Students Card -->
          <Link
            :href="route('students.index')" 
            class="group flex items-center justify-between bg-white rounded-2xl shadow-xl border border-blue-200 hover:border-blue-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 p-8"
          >
            <div>
              <p class="text-gray-500 text-sm uppercase font-semibold">Total Students</p>
              <h2 class="text-5xl font-extrabold text-blue-600 mt-2 group-hover:text-blue-700 transition-colors">
                     {{ totalStudents }}
              </h2>
            </div>
            <div class="bg-blue-100 p-4 rounded-full border border-blue-300 shadow-inner">
              <i class="fa-solid fa-user-graduate text-3xl text-blue-600"></i>
            </div>
             </Link>

          <!-- 👨‍🏫 Teachers Card -->
          <Link
            :href="route('teachers.index')" 
            class="group flex items-center justify-between bg-white rounded-2xl shadow-xl border border-green-200 hover:border-green-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 p-8"
          >
            <div>
              <p class="text-gray-500 text-sm uppercase font-semibold">Total Teachers</p>
              <h2 class="text-5xl font-extrabold text-green-600 mt-2 group-hover:text-green-700 transition-colors">
                 {{ totalTeachers }}
              </h2>
            </div>
            <div class="bg-green-100 p-4 rounded-full border border-green-300 shadow-inner">
              <i class="fa-solid fa-chalkboard-teacher text-3xl text-green-600"></i>
            </div>
            </Link>

            <!-- 🏫 Classes Card -->
            <Link
            :href="route('class-rooms.index')" 
            class="group flex items-center justify-between bg-white rounded-2xl shadow-xl border border-yellow-200 hover:border-yellow-300 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-3 p-8"
            >
            <div>
                <p class="text-gray-500 text-sm uppercase font-semibold">Total Classes</p>
                <h2 class="text-5xl font-extrabold text-yellow-600 mt-2 group-hover:text-yellow-700 transition-colors">
                {{ totalClasses }}
                </h2>
            </div>
            <div class="bg-yellow-100 p-4 rounded-full border border-yellow-300 shadow-inner">
                <i class="fa-solid fa-book text-3xl text-yellow-600"></i>
            </div>
            </Link>


        </div>
      </div>
    </template>
  </AuthenticatedLayout>
</template>
