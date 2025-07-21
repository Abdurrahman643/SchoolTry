<template>
  <!-- Q&A and AI Chat UI for a lesson -->
  <div class="qa-chat card">
    <h4 class="card-title">Ask a Question About This Lesson</h4>
    <!-- Form for asking a question about the lesson -->
    <form @submit.prevent="askQuestion">
      <!-- Input for the student's question -->
      <input v-model="question" type="text" placeholder="Type your question..." required class="input" />
      <!-- Ask button, disabled while loading -->
      <button type="submit" :disabled="loading" class="btn">Ask</button>
    </form>
    <!-- Loading indicator while getting AI answer -->
    <div v-if="loading" class="loading">Getting answer...</div>
    <!-- Display the AI's answer -->
    <div v-if="aiAnswer" class="ai-answer">
      <strong>AI Answer:</strong> {{ aiAnswer }}
      <!-- Button to save Q&A to history, disabled while saving -->
      <button @click="saveQA" :disabled="saving" class="btn">Save Q&A</button>
    </div>
    <!-- Success message after saving Q&A -->
    <div v-if="saveMessage" class="success alert">{{ saveMessage }}</div>
    <!-- Recommended lessons based on the question -->
    <div v-if="recommendations.length">
      <h5>Recommended Lessons:</h5>
      <ul>
        <li v-for="rec in recommendations" :key="rec.id">{{ rec.title }}</li>
      </ul>
    </div>
    <!-- Q&A history for this lesson -->
    <h4>Q&A History</h4>
    <ul>
      <li v-for="qa in qaHistory" :key="qa.id">
        <strong>Q:</strong> {{ qa.question }}<br />
        <strong>A:</strong> {{ qa.answer }}
      </li>
    </ul>
    <!-- Pagination controls for Q&A history -->
    <div class="pagination">
      <button @click="fetchHistory(meta.current_page - 1)" :disabled="meta.current_page === 1">Prev</button>
      <span>Page {{ meta.current_page }} of {{ meta.last_page }}</span>
      <button @click="fetchHistory(meta.current_page + 1)" :disabled="meta.current_page === meta.last_page">Next</button>
    </div>
    <!-- Error message for any Q&A/AI/recommendation error -->
    <div v-if="error" class="error alert">{{ error }}</div>
  </div>
</template>

<script setup>
// Import Vue's ref, watch, and onMounted for reactivity and lifecycle
import { ref, watch, onMounted } from 'vue'; // Import composition API
import api from '../api'; // Import the API utility for HTTP requests

// Props: lesson object (must have id, title, content)
const props = defineProps({ lesson: Object }); // Receive lesson as a prop

// State for question, answer, Q&A history, recommendations, etc.
const question = ref(''); // Holds the student's question
const aiAnswer = ref(''); // Holds the AI's answer
const loading = ref(false); // Indicates if AI answer is loading
const saving = ref(false); // Indicates if Q&A is being saved
const saveMessage = ref(''); // Success message for saving Q&A
const error = ref(''); // Error message for any failure
const qaHistory = ref([]); // List of Q&A history for the lesson
const meta = ref({ current_page: 1, last_page: 1 }); // Pagination meta for Q&A history
const recommendations = ref([]); // List of recommended lessons

// Ask AI a question about the lesson
async function askQuestion() {
  aiAnswer.value = ''; // Clear previous answer
  error.value = ''; // Clear previous error
  recommendations.value = []; // Clear previous recommendations
  loading.value = true; // Show loading indicator
  try {
    // Call AI answer endpoint with question and lesson content
    const res = await api.post('/ai/answer', {
      question: question.value,
      lesson: props.lesson.content,
    });
    aiAnswer.value = res.data.answer; // Set AI answer
    // Bonus: get recommendations based on question
    const recRes = await api.post('/ai/recommend', { question: question.value });
    recommendations.value = recRes.data.recommendations || []; // Set recommendations
  } catch (e) {
    // Show error message from backend or fallback
    error.value = e.response?.data?.message || 'Failed to get AI answer';
  } finally {
    loading.value = false; // Hide loading indicator
  }
}

// Save Q&A to history
async function saveQA() {
  saving.value = true; // Show saving indicator
  saveMessage.value = ''; // Clear previous success message
  error.value = '';
  try {
    // Call backend to save Q&A
    await api.post('/questions', {
      lesson_id: props.lesson.id,
      question: question.value,
      answer: aiAnswer.value,
    });
    saveMessage.value = 'Q&A saved!'; // Show success message
    fetchHistory(); // Refresh Q&A history
  } catch (e) {
    // Show error message from backend or fallback
    error.value = e.response?.data?.message || 'Failed to save Q&A';
  } finally {
    saving.value = false; // Hide saving indicator
  }
}

// Fetch Q&A history for this lesson
async function fetchHistory(page = 1) {
  error.value = '';
  try {
    // Call backend to get Q&A history for the lesson
    const res = await api.get(`/lessons/${props.lesson.id}/questions?per_page=5&page=${page}`);
    qaHistory.value = res.data.data; // Set Q&A history
    meta.value = res.data.meta; // Set pagination meta
  } catch (e) {
    error.value = 'Failed to load Q&A history'; // Show error message
  }
}

// Watch for lesson change to refresh history
watch(() => props.lesson?.id, (id) => {
  if (id) fetchHistory(); // Fetch history when lesson changes
});

// On mount, fetch history if lesson is present
onMounted(() => {
  if (props.lesson?.id) fetchHistory(); // Fetch history on mount
});
</script>

<style scoped>
.qa-chat.card {
  margin-top: 2rem;
  border-top: 1px solid #eee;
  padding-top: 1rem;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 16px 0 rgba(60,60,60,0.08);
  border: 1px solid #e0e0e0;
}
.card-title {
  text-align: center;
  margin-bottom: 1.5rem;
  color: #2c3e50;
  font-weight: 600;
  font-size: 1.3rem;
}
.input {
  width: 70%;
  padding: 0.5rem;
  margin-right: 0.5rem;
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
  padding: 0.5rem 1rem;
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
.success.alert {
  color: #155724;
  background: #d4edda;
  border: 1px solid #c3e6cb;
  border-radius: 4px;
  padding: 0.7rem 1rem;
  margin-top: 1rem;
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
.ai-answer {
  margin: 1rem 0;
  background: #f6f6ff;
  padding: 1rem;
  border-radius: 6px;
}
.pagination {
  margin: 1rem 0;
}
ul { list-style: none; padding: 0; }
li { margin-bottom: 1rem; }
</style>
