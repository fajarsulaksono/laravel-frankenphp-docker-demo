<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Produk</h1>
            <div class="flex gap-2">
                <select class="select select-bordered select-sm" v-model="category">
                    <option value="">Semua Kategori</option>
                    <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
                <input type="text" placeholder="Cari..." class="input input-bordered input-sm w-48"
                       v-model="search" @input="debouncedSearch" />
            </div>
        </div>

        <div v-if="loading" class="flex justify-center py-20">
            <span class="loading loading-spinner loading-lg"></span>
        </div>

        <div v-else-if="products.length === 0" class="text-center py-20 text-gray-400">
            <span class="text-6xl">📭</span>
            <p class="mt-4">Tidak ada produk ditemukan</p>
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <ProductCard v-for="p in products" :key="p.id" :product="p" @add-cart="addToCart" />
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useApi } from '../composables/useApi'
import { useCartStore } from '../stores/cart'
import ProductCard from '../components/ProductCard.vue'

const { get } = useApi()
const cart = useCartStore()

const products = ref([])
const categories = ref([])
const loading = ref(true)
const category = ref('')
const search = ref('')
let debounceTimer

function debouncedSearch() {
    clearTimeout(debounceTimer)
    debounceTimer = setTimeout(fetchProducts, 400)
}

async function fetchProducts() {
    loading.value = true
    const params = new URLSearchParams()
    if (category.value) params.set('category', category.value)
    if (search.value) params.set('search', search.value)
    try {
        const res = await get(`/products?${params}`)
        products.value = res.data || res
    } catch { products.value = [] }
    loading.value = false
}

function addToCart(product) {
    cart.addItem(product)
}

watch(category, fetchProducts)

onMounted(async () => {
    try {
        categories.value = await get('/categories')
    } catch {}
    await fetchProducts()
})
</script>
