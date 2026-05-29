<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Riwayat Pesanan</h1>

        <div v-if="loading" class="flex justify-center py-20">
            <span class="loading loading-spinner loading-lg"></span>
        </div>

        <div v-else-if="orders.length === 0" class="text-center py-20 text-gray-400">
            <span class="text-6xl">📋</span>
            <p class="mt-4">Belum ada pesanan</p>
            <router-link to="/products" class="btn btn-primary mt-4">Mulai Belanja</router-link>
        </div>

        <div v-else class="space-y-4">
            <div v-for="order in orders" :key="order.id" class="card bg-base-100 shadow-md">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Order #{{ order.id }}</p>
                            <p class="text-xs text-gray-400">{{ order.created_at }}</p>
                        </div>
                        <span class="badge" :class="statusBadge(order.status)">{{ order.status }}</span>
                    </div>
                    <div class="mt-2 space-y-1">
                        <div v-for="item in order.items" :key="item.id" class="flex justify-between text-sm">
                            <span>{{ item.product.name }} x{{ item.quantity }}</span>
                            <span>Rp {{ formatPrice(item.price * item.quantity) }}</span>
                        </div>
                    </div>
                    <div class="flex justify-between font-bold mt-3 pt-3 border-t">
                        <span>Total</span>
                        <span class="text-primary">Rp {{ formatPrice(order.total_price) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useApi } from '../composables/useApi'

const { get } = useApi()
const orders = ref([])
const loading = ref(true)

function formatPrice(val) {
    return Number(val).toLocaleString('id-ID')
}

function statusBadge(status) {
    const map = { pending: 'badge-warning', processing: 'badge-info', completed: 'badge-success', cancelled: 'badge-error' }
    return map[status] || 'badge-ghost'
}

onMounted(async () => {
    try {
        orders.value = await get('/orders')
    } catch { orders.value = [] }
    loading.value = false
})
</script>
