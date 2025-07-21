// api.js
// Axios instance and authentication state management for EdTech frontend.
// Handles API requests, token storage, and user role checks.

import axios from 'axios';

const api = axios.create({
  baseURL: 'http://localhost:8000/api', // Backend API base URL
});

// Auth state management
export const auth = {
  token: localStorage.getItem('token'), // JWT token from localStorage
  user: JSON.parse(localStorage.getItem('user')), // User info from localStorage
  
  // Set authentication state and persist to localStorage
  setAuth(token, user) {
    this.token = token;
    this.user = user;
    localStorage.setItem('token', token);
    localStorage.setItem('user', JSON.stringify(user));
  },
  
  // Clear authentication state and remove from localStorage
  clearAuth() {
    this.token = null;
    this.user = null;
    localStorage.removeItem('token');
    localStorage.removeItem('user');
  },
  
  // Check if user is authenticated
  isAuthenticated() {
    return !!this.token;
  },
  
  // Check if user is an admin
  isAdmin() {
    return this.user?.role === 'admin';
  }
};

// Request interceptor: attach token to headers if present
api.interceptors.request.use(
  (config) => {
    if (auth.token) {
      config.headers['Authorization'] = `Bearer ${auth.token}`;
    }
    return config;
  },
  (error) => Promise.reject(error)
);

// Response interceptor: handle 401 errors globally
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      auth.clearAuth();
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default api;
