<!--
  App.vue
  Root component for the EdTech frontend application.
  Renders the navigation bar and main content area using Vue Router.
-->
<script setup>
import Navigation from './components/Navigation.vue'
import { RouterView } from 'vue-router'
</script>

<template>
  <div class="app-container">
    <!-- Navigation bar at the top -->
    <Navigation />
    <!-- Main content rendered by Vue Router -->
    <main class="main-content">
      <RouterView />
    </main>
  </div>
</template>

<style scoped>
.app-container {
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

.main-content {
  flex: 1;
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
  width: 100%;
}
</style>