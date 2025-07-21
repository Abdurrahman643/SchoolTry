// Importing the main CSS file for the application
import './assets/main.css'

// Import Vue, App component, and router
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

// Create Vue app, use router, and mount to #app
createApp(App).use(router).mount('#app')
