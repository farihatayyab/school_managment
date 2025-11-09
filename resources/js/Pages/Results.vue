<script setup>
import { ref } from 'vue'
import axios from 'axios'
import Test from '@/Layouts/Test.vue'
import { usePage } from '@inertiajs/vue3'

const { props } = usePage()
const results = ref(props.results)
const students = ref(props.students)
const exams = ref(props.exams)
const role = props.role

// ✅ Form Data
const form = ref({
  id: null,
  student_id: '',
  exam_id: '',
  obtained_marks: ''
})


const saveResult = async () => {
  try {
    if (form.value.id) {
      // ✅ UPDATE
      const routeName = role === 'teacher' ? 'teacher.results.update' : 'results.update'
      const res = await axios.put(route(routeName, form.value.id), form.value)
      const index = results.value.findIndex(r => r.id === res.data.id)
      if (index !== -1) results.value[index] = res.data
      else results.value.push(res.data)
    } else {
      // ✅ ADD NEW
      const routeName = role === 'teacher' ? 'teacher.results.store' : 'results.store'
      const res = await axios.post(route(routeName), form.value)
      results.value.push(res.data)
    }

    // Reset form
    form.value = { id: null, student_id: '', exam_id: '', obtained_marks: '' }
  } catch (error) {
    if (error.response && error.response.data) {
      console.error('Validation / server error:', error.response.data)
      alert(error.response.data.message || 'Error saving result.')
    } else {
      console.error(error)
    }
  }
}

const editResult = (result) => {
  form.value = {
    id: result.id,
    student_id: result.student_id,
    exam_id: result.exam_id,
    obtained_marks: result.obtained_marks
  }

}

const deleteResult = async (id) => {
  if (confirm('Are you sure you want to delete this result?')) {
    try {
      await axios.delete(route('results.destroy', id))
      results.value = results.value.filter(r => r.id !== id)
     
    } catch (error) {
      console.error(error)
      alert('❌ Failed to delete result.')
    }
  }
}

</script>

<template>
  <Test>
    <div class="container">
      <h1 class="title">📊 Results Management</h1>

      <!-- ✅ Add / Update Result Form -->
      <transition name="fade-slide">
        <div
          v-if="role === 'admin' || role === 'teacher'"
          class="form-card"
        >
          <h2 class="form-title">{{ form.id ? '✏️ Update Result' : '➕ Add New Result' }}</h2>

          <form @submit.prevent="saveResult">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <div>
                <label>Student</label>
                <select v-model="form.student_id" class="form-input" required>
                  <option value="" disabled>Select Student</option>
                  <option v-for="s in students" :key="s.id" :value="s.id">
                    {{ s.user.name }}
                  </option>
                </select>
              </div>

              <div>
                <label>Exam</label>
                <select v-model="form.exam_id" class="form-input" required>
                  <option value="" disabled>Select Exam</option>
                  <option v-for="e in exams" :key="e.id" :value="e.id">
                    {{ e.subject.subject_name }} ({{ e.class_room.class_name }}) - {{ e.exam_date }}
                  </option>
                </select>
              </div>

              <div>
                <label>Obtained Marks</label>
                <input
                  v-model="form.obtained_marks"
                  type="number"
                  placeholder="Enter Marks"
                  class="form-input"
                  required
                />
              </div>
            </div>

            <button type="submit" class="form-button mt-4">
              {{ form.id ? 'Update Result' : 'Add Result' }}
            </button>
          </form>
        </div>
      </transition>

      <!-- ✅ Results Table -->
      <div class="table-card">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Student</th>
              <th>Subject</th>
              <th>Class</th>
              <th>Exam Date</th>
              <th>Total Marks</th>
              <th>Obtained Marks</th>
              <th v-if="role !== 'student'">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(r, i) in results"
              :key="r.id"
              class="table-row"
            >
                <td>{{ i + 1 }}</td>
                <td>{{ r.student?.user?.name || 'N/A' }}</td>
                <td>{{ r.exam?.subject?.subject_name || 'N/A' }}</td>
                <td>{{ r.exam?.class_room?.class_name || 'N/A' }}</td>
                <td>{{ r.exam?.exam_date || 'N/A' }}</td>
                <td>{{ r.exam?.total_marks || 'N/A' }}</td>
                <td>{{ r.obtained_marks || '0' }}</td>


              <td v-if="role !== 'student'">
                <button
                  v-if="role === 'teacher' || role === 'admin'"
                  class="edit-btn"
                  @click="editResult(r)"
                >
                  ✏️ Edit
                </button>
                <button
                  v-if="role === 'admin'"
                  class="delete-btn"
                  @click="deleteResult(r.id)"
                >
                  🗑️ Delete
                </button>
              </td>
            </tr>

            <tr v-if="results.length === 0">
              <td colspan="8" class="empty">No results added yet.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </Test>
</template>

<style scoped>
/* same styles from your class component for consistent design */
.container {
  padding: 2.5rem;
  background: linear-gradient(135deg, #eef2ff 0%, #f9fafb 100%);
  min-height: 100vh;
  font-family: "Poppins", sans-serif;
}
.title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #1e3a8a;
  text-align: center;
  margin-bottom: 2.5rem;
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
.form-card {
  background: #ffffff;
  padding: 2rem;
  border-radius: 1.25rem;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
  margin-bottom: 3rem;
  max-width: 800px;
  margin-left: auto;
  margin-right: auto;
  border: 1px solid #e5e7eb;
}
.form-title {
  font-size: 1.4rem;
  font-weight: 600;
  color: #2563eb;
  text-align: center;
  margin-bottom: 1.5rem;
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
.table-card {
  background: #ffffff;
  border-radius: 1.25rem;
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.05);
  overflow-x: auto;
  border: 1px solid #e5e7eb;
  max-width: 950px;
  margin: 0 auto;
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
tr:nth-child(even) {
  background-color: #f9fafb;
}
tr:hover {
  background-color: #eff6ff;
}
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
.delete-btn {
  background: #ef4444;
  color: white;
}
.empty {
  text-align: center;
  color: #9ca3af;
  padding: 1.5rem 0;
  font-style: italic;
}
</style>
