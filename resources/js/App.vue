<template>
  <div id="app" class="min-h-screen bg-gradient-to-br from-purple-50 to-blue-50">
    <!-- Navbar -->
    <nav class="navbar bg-base-100 shadow-lg">
      <div class="flex-1">
        <a class="btn btn-ghost text-xl">Laravel FrankenPHP</a>
      </div>
      <div class="flex-none gap-2">
        <div class="form-control">
          <input
            type="text"
            placeholder="Search"
            class="input input-bordered w-24 md:w-auto"
          />
        </div>
        <div class="dropdown dropdown-end">
          <button class="btn btn-ghost btn-circle avatar">
            <div class="w-10 rounded-full bg-primary text-white flex items-center justify-center">
              L
            </div>
          </button>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero min-h-screen bg-base-200">
      <div class="hero-content text-center">
        <div class="max-w-md">
          <h1 class="text-5xl font-bold">Welcome to</h1>
          <p class="text-3xl font-bold text-primary mt-4">Laravel FrankenPHP</p>
          <p class="py-6 text-lg">
            Modern PHP runtime with Vue 3, Tailwind CSS & Daisy UI
          </p>

          <!-- Tech Stack Cards -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
            <div class="card bg-base-100 shadow-md">
              <div class="card-body">
                <h2 class="card-title text-sm">🚀 FrankenPHP</h2>
                <p class="text-xs">Modern PHP runtime</p>
              </div>
            </div>
            <div class="card bg-base-100 shadow-md">
              <div class="card-body">
                <h2 class="card-title text-sm">⚡ Vue 3</h2>
                <p class="text-xs">Progressive JS framework</p>
              </div>
            </div>
            <div class="card bg-base-100 shadow-md">
              <div class="card-body">
                <h2 class="card-title text-sm">🎨 Tailwind</h2>
                <p class="text-xs">Utility-first CSS</p>
              </div>
            </div>
          </div>

          <button class="btn btn-primary mt-8" @click="showAlert = true">
            Get Started
          </button>
        </div>
      </div>
    </div>

    <!-- Alert -->
    <div v-if="showAlert" class="toast toast-center">
      <div class="alert alert-success">
        <span>Welcome! This is a demo alert.</span>
        <button class="btn btn-sm" @click="showAlert = false">Close</button>
      </div>
    </div>

    <!-- Footer -->
    <footer class="footer footer-center bg-base-300 text-base-content p-4 mt-8">
      <div>
        <p>Copyright © 2025 Laravel FrankenPHP Docker Demo</p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const showAlert = ref(false);
</script>

<style scoped>
/* Component specific styles */
</style>
