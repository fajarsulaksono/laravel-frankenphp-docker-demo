<template>
    <div>
        <h1 class="text-2xl font-bold mb-6">Keranjang Belanja</h1>

        <div v-if="cart.items.length === 0" class="text-center py-20 text-gray-400">
            <span class="text-6xl">🛒</span>
            <p class="mt-4">Keranjang masih kosong</p>
            <router-link to="/products" class="btn btn-primary mt-4">Belanja Sekarang</router-link>
        </div>

        <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-4">
                <div v-for="item in cart.items" :key="item.product_id"
                     class="card card-side bg-base-100 shadow-md items-center p-4">
                    <figure class="w-20 h-20">
                        <img :src="item.image_url || 'https://placehold.co/200x200?text=Item'"
                             class="rounded-lg object-cover w-full h-full" />
                    </figure>
                    <div class="card-body p-4">
                        <h3 class="font-bold">{{ item.name }}</h3>
                        <p class="text-sm text-primary font-bold">Rp {{ formatPrice(item.price) }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <button class="btn btn-xs btn-ghost" @click="cart.updateQuantity(item.product_id, item.quantity - 1)">-</button>
                            <span class="font-bold">{{ item.quantity }}</span>
                            <button class="btn btn-xs btn-ghost" @click="cart.updateQuantity(item.product_id, item.quantity + 1)">+</button>
                            <span class="text-sm ml-2">= Rp {{ formatPrice(item.price * item.quantity) }}</span>
                        </div>
                    </div>
                    <button class="btn btn-ghost btn-sm text-red-500" @click="cart.removeItem(item.product_id)">
                        ✕
                    </button>
                </div>
            </div>

            <div class="card bg-base-100 shadow-md h-fit">
                <div class="card-body">
                    <h3 class="card-title">Ringkasan</h3>
                    <div class="space-y-2 mt-2">
                        <div class="flex justify-between text-sm">
                            <span>Total Item</span>
                            <span class="font-bold">{{ cart.totalItems }}</span>
                        </div>
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total Harga</span>
                            <span class="text-primary">Rp {{ formatPrice(cart.totalPrice) }}</span>
                        </div>
                    </div>
                    <button class="btn btn-primary mt-4 w-full" @click="checkout" :disabled="checkingOut">
                        {{ checkingOut ? 'Memproses...' : 'Checkout' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useApi } from '../composables/useApi'
import { useCartStore } from '../stores/cart'

const router = useRouter()
const { post } = useApi()
const cart = useCartStore()
const checkingOut = ref(false)

function formatPrice(val) {
    return Number(val).toLocaleString('id-ID')
}

async function checkout() {
    checkingOut.value = true
    try {
        await post('/checkout', { items: cart.items })
        cart.clear()
        router.push('/orders')
    } catch (e) {
        alert('Checkout gagal: ' + e.message)
    }
    checkingOut.value = false
}
</script>
