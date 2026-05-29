<template>
    <nav class="navbar bg-base-100 shadow-lg">
        <div class="navbar-start">
            <router-link to="/" class="btn btn-ghost text-xl">
                🛒 Laravel Store
            </router-link>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><router-link to="/">Home</router-link></li>
                <li><router-link to="/products">Products</router-link></li>
                <li><router-link to="/orders">Orders</router-link></li>
            </ul>
        </div>
        <div class="navbar-end">
            <router-link to="/cart" class="btn btn-ghost btn-circle indicator">
                <span class="indicator-item badge badge-primary badge-sm" v-if="cartCount">{{ cartCount }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z" />
                </svg>
            </router-link>
        </div>
    </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useCartStore } from '../stores/cart'

const cart = useCartStore()
const cartCount = computed(() => cart.totalItems)
</script>
