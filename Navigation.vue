<!--
  Navigation.vue
  Main navigation bar for the EdTech frontend. Shows links based on authentication and user role.
-->
<template>
  <nav class="navbar">
    <div class="nav-brand">
      <router-link to="/" class="brand">EdTech Platform</router-link>
    </div>
    <div class="nav-links">
      <!-- Show these links only when authenticated -->
      <template v-if="auth.isAuthenticated()">
        <router-link to="/lessons" class="nav-link">Lessons</router-link>
        <template v-if="auth.isAdmin()">
          <router-link to="/admin/upload" class="nav-link">Upload Lesson</router-link>
        </template>
        <button @click="handleLogout" class="nav-link logout-btn">Logout</button>
      </template>
      <!-- Show these links when not authenticated -->
      <template v-else>
        <router-link to="/login" class="nav-link">Login</router-link>
        <router-link to="/register" class="nav-link register">Register</router-link>
      </template>
    </div>
  </nav>
</template>

<script setup>
import api, { auth } from '../api';
import { useRouter } from 'vue-router';

const router = useRouter();

// Handle user logout and redirect to login page
async function handleLogout() {
  try {
    await api.post('/logout');
    auth.clearAuth();
    router.push('/login');
  } catch (error) {
    console.error('Logout failed:', error);
  }
}
</script>

<style scoped>
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background: #fff;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.nav-brand {
  font-size: 1.5rem;
  font-weight: 600;
}

.brand {
  color: #1976d2;
  text-decoration: none;
}

.nav-links {
  display: flex;
  gap: 1.5rem;
  align-items: center;
}

.nav-link {
  color: #2c3e50;
  text-decoration: none;
  font-weight: 500;
  transition: color 0.2s;
}

.nav-link:hover {
  color: #1976d2;
}

.register {
  background: #1976d2;
  color: white;
  padding: 0.5rem 1rem;
  border-radius: 4px;
}

.register:hover {
  background: #1565c0;
  color: white;
}

.logout-btn {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1rem;
  padding: 0;
}
</style>
