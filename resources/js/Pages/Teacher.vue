<template>
  <Test>
    <div class="page-wrapper">
      <div class="content-box">
        <h1 class="page-title">🧑‍🏫 Teacher Management</h1>

        <!-- ✅ Add / Edit Form -->
        <div
          v-if="userRole === 'admin' || userRole === 'teacher'"
          class="form-container"
        >
          <h2 class="form-heading">
            {{
              form.id
                ? userRole === 'teacher'
                  ? '✏️ Update Your Profile'
                  : '✏️ Update Teacher'
                : userRole === 'admin'
                ? '➕ Add New Teacher'
                : ''
            }}
          </h2>

          <!-- ✅ Form visibility logic -->
          <form
            v-if="
              (userRole === 'admin') ||
              (userRole === 'teacher' && form.id)
            "
            @submit.prevent="saveTeacher"
            class="form-grid"
          >
            <!-- 🔹 Select User (Only Admin can select) -->
            <select
              v-if="userRole === 'admin'"
              v-model="form.user_id"
              required
              class="input-field"
            >
              <option value="">Select Teacher </option>
              <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }}
              </option>
            </select>

            <!-- 🔹 Phone -->
            <input
              v-model="form.phone"
              type="text"
              placeholder="Phone Number"
              class="input-field"
              required
            />

            <!-- 🔹 Subject Specialization -->
            <input
              v-model="form.subject_specialization"
              type="text"
              placeholder="Subject Specialization"
              class="input-field"
              required
            />

            <!-- 🔹 Salary (Only Admin can edit salary) -->
            <input
              v-model="form.salary"
              type="number"
              placeholder="Salary"
              class="input-field"
              :readonly="userRole !== 'admin'"
              required
              min="0"
            />

            <div class="btn-wrapper">
              <button type="submit" class="main-btn">
                {{
                  form.id
                    ? userRole === 'teacher'
                      ? 'Update Profile'
                      : 'Update Teacher'
                    : 'Add Teacher'
                }}
              </button>
            </div>
          </form>
        </div>

        <!-- ✅ Teachers Table -->
        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Subject Specialization</th>
                <th>Salary</th>
                <th v-if="userRole !== 'student'">Action</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(teacher, i) in teachers" :key="teacher.id">
                <td>{{ i + 1 }}</td>
                <td>{{ teacher.user?.name }}</td>
                <td>{{ teacher.phone }}</td>
                <td>{{ teacher.subject_specialization }}</td>
                <td>{{ teacher.salary }}</td>

                <td v-if="userRole !== 'student'" class="actions">
                  <!-- Admin aur apna teacher khud edit kar sakta -->
                  <button
                    v-if="
                      userRole === 'admin' ||
                      (userRole === 'teacher' &&
                        teacher.user_id === auth.user.id)
                    "
                    @click="editTeacher(teacher)"
                    class="edit-btn"
                  >
                    Edit
                  </button>

                  <!-- Sirf Admin delete kar sakta -->
                  <button
                    v-if="userRole === 'admin'"
                    @click="deleteTeacher(teacher.id)"
                    class="delete-btn"
                  >
                    Delete
                  </button>
                </td>
              </tr>

              <tr v-if="teachers.length === 0">
                <td colspan="6" class="empty-row">No teachers found.</td>
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
import axios from "axios";
import Test from "@/Layouts/Test.vue";

const props = defineProps({
  teachers: Array,
  users: Array,
  auth: Object,
});

const userRole = props.auth.user.role;
const teachers = ref(props.teachers || []);

// 🔹 Reactive form
const form = ref({
  id: null,
  user_id: userRole === "teacher" ? props.auth.user.id : "",
  phone: "",
  subject_specialization: "",
  salary: "",
});

// 🔹 Save (Add / Update)
const saveTeacher = async () => {
  try {
    if (!/^\d+$/.test(form.value.phone)) {
      alert("❌ Phone number must contain digits only.");
      return;
    }

    const payload = {
      user_id: form.value.user_id,
      phone: form.value.phone,
      subject_specialization: form.value.subject_specialization,
      salary: form.value.salary,
    };

    let response;
    if (form.value.id) {
      // Update
      response = await axios.put(`/teachers/${form.value.id}`, payload);
      const index = teachers.value.findIndex((t) => t.id === form.value.id);
      if (index !== -1) teachers.value[index] = response.data;
    } else if (userRole === "admin") {
      // Only Admin can Add
      response = await axios.post("/teachers", payload);
      teachers.value.push(response.data);
    }

    // Reset form
    form.value = {
      id: null,
      user_id: userRole === "teacher" ? props.auth.user.id : "",
      phone: "",
      subject_specialization: "",
      salary: "",
    };
  } catch (error) {
    console.error("Error saving teacher:", error);
    alert("Failed to save teacher. Check console for details.");
  }
};

// 🔹 Edit
const editTeacher = (teacher) => {
  form.value = {
    id: teacher.id,
    user_id: teacher.user_id,
    phone: teacher.phone,
    subject_specialization: teacher.subject_specialization,
    salary: teacher.salary,
  };
};

// 🔹 Delete
const deleteTeacher = async (id) => {
  if (confirm("Are you sure you want to delete this teacher?")) {
    await axios.delete(`/teachers/${id}`);
    teachers.value = teachers.value.filter((t) => t.id !== id);
  }
};
</script>


<style scoped>
.page-wrapper {
  @apply min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-10;
}

.content-box {
  @apply max-w-6xl mx-auto bg-white shadow-2xl rounded-2xl p-8;
}

.page-title {
  @apply text-4xl font-extrabold text-center text-indigo-700 mb-8 drop-shadow-sm;
}

.form-container {
  @apply bg-indigo-50 border border-indigo-100 rounded-2xl p-6 shadow-md mb-10;
}

.form-heading {
  @apply text-2xl font-semibold text-indigo-700 mb-5 text-center;
}

.form-grid {
  @apply grid grid-cols-1 md:grid-cols-2 gap-5;
}

.input-field {
  @apply w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-400 outline-none transition bg-white;
}

.btn-wrapper {
  @apply md:col-span-2 text-center;
}

.main-btn {
  @apply px-6 py-2 text-white font-semibold bg-indigo-600 hover:bg-indigo-700 rounded-xl transition transform hover:scale-105 shadow-md;
}

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
