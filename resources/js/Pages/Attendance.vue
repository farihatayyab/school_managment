<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import Test from '@/Layouts/Test.vue'

defineProps({
  attendances: Array,
  students: Array,
  role: String,
})

const form = useForm({
  id: null,
  student_id: '',
  date: '',
  status: 'present',
})

const isEditing = ref(false)

const submitForm = () => {
  if (isEditing.value) {
    form.put(route('attendances.update', form.id), { onSuccess: () => resetForm() })
  } else {
    form.post(route('attendances.store'), { onSuccess: () => resetForm() })
  }
}

const editAttendance = (attendance) => {
  form.id = attendance.id
  form.student_id = attendance.student_id
  form.date = attendance.date
  form.status = attendance.status
  isEditing.value = true
}

const resetForm = () => {
  form.reset()
  isEditing.value = false
}
</script>

<template>
  <Test>
    <div class="max-w-6xl mx-auto p-8">
      <h1 class="text-4xl font-bold text-blue-700 mb-8 text-center">
        📅 Attendance Management
      </h1>

      <!-- 🟢 Show form only for Admin & Teacher -->
      <form
        v-if="role === 'admin' || role === 'teacher'"
        @submit.prevent="submitForm"
        class="bg-white p-6 rounded-2xl shadow-lg mb-6"
      >
        <div class="grid grid-cols-3 gap-6">
          <div>
            <label class="font-semibold text-gray-700">Student</label>
            <select v-model="form.student_id" class="w-full p-2 border rounded-lg">
              <option disabled value="">Select Student</option>
              <option v-for="s in students" :key="s.id" :value="s.id">{{ s.user.name }}</option>
            </select>
          </div>

          <div>
            <label class="font-semibold text-gray-700">Date</label>
            <input type="date" v-model="form.date" class="w-full p-2 border rounded-lg" />
          </div>

          <div>
            <label class="font-semibold text-gray-700">Status</label>
            <select v-model="form.status" class="w-full p-2 border rounded-lg">
              <option value="present">Present</option>
              <option value="absent">Absent</option>
            </select>
          </div>
        </div>

        <div class="mt-6 text-right">
          <button
            type="submit"
            class="px-6 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition"
          >
            {{ isEditing ? 'Update Attendance' : 'Add Attendance' }}
          </button>
          <button
            v-if="isEditing"
            type="button"
            @click="resetForm"
            class="ml-2 px-6 py-2 rounded-xl bg-gray-400 text-white hover:bg-gray-500"
          >
            Cancel
          </button>
        </div>
      </form>

      <!-- 🟣 Table -->
      <div class="bg-white p-6 rounded-2xl shadow-lg">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Attendance Records</h2>
        <table class="w-full border-collapse">
          <thead class="bg-blue-100">
            <tr>
              <th class="p-3 border">#</th>
              <th class="p-3 border">Student</th>
              <th class="p-3 border">Date</th>
              <th class="p-3 border">Status</th>
              <th v-if="role !== 'student'" class="p-3 border">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(a, index) in attendances" :key="a.id" class="text-center hover:bg-gray-50">
              <td class="p-3 border">{{ index + 1 }}</td>
              <td class="p-3 border">{{ a.student.user.name }}</td>
              <td class="p-3 border">{{ a.date }}</td>
              <td class="p-3 border">
                <span
                  :class="a.status === 'present' ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold'"
                >
                  {{ a.status }}
                </span>
              </td>
              <!-- 🟢 Actions visible only to Admin & Teacher -->
              <td v-if="role === 'admin' || role === 'teacher'" class="actions">
                <button @click="editAttendance(a)" class="edit-btn">
                  Edit
                </button>
                <button
                  @click="$inertia.delete(route('attendances.destroy', a.id))"
                  class="delete-btn"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </Test>
</template>

<style scoped>
.edit-btn {
  @apply bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md transition;
}
.delete-btn {
  @apply bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md transition;
}
.actions {
  display: flex;
  justify-content: center;
  gap: 0.5rem;
}
</style>
