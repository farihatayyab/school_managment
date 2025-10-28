<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
  <div class="min-h-screen flex bg-gray-100">

    <!-- ✅ Sidebar -->
    <aside class="w-54 bg-gray-800 text-white flex flex-col">
      <div class="p-4 text-2xl font-bold border-b border-gray-700">
        School Management
      </div>

      <nav class="flex-1 p-4 space-y-2">
        <Link
          href="/dashboard"
          class="block px-4 py-2 rounded hover:bg-gray-700"
        >
          Dashboard
        </Link>

        <Link
          href="/students"
          class="block px-4 py-2 rounded hover:bg-gray-700"
        >
          Students
        </Link>

        <Link
          href="/teachers"
          class="block px-4 py-2 rounded hover:bg-gray-700"
        >
          Teachers
        </Link>

        <Link
          href="/class-rooms"
          class="block px-4 py-2 rounded hover:bg-gray-700"
        >
          Classes
        </Link>

        <Link
          href="/announcements"
          class="block px-4 py-2 rounded hover:bg-gray-700"
        >
          Announcements
        </Link>
      </nav>
    </aside>

    <!-- ✅ Main content -->
    <div class="flex-1 flex flex-col">
      <!-- Header -->
      <header >
        <slot name="header"></slot>
      </header>

      <!-- Page Content -->
      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>
