<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import Test from '@/Layouts/Test.vue'

defineProps({
  exams: Array,
  students: Array,
  classes: Array,
  subjects: Array,
  role: String, // 👈 Role aata hai backend se
})

// Form setup
const form = useForm({
  id: null,
  student_id: '',
  class_id: '',
  subject_id: '',
  exam_date: '',
  total_marks: '',
})

const isEditing = ref(false)

// ✏️ Edit mode
const editExam = (exam) => {
  isEditing.value = true
  form.id = exam.id
  form.student_id = exam.student_id
  form.class_id = exam.class_id
  form.subject_id = exam.subject_id
  form.exam_date = exam.exam_date
  form.total_marks = exam.total_marks
}

// 📤 Submit form (Add / Update)
const submitForm = () => {
  if (isEditing.value) {
    form.put(route('exams.update', form.id))
  } else {
    form.post(route('exams.store'))
  }
  form.reset()
  isEditing.value = false
}
</script>

<template>
  <Test>
    <div class="min-h-screen bg-gray-50 py-10">
      <!-- 🧾 Header -->
      <div class="text-center mb-10">
        <h1 class="text-4xl font-bold text-blue-700 flex items-center justify-center gap-2">
          🎓 Exam Management
        </h1>
        <p class="text-gray-500 text-sm">Manage student exams based on user role</p>
      </div>

      <!-- 🧑‍🏫 Admin + Teacher: Add/Edit Form -->
      <div
        v-if="role === 'admin' || role === 'teacher'"
        class="max-w-5xl mx-auto bg-white rounded-2xl shadow-md p-6 mb-10 border border-gray-100"
      >
        <form
          @submit.prevent="submitForm"
          class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-end"
        >
          <div>
            <label class="block text-gray-700 font-semibold mb-2">Student</label>
            <select v-model="form.student_id" class="form-select">
              <option disabled value="">Select Student</option>
              <option v-for="s in students" :key="s.id" :value="s.id">
                {{ s.user.name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-2">Class</label>
            <select v-model="form.class_id" class="form-select">
              <option disabled value="">Select Class</option>
              <option v-for="c in classes" :key="c.id" :value="c.id">
                {{ c.class_name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-2">Subject</label>
            <select v-model="form.subject_id" class="form-select">
              <option disabled value="">Select Subject</option>
              <option v-for="sub in subjects" :key="sub.id" :value="sub.id">
                {{ sub.subject_name }}
              </option>
            </select>
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-2">Exam Date</label>
            <input type="date" v-model="form.exam_date" class="form-input" />
          </div>

          <div>
            <label class="block text-gray-700 font-semibold mb-2">Total Marks</label>
            <input type="number" v-model="form.total_marks" class="form-input" />
          </div>

          <div class="mt-2">
            <button type="submit" class="submit-btn">
              {{ isEditing ? 'Update Exam' : 'Add Exam' }}
            </button>
          </div>
        </form>
      </div>

      <!-- 📋 Exam Records Table -->
      <div
        class="max-w-5xl mx-auto bg-white rounded-2xl shadow-md p-6 border border-gray-100"
      >
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-bold text-gray-700">Exam Records</h2>

        </div>

        <div class="overflow-x-auto">
         <table class="w-full border-collapse text-sm">
  <thead class="bg-blue-100 text-blue-700">
    <tr>
      <th class="p-3 text-left">#</th>
      <th class="p-3 text-left">Student</th>
      <th class="p-3 text-left">Class</th>
      <th class="p-3 text-left">Subject</th>
      <th class="p-3 text-left">Exam Date</th>
      <th class="p-3 text-left">Total Marks</th>
      <!-- 👇 Only show Actions column for admin/teacher -->
      <th
        v-if="role === 'admin' || role === 'teacher'"
        class="p-3 text-center"
      >
        Actions
      </th>
    </tr>
  </thead>

  <tbody>
    <tr
      v-for="(exam, index) in exams"
      :key="exam.id"
      class="border-t hover:bg-gray-50 transition"
    >
      <td class="p-3">{{ index + 1 }}</td>
      <td class="p-3">{{ exam.student.user.name }}</td>
      <td class="p-3">{{ exam.class_room.class_name }}</td>
      <td class="p-3">{{ exam.subject.subject_name }}</td>
      <td class="p-3">{{ exam.exam_date }}</td>
      <td class="p-3 font-semibold text-gray-700">{{ exam.total_marks }}</td>

      <!-- 👇 Only show Actions cell for admin/teacher -->
      <td
        v-if="role === 'admin' || role === 'teacher'"
        class="p-3 text-center"
      >
        <button @click="editExam(exam)" class="btn-edit">Edit</button>
        <Link
          :href="route('exams.destroy', exam.id)"
          method="delete"
          class="btn-delete"
        >
          Delete
        </Link>
      </td>
    </tr>

    <tr v-if="exams.length === 0">
      <!-- 👇 Match colspan based on role -->
      <td
        :colspan="role === 'admin' || role === 'teacher' ? 7 : 6"
        class="text-center text-gray-500 py-6 italic"
      >
        No exam records found.
      </td>
    </tr>
  </tbody>
</table>

        </div>
      </div>
    </div>
  </Test>
</template>

<style scoped>
.form-select,
.form-input {
  @apply w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 focus:outline-none;
}

.submit-btn {
  @apply bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow transition duration-300;
}

.btn-edit {
  @apply bg-yellow-400 hover:bg-yellow-500 text-white font-medium px-3 py-1 rounded-md mr-2 transition duration-300;
}

.btn-delete {
  @apply bg-red-500 hover:bg-red-600 text-white font-medium px-3 py-1 rounded-md transition duration-300;
}
</style>
