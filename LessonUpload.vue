<template>
  <!-- Lesson upload form for admin users -->
  <div class="lesson-upload card">
    <h2 class="card-title">Upload Lesson (Admin Only)</h2>
    
    <!-- Form for lesson title and content -->
    <form @submit.prevent="uploadLesson" class="space-y-4">
      <!-- Title input with validation -->
      <div class="form-group">
        <label for="title" class="label">Lesson Title</label>
        <input 
          id="title"
          v-model="title"
          type="text"          :class="['input', { 'input-error': titleError }]"
          placeholder="Enter lesson title"
          @input="validateTitle"
          required
        />
        <span v-if="titleError" class="error-text">{{ titleError }}</span>
      </div>

      <!-- Content input with validation -->
      <div class="form-group">
        <label for="content" class="label">Lesson Content</label>
        <textarea 
          id="content"
          v-model="content"          :class="['textarea', { 'textarea-error': contentError }]"
          placeholder="Enter lesson content (minimum 50 characters)"
          rows="10"
          @input="validateContent"
          required
        ></textarea>
        <span v-if="contentError" class="error-text">{{ contentError }}</span>
        <span class="text-sm text-gray-500">{{ characterCount }}/50 characters minimum</span>
      </div>

      <!-- Submit button -->
      <button 
        type="submit"
        :disabled="loading || hasErrors"        :class="['btn', { 'btn-disabled': loading || hasErrors }]"
      >
        <span v-if="loading">
          <span class="loading-spinner"></span>
          Uploading...
        </span>
        <span v-else>Upload Lesson</span>
      </button>
    </form>

    <!-- Success/Error messages -->
    <div v-if="message" class="alert alert-success">
      {{ message }}
    </div>
    <div v-if="error" class="alert alert-error">
      {{ error }}
    </div>
  </div>
</template>

<script setup>
// Import Vue's ref, computed for reactivity and API utility for HTTP requests
import { ref, computed } from 'vue'; // Import ref and computed from Vue for reactive state
import { useRouter } from 'vue-router'; // Import useRouter for navigation
import api from '../api'; // Import the API utility for HTTP requests

// Form state
const title = ref(''); // Holds the lesson title input
const content = ref(''); // Holds the lesson content input
const loading = ref(false); // Indicates if upload is in progress
const message = ref(''); // Holds the success message
const error = ref(''); // Holds the error message
const titleError = ref(''); // Holds the title validation error message
const contentError = ref(''); // Holds the content validation error message
const router = useRouter(); // Get router instance for navigation

// Computed properties
const characterCount = computed(() => content.value.length); // Compute the character count of content
const hasErrors = computed(() => titleError.value || contentError.value); // Check if there are validation errors

// Validation functions
const validateTitle = () => {
  titleError.value = ''; // Clear previous title error
  if (title.value.length < 3) {
    titleError.value = 'Title must be at least 3 characters'; // Set title error if invalid
  } else if (title.value.length > 255) {
    titleError.value = 'Title must be less than 255 characters'; // Set title error if invalid
  }
};

const validateContent = () => {
  contentError.value = ''; // Clear previous content error
  if (content.value.length < 50) {
    contentError.value = 'Content must be at least 50 characters'; // Set content error if invalid
  }
};

// Upload function
const uploadLesson = async () => {
  try {
    // Clear previous messages
    message.value = '';
    error.value = '';
    
    // Validate before submission
    validateTitle();
    validateContent();
    if (hasErrors.value) return; // Abort if there are validation errors

    loading.value = true; // Show loading indicator

    // Send request to backend
    const response = await api.post('/lessons', {
      title: title.value,
      content: content.value
    });

    // Handle success
    message.value = 'Lesson uploaded successfully!'; // Show success message
    
    // Reset form
    title.value = '';
    content.value = '';

    // Redirect to lesson view after short delay
    setTimeout(() => {
      router.push(`/lessons/${response.data.lesson.id}`);
    }, 1500);

  } catch (err) {
    // Handle different types of errors
    if (err.response?.status === 403) {
      error.value = 'You do not have permission to upload lessons'; // Handle permission error
    } else if (err.response?.data?.errors) {
      error.value = Object.values(err.response.data.errors).flat().join(', '); // Handle validation errors from server
    } else {
      error.value = 'Failed to upload lesson. Please try again.'; // Handle generic error
    }
  } finally {
    loading.value = false; // Hide loading indicator
  }
};
</script>

<style scoped>
.lesson-upload {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
}

.input, .textarea {
  width: 100%;
  padding: 0.5rem;
  margin-bottom: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
}

.input-error, .textarea-error {
  border-color: #dc2626;
}

.error-text {
  color: #dc2626;
  font-size: 0.875rem;
}

.btn {
  padding: 0.5rem 1rem;
  background-color: #2563eb;
  color: white;
  border-radius: 4px;
  cursor: pointer;
}

.btn-disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.loading-spinner {
  display: inline-block;
  width: 1rem;
  height: 1rem;
  border: 2px solid #fff;
  border-radius: 50%;
  border-top-color: transparent;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.alert {
  padding: 1rem;
  margin-top: 1rem;
  border-radius: 4px;
}

.alert-success {
  background-color: #d1fae5;
  color: #065f46;
}

.alert-error {
  background-color: #fee2e2;
  color: #991b1b;
}
</style>
