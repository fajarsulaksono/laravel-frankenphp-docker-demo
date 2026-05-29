<template>
    <div>
        <!-- Hero -->
        <div class="hero rounded-2xl bg-gradient-to-r from-red-600 to-red-500 text-white p-12 mb-8">
            <div class="hero-content text-center">
                <div class="max-w-lg">
                    <h1 class="text-5xl font-bold">Laravel Store</h1>
                    <p class="py-4 text-lg opacity-90">Demo toko online dengan FrankenPHP, Vue 3, MariaDB, Redis &amp; Jaeger</p>
                    <router-link to="/products" class="btn btn-accent btn-lg">Lihat Produk</router-link>
                </div>
            </div>
        </div>

        <!-- Service Health -->
        <div class="card bg-base-100 shadow-md mb-8">
            <div class="card-body">
                <h2 class="card-title text-sm uppercase tracking-widest text-gray-500">Docker Services</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-4">
                    <div v-for="s in services" :key="s.name"
                         class="flex items-center justify-between px-4 py-3 rounded-xl border"
                         :class="s.status === 'running' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">{{ s.icon }}</span>
                            <div>
                                <p class="text-sm font-bold">{{ s.name }}</p>
                                <p class="text-xs" :class="s.status === 'running' ? 'text-green-600' : 'text-red-500'">
                                    {{ s.detail }}
                                </p>
                            </div>
                        </div>
                        <span class="flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full"
                              :class="s.status === 'running' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'">
                            <span class="w-1.5 h-1.5 rounded-full inline-block"
                                  :class="s.status === 'running' ? 'bg-green-500 animate-pulse' : 'bg-red-500'"></span>
                            {{ s.status === 'running' ? 'Running' : 'Down' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <router-link to="/products" class="card bg-base-100 shadow-md hover:shadow-xl transition-shadow">
                <div class="card-body items-center text-center">
                    <span class="text-4xl">📦</span>
                    <h3 class="card-title">Produk</h3>
                    <p class="text-sm text-gray-500">Lihat katalog produk terbaru</p>
                </div>
            </router-link>
            <router-link to="/cart" class="card bg-base-100 shadow-md hover:shadow-xl transition-shadow">
                <div class="card-body items-center text-center">
                    <span class="text-4xl">🛒</span>
                    <h3 class="card-title">Keranjang</h3>
                    <p class="text-sm text-gray-500">{{ cartCount }} items di keranjang</p>
                </div>
            </router-link>
            <router-link to="/orders" class="card bg-base-100 shadow-md hover:shadow-xl transition-shadow">
                <div class="card-body items-center text-center">
                    <span class="text-4xl">📋</span>
                    <h3 class="card-title">Pesanan</h3>
                    <p class="text-sm text-gray-500">Riwayat pesanan kamu</p>
                </div>
            </router-link>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useApi } from '../composables/useApi'
import { useCartStore } from '../stores/cart'

const { get } = useApi()
const cart = useCartStore()
const cartCount = computed(() => cart.totalItems)

const services = ref([
    { name: 'FrankenPHP', icon: '🐘', detail: 'PHP ' + phpversion(), status: 'running' },
    { name: 'MariaDB', icon: '🗄️', detail: 'Menghubungkan...', status: 'running' },
    { name: 'Redis', icon: '⚡', detail: 'Menghubungkan...', status: 'running' },
    { name: 'phpMyAdmin', icon: '🐬', detail: 'port :9001', status: 'running' },
    { name: 'Jaeger', icon: '🔍', detail: 'port :9016', status: 'running' },
    { name: 'OpenTelemetry', icon: '📊', detail: 'gRPC :4317', status: 'running' },
])

onMounted(async () => {
    try {
        const health = await get('/health')
        services.value[1].detail = health.database === 'connected' ? 'Terhubung' : health.database
        services.value[2].detail = health.cache === 'active' ? 'Active' : health.cache
        services.value[3].status = health.database === 'connected' ? 'running' : 'down'
    } catch {
        services.value[1].detail = 'Error'
        services.value[2].detail = 'Error'
    }
})

function phpversion() {
    // Will be populated from API or kept as static
    return '8.3'
}
</script>
