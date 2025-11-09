<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  totalAnnouncements: Number,
  totalStudents: Number,
  totalTeachers: Number,
  totalClasses: Number,
  totalSubjects: Number,
  totalAttendence: Number,
  totalExams: Number,
  totalResults: Number,
  totalPaidFees: Number,
  studentPaidFees: Number,
  userRole: String
});
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <div class="min-h-screen flex flex-col items-center bg-gradient-to-b from-[#f9f9fb] via-[#fafafb] to-[#f2f2f4] py-16 px-6">

        <!-- 🌸 Heading -->
        <div class="text-center mb-16">
          <h1 class="text-5xl font-extrabold bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 text-transparent bg-clip-text drop-shadow-md">
            Welcome to
            <span v-if="userRole === 'admin'"> Admin </span>
            <span v-else-if="userRole === 'teacher'"> Teacher </span>
            <span v-else> Student </span>
            Dashboard
          </h1>
          <p class="mt-4 text-gray-500 text-lg tracking-wide">
            Your calm and beautiful space for learning insights 🌿
          </p>
        </div>

        <!-- ✨ Dashboard Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-10 w-full max-w-6xl">
          <Link
            v-for="(card, index) in cards"
            :key="index"
            :href="card.route"
            class="group relative flex items-center justify-between backdrop-blur-md bg-white/60 border border-gray-100 rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-500 p-8 hover:-translate-y-2 hover:bg-white/70"
          >
            <!-- gradient ring on hover -->
            <div class="absolute inset-0 rounded-3xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 bg-gradient-to-r from-indigo-100 via-pink-100 to-purple-100"></div>

            <div class="relative z-10">
              <p class="text-gray-500 text-sm uppercase font-semibold tracking-wide">{{ card.title }}</p>
              <h2 class="text-5xl font-extrabold text-gray-800 mt-3 group-hover:text-indigo-600 transition-colors duration-500">
                {{ card.value }}
              </h2>
            </div>

            <div class="relative z-10 bg-gradient-to-tr from-indigo-100 to-pink-100 p-5 rounded-2xl border border-gray-200 shadow-inner group-hover:scale-110 transition-transform duration-500">
              <i :class="card.icon + ' text-3xl text-indigo-500'"></i>
            </div>
          </Link>
        </div>
      </div>
    </template>
  </AuthenticatedLayout>
</template>

<script>
export default {
  computed: {
    cards() {
      return [
        {
          title: 'Total Announcements',
          value: this.totalAnnouncements,
          icon: 'fa-solid fa-bullhorn',
          route: this.route('announcements.index')
        },
        {
          title: 'Total Students',
          value: this.totalStudents,
          icon: 'fa-solid fa-user-graduate',
          route: this.route('students.index')
        },
        {
          title: 'Total Teachers',
          value: this.totalTeachers,
          icon: 'fa-solid fa-chalkboard-teacher',
          route: this.route('teachers.index')
        },
        {
          title: 'Total Classes',
          value: this.totalClasses,
          icon: 'fa-solid fa-school',
          route: this.route('class-rooms.index')
        },
        {
          title: 'Total Subjects',
          value: this.totalSubjects,
          icon: 'fa-solid fa-book-open',
          route: this.route('subjects.index')
        },
        {
          title: 'Total Attendance',
          value: this.totalAttendence,
          icon: 'fa-solid fa-calendar-check',
          route: this.route('attendances.index')
        },
        {
          title: 'Total Exams',
          value: this.totalExams,
          icon: 'fa-solid fa-file-pen',
          route: this.route('exams.index')
        },
        {
          title: 'Exam Results',
          value: this.totalResults,
          icon: 'fa-solid fa-award',
          route: this.route('results.index')
        },
        {
          title: this.userRole === 'student' ? 'Your Paid Fees' : 'Total Paid Fees (Students)',
          value: this.userRole === 'student' ? this.studentPaidFees : this.totalPaidFees,
          icon: 'fa-solid fa-sack-dollar',
          route: this.route('fees.index')
        }
      ];
    }
  }
};
</script>

<style scoped>
body {
  font-family: 'Inter', sans-serif;
}

.group {
  transition: all 0.5s ease;
}

.group:hover h2 {
  letter-spacing: 0.5px;
}

/* soft pulse glow */
.group:hover {
  box-shadow: 0 10px 40px -10px rgba(100, 100, 255, 0.2);
}
</style>
