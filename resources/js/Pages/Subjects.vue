<template>
  <Test>
    <div class="page-wrapper">
      <div class="content-box">
        <h1 class="page-title">📚 Subject Management</h1>

        <!-- ✅ Add / Edit Form (Only Admin can Add/Edit All) -->
      <div v-if="role === 'admin' || (role === 'teacher' && editMode)" class="form-container">

          <h2 class="form-heading">
            {{ editMode ? "✏️ Update Subject" : "➕ Add New Subject" }}
          </h2>

          <form @submit.prevent="submit" class="form-grid">
            <input
              v-model="form.subject_name"
              type="text"
              placeholder="Enter Subject Name"
              class="input-field"
              required
            />

            <select v-model="form.class_id" class="input-field" required>
              <option disabled value="">Select Class</option>
              <option v-for="cls in classes" :key="cls.id" :value="cls.id">
                {{ cls.class_name }}
              </option>
            </select>

            <select v-model="form.teacher_id" class="input-field" required>
              <option disabled value="">Select Teacher</option>
              <option
                v-for="teacher in teachers"
                :key="teacher.id"
                :value="teacher.id"
              >
                {{ teacher.user?.name ?? "N/A" }}
              </option>
            </select>

            <div class="btn-wrapper">
              <button type="submit" class="main-btn">
                {{ editMode ? "Update Subject" : "Add Subject" }}
              </button>
            </div>
          </form>
        </div>

        <!-- ✅ Subjects Table -->
        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Subject</th>
                <th>Class</th>
                <th>Teacher</th>
                <th v-if="role !== 'student'">Action</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(subject, index) in subjects" :key="subject.id">
                <td>{{ index + 1 }}</td>
                <td>{{ subject.subject_name }}</td>
                <td>{{ subject.class_room.class_name }}</td>
                <td>{{ subject.teacher?.user?.name ?? "N/A" }}</td>

                <!-- ✅ Role Based Action Buttons -->
                <td v-if="role !== 'student'" class="actions">
                  <!-- Admin OR Teacher (own subject) can edit -->
                  <button
                    v-if="
                      role === 'admin' ||
                      (role === 'teacher' &&
                        subject.teacher?.user_id === $page.props.auth.user.id)
                    "
                    @click="edit(subject)"
                    class="edit-btn"
                  >
                    Edit
                  </button>

                  <!-- Only Admin can delete -->
                  <button
                    v-if="role === 'admin'"
                    @click="remove(subject.id)"
                    class="delete-btn"
                  >
                    Delete
                  </button>
                </td>
              </tr>

              <tr v-if="subjects.length === 0">
                <td colspan="5" class="empty-row">No subjects found.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </Test>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import Test from "@/Layouts/Test.vue";

const props = defineProps({
  subjects: Array,
  classes: Array,
  teachers: Array,
  role: String,
});

const form = useForm({
  subject_name: "",
  class_id: "",
  teacher_id: "",
});

const editMode = ref(false);
const editId = ref(null);

function submit() {
  if (editMode.value) {
    form.put(route("subjects.update", editId.value), {
      onSuccess: () => {
        form.reset();
        editMode.value = false;
      },
    });
  } else {
    form.post(route("subjects.store"), {
      onSuccess: () => form.reset(),
    });
  }
}

function edit(subject) {
  form.subject_name = subject.subject_name;
  form.class_id = subject.class_id;
  form.teacher_id = subject.teacher_id;
  editMode.value = true;
  editId.value = subject.id;
}

function remove(id) {
  if (confirm("Are you sure you want to delete this subject?")) {
    form.delete(route("subjects.destroy", id));
  }
}
</script>

<style scoped>
/* 🌈 Background and Wrapper */
.page-wrapper {
  @apply min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-10;
}

.content-box {
  @apply max-w-6xl mx-auto bg-white shadow-2xl rounded-2xl p-8;
}

/* 🏷️ Headings */
.page-title {
  @apply text-4xl font-extrabold text-center text-indigo-700 mb-8 drop-shadow-sm;
}

.form-heading {
  @apply text-2xl font-semibold text-indigo-700 mb-5 text-center;
}

/* 🧾 Form Section */
.form-container {
  @apply bg-indigo-50 border border-indigo-100 rounded-2xl p-6 shadow-md mb-10;
}

.form-grid {
  @apply grid grid-cols-1 md:grid-cols-3 gap-5;
}

.input-field {
  @apply w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-400 outline-none transition bg-white;
}

.btn-wrapper {
  @apply md:col-span-3 text-center;
}

.main-btn {
  @apply px-6 py-2 text-white font-semibold bg-indigo-600 hover:bg-indigo-700 rounded-xl transition transform hover:scale-105 shadow-md;
}

/* 📋 Table */
.table-container {
  @apply overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-100;
}

.data-table {
  @apply w-full border-collapse text-center;
}

.data-table thead {
  @apply bg-indigo-600 text-white;
}

.data-table th,
.data-table td {
  @apply py-3 px-4 border-b border-gray-100 text-gray-700;
}

.data-table tr:hover {
  @apply bg-indigo-50 transition duration-200;
}

.actions {
  @apply flex justify-center gap-2;
}

.edit-btn {
  @apply bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md transition transform hover:scale-105;
}

.delete-btn {
  @apply bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md transition transform hover:scale-105;
}

.empty-row {
  @apply py-5 text-gray-500 italic;
}
</style>
