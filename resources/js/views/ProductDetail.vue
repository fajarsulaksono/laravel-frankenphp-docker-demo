<template>
    <div v-if="loading" class="flex justify-center py-20">
        <span class="loading loading-spinner loading-lg"></span>
    </div>

    <div v-else-if="!product" class="text-center py-20 text-gray-400">
        <span class="text-6xl">❌</span>
        <p class="mt-4">Produk tidak ditemukan</p>
        <router-link to="/products" class="btn btn-primary mt-4">Kembali</router-link>
    </div>

    <div v-else class="max-w-4xl mx-auto">
        <router-link to="/products" class="btn btn-ghost btn-sm mb-4">&larr; Kembali</router-link>
        <div class="card lg:card-side bg-base-100 shadow-xl">
            <figure class="lg:w-1/2">
                <img :src="product.image_url || 'https://placehold.co/600x600?text=No+Image'"
                     :alt="product.name" class="w-full h-80 lg:h-full object-cover" />
            </figure>
            <div class="card-body lg:w-1/2">
                <span class="badge badge-ghost">{{ product.category }}</span>
                <h1 class="text-3xl font-bold">{{ product.name }}</h1>
                <p class="text-2xl font-bold text-primary">Rp {{ formatPrice(product.price) }}</p>
                <p class="text-gray-600">{{ product.description }}</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-sm" :class="product.stock > 0 ? 'text-green-600' : 'text-red-500'">
                        {{ product.stock > 0 ? 'Stok: ' + product.stock + ' pcs' : 'Stok Habis' }}
                    </span>
                </div>

                <div class="flex items-center gap-4 mt-4" v-if="product.stock > 0">
                    <div class="join">
                        <button class="btn btn-sm join-item" @click="qty = Math.max(1, qty - 1)">-</button>
                        <input class="input input-bordered input-sm join-item w-16 text-center" v-model.number="qty" min="1" />
                        <button class="btn btn-sm join-item" @click="qty++">+</button>
                    </div>
                    <button class="btn btn-primary flex-1" @click="addToCart">
                        + Keranjang
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useApi } from '../composables/useApi'
import { useCartStore } from '../stores/cart'

const route = useRoute()
const { get } = useApi()
const cart = useCartStore()

const product = ref(null)
const loading = ref(true)
const qty = ref(1)

function formatPrice(val) {
    return Number(val).toLocaleString('id-ID')
}

function addToCart() {
    cart.addItem(product.value, qty.value)
}

onMounted(async () => {
    try {
        product.value = await get(`/products/${route.params.id}`)
    } catch { product.value = null }
    loading.value = false
})
</script>
