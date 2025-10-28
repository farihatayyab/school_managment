<template>
  <Test>
    <div class="page-wrapper">
      <div class="content-box">
        <h1 class="page-title">📢 Announcements</h1>

        <!-- ✅ Admin Add/Edit Form -->
        <div
          v-if="auth.user.role === 'admin'"
          class="form-container"
        >
          <h2 class="form-heading">
            {{ form.id ? "✏️ Update Announcement" : "➕ Add New Announcement" }}
          </h2>

          <form @submit.prevent="saveAnnouncement" class="form-grid">
            <input
              v-model="form.title"
              type="text"
              placeholder="Title"
              class="input-field"
              required
            />
            <textarea
              v-model="form.description"
              placeholder="Description"
              class="input-field"
              required
            ></textarea>
            <select v-model="form.status" class="input-field" required>
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>

            <div class="btn-wrapper">
              <button type="submit" class="main-btn">
                {{ form.id ? "Update" : "Add" }}
              </button>
            </div>
          </form>
        </div>

        <!-- ✅ Announcements Table -->
        <div class="table-container">
          <table class="data-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Title</th>
                <th>Description</th>
                <th>Date Posted</th>
                
                <th>Status</th>
                <th v-if="auth.user.role === 'admin'">Action</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="(a, i) in announcements"
                :key="a.id"
              >
                <td>{{ i + 1 }}</td>
                <td>{{ a.title }}</td>
                <td>{{ a.description }}</td>
                <td>{{ a.date_posted }}</td>
                
              <td>
  <span
    :class="a.status.toLowerCase() === 'active' ? 'text-green-600 font-semibold' : 'text-red-500 font-semibold'"
  >
    {{ a.status }}
  </span>
</td>

                <td v-if="auth.user.role === 'admin'" class="actions">
                  <button @click="editAnnouncement(a)" class="edit-btn">
                    Edit
                  </button>
                  <button @click="deleteAnnouncement(a.id)" class="delete-btn">
                    Delete
                  </button>
                </td>
              </tr>

              <tr v-if="announcements.length === 0">
                <td colspan="6" class="empty-row">
                  No announcements found.
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
import { ref } from "vue";
import axios from "axios";
import Test from "@/Layouts/Test.vue";

const props = defineProps({
  announcements: Array,
  auth: Object,
  
});

const form = ref({
  id: null,
  title: "",
  description: "",
  status: "active",
});

const announcements = ref(props.announcements || []);

// ✅ Add / Update
const saveAnnouncement = async () => {
  try {
    const payload = { ...form.value };

    let response;
    if (form.value.id) {
      response = await axios.put(`/announcements/${form.value.id}`, payload);
      const index = announcements.value.findIndex((a) => a.id === form.value.id);
      if (index !== -1) announcements.value[index] = response.data;
    } else {
      response = await axios.post("/announcements", payload);
      announcements.value.unshift(response.data);
    }

    // Reset form
    form.value = { id: null, title: "", description: "", status: "active" };
  } catch (err) {
    console.error(err);
    alert("Failed to save announcement.");
  }
};

// ✅ Edit
const editAnnouncement = (a) => {
  form.value = { ...a };
};

// ✅ Delete
const deleteAnnouncement = async (id) => {
  if (confirm("Are you sure you want to delete this announcement?")) {
    await axios.delete(`/announcements/${id}`);
    announcements.value = announcements.value.filter((a) => a.id !== id);
  }
};
</script>    

<style scoped>
.page-wrapper {
  @apply min-h-screen bg-gradient-to-br from-indigo-50 to-purple-100 py-10;
}
.content-box {
  @apply max-w-6xl mx-auto bg-white shadow-2xl rounded-2xl p-8;
}
.page-title {
  @apply text-4xl font-bold text-center text-purple-700 mb-8;
}
.form-container {
  @apply bg-purple-50 border border-purple-100 rounded-2xl p-6 shadow-md mb-10;
}
.form-heading {
  @apply text-2xl font-semibold text-purple-700 mb-5 text-center;
}
.form-grid {
  @apply grid grid-cols-1 md:grid-cols-2 gap-5;
}
.input-field {
  @apply w-full p-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-400 outline-none;
}
.btn-wrapper {
  @apply md:col-span-2 text-center;
}
.main-btn {
  @apply px-6 py-2 text-white font-semibold bg-purple-600 hover:bg-purple-700 rounded-xl transition;
}
.table-container {
  @apply overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-100;
}
.data-table {
  @apply w-full border-collapse text-center;
}
.data-table thead {
  @apply bg-purple-600 text-white;
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
