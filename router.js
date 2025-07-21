// router.js
// Vue Router configuration for EdTech frontend application.
// Defines routes for login, registration, lesson upload, and lesson list.

// Import Vue Router and page components
import { createRouter, createWebHistory } from 'vue-router';
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import LessonUpload from './components/LessonUpload.vue';
import LessonList from './components/LessonList.vue';

// Define application routes
const routes = [
  { path: '/login', component: Login }, // Login page
  { path: '/register', component: Register }, // Register page
  { path: '/admin/upload', component: LessonUpload }, // Admin lesson upload page
  { path: '/', component: LessonList }, // Lesson list (home)
];

// Create and export Vue Router instance
const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
