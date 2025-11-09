<script setup>
import { Link } from '@inertiajs/vue3'
import { BarChart2 } from 'lucide-vue-next'
import { Coins } from "lucide-vue-next";


import {
  LayoutDashboard,
  Users,
  UserCheck,
  School,
  Megaphone,
  BookOpen,
  ClipboardList,
  FileText
} from 'lucide-vue-next' // ✅ Lucide icons import

const props = defineProps({
  userRole: String,
})
</script>

<template>
  <div class="min-h-screen flex bg-gray-100">
    <!-- ✅ Sidebar -->
    <aside class="w-56 bg-gray-800 text-white flex flex-col">
      <div class="p-4 text-2lg font-bold border-b border-gray-700  ">
        🏫 School Management
      </div>

      <nav class="flex-1 p-4 space-y-2">
        <Link
          href="/dashboard"
          class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-700 transition"
        >
          <LayoutDashboard class="w-5 h-5" />
          <span>Dashboard</span>
        </Link>

        <Link
          href="/students"
          class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-700 transition"
        >
          <Users class="w-5 h-5" />
          <span>Students</span>
        </Link>

        <Link
          href="/teachers"
          class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-700 transition"
        >
          <UserCheck class="w-5 h-5" />
          <span>Teachers</span>
        </Link>

        <Link
          href="/class-rooms"
          class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-700 transition"
        >
          <School class="w-5 h-5" />
          <span>Classes</span>
        </Link>

        <Link
          href="/announcements"
          class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-700 transition"
        >
          <Megaphone class="w-5 h-5" />
          <span>Announcements</span>
        </Link>

        <Link
          href="/subjects"
          class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-700 transition"
        >
          <BookOpen class="w-5 h-5" />
          <span>Subjects</span>
        </Link>

        <Link
          href="/attendances"
          class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-700 transition"
        >
          <ClipboardList class="w-5 h-5" />
          <span>Attendance</span>
        </Link>

        <Link
          href="/exams"
          class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-700 transition"
        >
          <FileText class="w-5 h-5" />
          <span>Exams</span>
        </Link>

         <Link
        href="/results"
        class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-700 transition"
      >
        <BarChart2 class="w-5 h-5" />   
        <span>Results</span>
      </Link>
      
      <Link
  href="/fees"
  class="flex items-center gap-3 px-4 py-2 rounded hover:bg-gray-700 transition"
>
  <Coins class="w-5 h-5" />   
  <span>Fees</span>
</Link>


      </nav>
    </aside>

    <!-- ✅ Main content -->
    <div class="flex-1 flex flex-col">
      <header>
        <slot name="header"></slot>
      </header>

      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>
