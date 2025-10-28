<template>
  <Test>
    <div class="page-wrapper">
      <div class="content-box">
        <h1 class="page-title">🎓 Student Management</h1>

        <!-- ✅ Add / Edit Form (Admin + Teacher only) -->
        <div
          v-if="userRole === 'admin' || userRole === 'teacher'"
          class="form-container"
        >
          <h2 class="form-heading">
            {{ form.id ? '✏️ Update Student' : '➕ Add New Student' }}
          </h2>

          <form @submit.prevent="saveStudent" class="form-grid">
            <!-- 🔹 User select (Admin/Teacher) -->
            <div>
              <select
                v-model="form.user_id"
                required
                class="input-field"
                @change="updateUserName"
              >
                <option value="">Select Student </option>
                <option
                  v-for="user in users"
                  :key="user.id"
                  :value="user.id"
                >
                  {{ user.name }}
                </option>
              </select>
            </div>

            <!-- 🔹 Student Name -->
            <input
              v-model="form.student_name"
              type="text"
              placeholder="Student Name"
              class="input-field"
            />

            <!-- 🔹 Class Dropdown -->
            <select v-model="form.class_id" required class="input-field">
              <option value="">Select Class</option>
              <option
                v-for="cls in classes"
                :key="cls.id"
                :value="cls.id"
              >
                {{ cls.class_name }}
              </option>
            </select>

            <!-- 🔹 Roll Number -->
            <input
              v-model="form.roll_no"
              type="text"
              placeholder="Roll Number"
              class="input-field"
              required
            />

            <!-- 🔹 Gender -->
          
          <select v-model="form.gender" required class="input-field">
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
              </select>
         

            <!-- 🔹 Address -->
            <input
              v-model="form.address"
              type="text"
              placeholder="Address"
              class="input-field"
            />

            <!-- 🔹 Phone -->
            <input
              v-model="form.phone"
              type="text"
              placeholder="Phone"
              class="input-field"
            />

            <div class="btn-wrapper">
              <button type="submit" class="main-btn">
                {{ form.id ? 'Update Student' : 'Add Student' }}
              </button>
            </div>
          </form>
        </div>

        <!-- ✅ Students Table (Visible to All) -->
        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Roll No</th>
                <th>Gender</th>
                <th>Class Name</th>
                <th>Phone</th>
                <th>Address</th>
                <!-- 👇 Hide Action column for student -->
                <th v-if="userRole !== 'student'">Action</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(student, i) in students" :key="student.id">
                <td>{{ i + 1 }}</td>
                <td>{{ student.user?.name || form.student_name }}</td>
                <td>{{ student.roll_no }}</td>
                <td>{{ student.gender }}</td>
               <td>{{ student.class_room.class_name }}</td>

                <td>{{ student.phone || '-' }}</td>
                <td>{{ student.address || '-' }}</td>

                <!-- 👇 Show edit/delete based on role -->
                <td v-if="userRole !== 'student'" class="actions">
                  <button
                    v-if="userRole === 'admin' || userRole === 'teacher'"
                    @click="editStudent(student)"
                    class="edit-btn"
                  >
                    Edit
                  </button>
                  <button
                    v-if="userRole === 'admin'"
                    @click="deleteStudent(student.id)"
                    class="delete-btn"
                  >
                    Delete
                  </button>
                </td>
              </tr>

              <tr v-if="students.length === 0">
                <td
                  :colspan="userRole === 'student' ? 7 : 8"
                  class="empty-row"
                >
                  No students found.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </Test>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Test from "@/Layouts/Test.vue";

const props = defineProps({
  students: Array,
  auth: Object,
  users: Array,
  classes: Array,
});

const userRole = props.auth.user.role;
const students = ref(props.students || []);
const form = ref({
  id: null,
  user_id: "",
  student_name: "",
  class_id: "",
  roll_no: "",
  gender: "",
  address: "",
  phone: "",
});

onMounted(() => {
  console.log("Students data:", JSON.stringify(students.value, null, 2));
});
// 🔹 Set auto user name if teacher selects student
const updateUserName = () => {
  const selectedUser = props.users.find((u) => u.id === form.value.user_id);
  if (selectedUser) form.value.student_name = selectedUser.name;
};

// 🔹 Save (Add / Update)
const saveStudent = async () => {
  try {
    let response;

    if (form.value.id) {
      response = await axios.put(`/students/${form.value.id}`, form.value);
      const index = students.value.findIndex(
        (s) => s.id === form.value.id
      );
      if (index !== -1) students.value[index] = response.data;
    } else {
      response = await axios.post("/students", form.value);
      students.value.push(response.data);
    }

    form.value = {
      id: null,
      user_id: "",
      student_name: "",
      class_id: "",
      roll_no: "",
      gender: "",
      address: "",
      phone: "",
    };
  } catch (error) {
    console.error("Error saving student:", error);
  }
};

// 🔹 Edit
const editStudent = (student) => {
  form.value = {
    id: student.id,
    user_id: student.user_id,
    student_name: student.user?.name || "",
    class_id: student.class_id,
    roll_no: student.roll_no,
    gender: student.gender,
    address: student.address,
    phone: student.phone,
  };
};

// 🔹 Delete
const deleteStudent = async (id) => {
  if (confirm("Delete this student?")) {
    await axios.delete(`/students/${id}`);
    students.value = students.value.filter((s) => s.id !== id);
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
