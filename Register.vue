<template>
  <!-- Registration form UI -->
  <div class="auth-container card">
    <h2 class="card-title">Register</h2>
    <!-- Registration form with name, email, password, and role fields -->
    <form @submit.prevent="register">
      <!-- Name input, bound to name state -->
      <input v-model="name" type="text" placeholder="Name" required class="input" />
      <!-- Email input, bound to email state -->
      <input v-model="email" type="email" placeholder="Email" required class="input" />
      <!-- Password input, bound to password state -->
      <input v-model="password" type="password" placeholder="Password" required class="input" />
      <!-- Role select, bound to role state -->
      <select v-model="role" required class="input">
        <option value="student">Student</option>
        <option value="admin">Admin</option>
      </select>
      <!-- Register button, disabled while loading -->
      <button type="submit" :disabled="loading" class="btn">Register</button>
    </form>
    <!-- Error message if registration fails -->
    <div v-if="error" class="error alert">{{ error }}</div>
    <!-- Loading indicator while registering -->
    <div v-if="loading" class="loading">Registering...</div>
  </div>
</template>

<script setup>
// Import Vue's ref for reactivity and API utility for HTTP requests
import { ref } from 'vue'; // Import ref from Vue for reactive state
import api from '../api'; // Import the API utility for HTTP requests

// State for form fields, error message, and loading indicator
const name = ref(''); // Holds the name input
const email = ref(''); // Holds the email input
const password = ref(''); // Holds the password input
const role = ref('student'); // Holds the selected role
const error = ref(''); // Holds the error message
const loading = ref(false); // Indicates if registration is in progress

// Register function: calls backend to create a new user
async function register() {
  error.value = ''; // Clear previous error message
  loading.value = true; // Show loading indicator
  try {
    // Call backend register endpoint with name, email, password, and role
    // Ensure the correct base URL is used for the backend API
    const res = await api.post('/register', { name: name.value, email: email.value, password: password.value, role: role.value });
    // The backend returns 'access_token', not 'token'. Store the correct key.
    localStorage.setItem('token', res.data.access_token); // Store token in localStorage
    window.location.reload(); // Reload or redirect after registration
  } catch (e) {
    // Show detailed error message from backend or fallback
    if (e.response && e.response.data) {
      error.value = JSON.stringify(e.response.data);
    } else {
      error.value = e.message || 'Registration failed';
    }
  } finally {
    loading.value = false; // Hide loading indicator
  }
}
</script>

<style scoped>
.auth-container.card {
  max-width: 400px;
  margin: 2rem auto;
  padding: 2rem;
  border-radius: 12px;
  background: #fff;
  box-shadow: 0 2px 16px 0 rgba(60,60,60,0.08);
  border: 1px solid #e0e0e0;
}
.card-title {
  text-align: center;
  margin-bottom: 1.5rem;
  color: #2c3e50;
  font-weight: 600;
  font-size: 1.6rem;
}
.input {
  display: block;
  width: 100%;
  margin-bottom: 1rem;
  padding: 0.7rem 1rem;
  border: 1px solid #d0d0d0;
  border-radius: 6px;
  font-size: 1rem;
  background: #fafbfc;
  transition: border 0.2s;
}
.input:focus {
  border: 1.5px solid #1976d2;
  outline: none;
  background: #fff;
}
.btn {
  width: 100%;
  padding: 0.7rem;
  background: linear-gradient(90deg, #1976d2 60%, #42a5f5 100%);
  color: #fff;
  border: none;
  border-radius: 6px;
  font-size: 1.1rem;
  font-weight: 500;
  cursor: pointer;
  transition: background 0.2s;
}
.btn:disabled {
  background: #b0bec5;
  cursor: not-allowed;
}
.error.alert {
  color: #721c24;
  background: #f8d7da;
  border: 1px solid #f5c6cb;
  border-radius: 4px;
  padding: 0.7rem 1rem;
  margin-top: 1rem;
}
.loading {
  color: #1976d2;
  margin-top: 1rem;
  text-align: center;
  font-weight: 500;
}
</style>
