<template>
  <Test>
    <div class="page-wrapper">
      <div class="content-box">
        <h1 class="page-title">💰 Fees Management</h1>

        <!-- Admin Add/Edit Form -->
        <div v-if="auth.user.role === 'admin'" class="form-container">
          <h2 class="form-heading">{{ form.id ? "✏️ Update Fee" : "➕ Add New Fee" }}</h2>

          <form @submit.prevent="saveFee" class="form-grid">
            <!-- Student Dropdown -->
            <select v-model="form.student_id" class="input-field" required>
              <option value="">Select Student</option>
              <option v-for="s in students" :key="s.id" :value="s.id">
                {{ s.name }}
              </option>
            </select>

            <!-- Month Dropdown -->
            <select v-model="form.month" class="input-field" required>
              <option value="">Select Month</option>
              <option v-for="m in months" :key="m" :value="m">
                {{ m }}
              </option>
            </select>

            <input v-model="form.amount" type="number" placeholder="Amount" class="input-field" required />

            <select v-model="form.status" class="input-field">
              <option value="paid">Paid</option>
              <option value="unpaid">Unpaid</option>
            </select>

            <div class="btn-wrapper">
              <button type="submit" class="main-btn">{{ form.id ? "Update" : "Add" }}</button>
            </div>
          </form>
        </div>

        <!-- Fees Table -->
        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th >Student</th>
                <th>Month</th>
                <th>Amount</th>
                <th>Status</th>
                <th v-if="auth.user.role === 'admin'">Action</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="(f, i) in fees" :key="f.id">
                <td>{{ i + 1 }}</td>
                <td>{{ f.student?.name || auth.user.name }}</td>
                <td>{{ f.month }}</td>
                <td>₹{{ f.amount }}</td>
                <td>
                  <span :class="f.status === 'paid' ? 'text-green-600 font-semibold' : 'text-red-500 font-semibold'">
                    {{ f.status }}
                  </span>
                </td>
                <td v-if="auth.user.role === 'admin'" class="actions">
                  <button @click="editFee(f)" class="edit-btn">Edit</button>
                  <button @click="deleteFee(f.id)" class="delete-btn">Delete</button>
                </td>
              </tr>

              <tr v-if="fees.length === 0">
                <td colspan="6" class="empty-row">No fees records found.</td>
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
  fees: Array,
  auth: Object,
  students: Array,
});

const fees = ref(props.fees || []);
const months = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];

const form = ref({
  id: null,
  student_id: "",
  month: "",
  amount: "",
  status: "unpaid",
});

const saveFee = async () => {
  try {
    const payload = { ...form.value };
    let response;

    if (form.value.id) {
      response = await axios.put(`/fees/${form.value.id}`, payload);
      const index = fees.value.findIndex((f) => f.id === form.value.id);
      if (index !== -1) fees.value[index] = response.data;
    } else {
      response = await axios.post("/fees", payload);
      fees.value.unshift(response.data);
    }

    form.value = { id: null, student_id: "", month: "", amount: "", status: "unpaid" };
  } catch (err) {
    console.error(err);
    alert("Failed to save fee record.");
  }
};

const editFee = (f) => {
  form.value = { ...f };
};

const deleteFee = async (id) => {
  if (confirm("Are you sure you want to delete this fee record?")) {
    await axios.delete(`/fees/${id}`);
    fees.value = fees.value.filter((f) => f.id !== id);
  }
};
</script>

<style scoped>
.page-wrapper {
  @apply min-h-screen bg-gradient-to-br from-green-50 to-blue-100 py-10;
}
.content-box {
  @apply max-w-6xl mx-auto bg-white shadow-2xl rounded-2xl p-8;
}
.page-title {
  @apply text-4xl font-bold text-center text-green-700 mb-8;
}
.form-container {
  @apply bg-green-50 border border-green-100 rounded-2xl p-6 shadow-md mb-10;
}
.form-heading {
  @apply text-2xl font-semibold text-green-700 mb-5 text-center;
}
.input-field {
  @apply w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-400 outline-none;
}
.btn-wrapper {
  @apply md:col-span-2 text-center;
}
.main-btn {
  @apply px-6 py-2 text-white font-semibold bg-green-600 hover:bg-green-700 rounded-xl transition;
}
.table-container {
  @apply overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-100;
}
.data-table {
  @apply w-full border-collapse text-center;
}
.data-table thead {
  @apply bg-green-600 text-white;
}
.data-table th,
.data-table td {
  @apply py-3 px-4 border-b border-gray-100;
}
.actions {
  @apply flex justify-center gap-2;
}
.edit-btn {
  @apply bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded-md transition;
}
.delete-btn {
  @apply bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md transition;
}
.empty-row {
  @apply py-5 text-gray-500 italic;
}
</style>
