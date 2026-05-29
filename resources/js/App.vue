<template>
    <div class="min-h-screen bg-base-200 flex flex-col">
        <Navbar />
        <main class="flex-1 container mx-auto px-4 py-6">
            <router-view />
        </main>
        <Footer />
    </div>
</template>

<script setup>
import Navbar from './components/Navbar.vue'
import Footer from './components/Footer.vue'
</script>
