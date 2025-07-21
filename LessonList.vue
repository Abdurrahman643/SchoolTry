<template>
  <div class="lesson-list">
    <h2>Lessons</h2>
    <!-- Show error if lessons fail to load -->
    <div v-if="error" class="error">{{ error }}</div>
    <!-- Show loading indicator while fetching lessons -->
    <div v-if="loadingLessons" class="loading">Loading lessons...</div>
    <!-- List lessons when loaded -->
    <ul v-if="!loadingLessons">
      <li v-for="lesson in lessons" :key="lesson.id">
        <button @click="selectLesson(lesson)">{{ lesson.title }}</button>
      </li>
    </ul>
    <!-- Pagination controls -->
    <div class="pagination" v-if="!loadingLessons">
      <button @click="fetchLessons(meta.current_page - 1)" :disabled="meta.current_page === 1">Prev</button>
      <span>Page {{ meta.current_page }} of {{ meta.last_page }}</span>
      <button @click="fetchLessons(meta.current_page + 1)" :disabled="meta.current_page === meta.last_page">Next</button>
    </div>
    <!-- Show selected lesson details and Q&A -->
    <div v-if="selectedLesson">
      <h3>{{ selectedLesson.title }}</h3>
      <p>{{ selectedLesson.content }}</p>
      <LessonQA :lesson="selectedLesson" />
      <div v-if="lessonSuccess" class="success">Lesson loaded successfully!</div>
    </div>
  </div>
</template>

<script setup>
// Import Vue composition API and API utility
import { ref, onMounted } from 'vue';
import api from '../api';
import LessonQA from './LessonQA.vue';

// State variables
const lessons = ref([]); // Lessons list
const meta = ref({ current_page: 1, last_page: 1 }); // Pagination info
const error = ref(''); // Error message
const selectedLesson = ref(null); // Selected lesson
const loadingLessons = ref(false); // Loading state
const lessonSuccess = ref(false); // Success notification

const userRole = ref(''); // User role ('admin' or 'student')
const isLoggedIn = ref(false); // Auth status

// Fetch lessons from backend (paginated)
async function fetchLessons(page = 1) {
  error.value = '';
  loadingLessons.value = true;
  try {
    const res = await api.get(`/lessons?per_page=10&page=${page}`);
    lessons.value = res.data.data;
    meta.value = res.data.meta;
  } catch (e) {
    error.value = 'Failed to load lessons';
  } finally {
    loadingLessons.value = false;
  }
}

// Select a lesson and show notification
function selectLesson(lesson) {
  selectedLesson.value = lesson;
  lessonSuccess.value = true;
  setTimeout(() => { lessonSuccess.value = false; }, 2000);
}

// Fetch user info for role and authentication
async function fetchUser() {
  try {
    const res = await api.get('/me');
    userRole.value = res.data.role;
    isLoggedIn.value = true;
  } catch (e) {
    userRole.value = '';
    isLoggedIn.value = false;
    window.location.href = '/login'; // Redirect if not authenticated
  }
}

// On component mount, fetch user and lessons
onMounted(() => {
  fetchUser();
  fetchLessons();
});
</script>

<style scoped>
.lesson-list { max-width: 600px; margin: 2rem auto; }
ul { list-style: none; padding: 0; }
li { margin-bottom: 0.5rem; }
button { padding: 0.5rem 1rem; margin-right: 1rem; }
.pagination { margin: 1rem 0; }
.error { color: red; }
.success { color: green; margin-top: 1rem; }
.loading { color: #555; margin: 1rem 0; }
</style>
