<template>
  <Test>
    <div class="container">
      <h1 class="title">📘 Class Rooms</h1>

      <!-- ✅ Add / Update Class Form -->
      <transition name="fade-slide">
        <div
          v-if="userRole === 'admin' || (userRole === 'teacher' && form.id)"
          class="form-card"
        >
          <h2 class="form-title">{{ form.id ? '✏️ Update Class' : '➕ Add New Class' }}</h2>
          <form @submit.prevent="saveClass">
            <div class="form-group">
              <label>Class Name</label>
              <input
                v-model="form.class_name"
                type="text"
                placeholder="Enter class name"
                class="form-input"
                required
              />
            </div>

            <div class="form-group">
              <label>Description</label>
              <textarea
                v-model="form.description"
                placeholder="Write a short description..."
                class="form-input"
              ></textarea>
            </div>

            <button type="submit" class="form-button">
              {{ form.id ? 'Update Class' : 'Add Class' }}
            </button>
          </form>
        </div>
      </transition>

      <!-- ✅ Class Table -->
      <transition name="fade-slide">
        <div class="table-card">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th>Class Name</th>
                <th>Description</th>
                <th v-if="userRole !== 'student'">Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(cls, index) in classRooms"
                :key="cls.id"
                class="table-row"
              >
                <td>{{ index + 1 }}</td>
                <td class="class-name">{{ cls.class_name }}</td>
                <td>{{ cls.description || '-' }}</td>
                <td v-if="userRole !== 'student'">
                  <button
                    v-if="userRole === 'teacher' || userRole === 'admin'"
                    class="edit-btn"
                    @click="editClass(cls)"
                  >
                    ✏️ Edit
                  </button>
                  <button
                    v-if="userRole === 'admin'"
                    class="delete-btn"
                    @click="deleteClass(cls.id)"
                  >
                    🗑️ Delete
                  </button>
                </td>
              </tr>

              <tr v-if="classRooms.length === 0">
                <td colspan="4" class="empty">No classes added yet.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </transition>
    </div>
  </Test>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import Test from '@/Layouts/Test.vue'

const props = defineProps({
  classRooms: Array,
  auth: Object
})

const userRole = props.auth.user.role
const classRooms = ref(props.classRooms || [])
const form = ref({ id: null, class_name: '', description: '' })

const saveClass = async () => {
  try {
    if (form.value.id) {
      const res = await axios.put(`/class-rooms/${form.value.id}`, form.value)
      const index = classRooms.value.findIndex(c => c.id == form.value.id)
      if (index !== -1) classRooms.value[index] = res.data
    } else {
      const res = await axios.post('/class-rooms', form.value)
      classRooms.value.push(res.data)
    }
    form.value = { id: null, class_name: '', description: '' }
  } catch (err) {
    console.error(err)
  }
}

const editClass = (cls) => {
  form.value = { ...cls }
}

const deleteClass = async (id) => {
  if (confirm('Are you sure you want to delete this class?')) {
    await axios.delete(`/class-rooms/${id}`)
    classRooms.value = classRooms.value.filter(c => c.id !== id)
  }
}
</script>

<style scoped>
/* ===== Base Styling ===== */
.container {
  padding: 2.5rem;
  background: linear-gradient(135deg, #eef2ff 0%, #f9fafb 100%);
  min-height: 100vh;
  font-family: "Poppins", sans-serif;
}

/* ===== Title ===== */
.title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #1e3a8a;
  text-align: center;
  margin-bottom: 2.5rem;
  letter-spacing: 0.5px;
  position: relative;
}
.title::after {
  content: "";
  display: block;
  width: 90px;
  height: 5px;
  background: linear-gradient(90deg, #2563eb, #38bdf8);
  margin: 0.5rem auto 0;
  border-radius: 2px;
}

/* ===== Form Card ===== */
.form-card {
  background: #ffffff;
  padding: 2rem;
  border-radius: 1.25rem;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
  margin-bottom: 3rem;
  max-width: 650px;
  margin-left: auto;
  margin-right: auto;
  border: 1px solid #e5e7eb;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.form-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 25px rgba(59, 130, 246, 0.1);
}
.form-title {
  font-size: 1.4rem;
  font-weight: 600;
  color: #2563eb;
  text-align: center;
  margin-bottom: 1.5rem;
}
.form-group {
  margin-bottom: 1rem;
}
label {
  display: block;
  margin-bottom: 0.3rem;
  font-weight: 500;
  color: #374151;
}
.form-input {
  width: 100%;
  padding: 0.9rem;
  border-radius: 0.7rem;
  border: 1px solid #d1d5db;
  font-size: 1rem;
  transition: 0.3s;
  background-color: #f9fafb;
}
.form-input:focus {
  border-color: #3b82f6;
  background: #fff;
  box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
  outline: none;
}
.form-button {
  background: linear-gradient(90deg, #2563eb, #1e40af);
  color: white;
  width: 100%;
  padding: 0.9rem;
  border-radius: 0.7rem;
  border: none;
  font-weight: 600;
  cursor: pointer;
  letter-spacing: 0.5px;
  transition: all 0.3s ease;
}
.form-button:hover {
  transform: scale(1.03);
  box-shadow: 0 6px 16px rgba(37, 99, 235, 0.25);
}

/* ===== Table Card ===== */
.table-card {
  background: #ffffff;
  border-radius: 1.25rem;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
  overflow-x: auto;
  border: 1px solid #e5e7eb;
  max-width: 950px;
  margin: 0 auto;
  transition: 0.3s ease;
}
table {
  width: 100%;
  border-collapse: collapse;
}
thead {
  background: linear-gradient(90deg, #2563eb, #3b82f6);
  color: white;
}
th, td {
  text-align: left;
  padding: 1rem 1.2rem;
  font-size: 0.95rem;
}
th {
  letter-spacing: 0.4px;
}
tr:nth-child(even) {
  background-color: #f9fafb;
}
tr:hover {
  background-color: #eff6ff;
  transition: background 0.3s ease;
}
.class-name {
  font-weight: 600;
  color: #1d4ed8;
}

/* ===== Buttons ===== */
.edit-btn,
.delete-btn {
  padding: 0.45rem 0.85rem;
  border-radius: 0.6rem;
  font-size: 0.9rem;
  font-weight: 500;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
}
.edit-btn {
  background: #fcd34d;
  color: #78350f;
  margin-right: 0.4rem;
}
.edit-btn:hover {
  background: #fbbf24;
  transform: scale(1.05);
}
.delete-btn {
  background: #ef4444;
  color: white;
}
.delete-btn:hover {
  background: #dc2626;
  transform: scale(1.05);
}

/* ===== Empty ===== */
.empty {
  text-align: center;
  color: #9ca3af;
  padding: 1.5rem 0;
  font-style: italic;
}

/* ===== Animations ===== */
.fade-slide-enter-active {
  animation: fade-slide-in 0.5s ease forwards;
}
@keyframes fade-slide-in {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>
