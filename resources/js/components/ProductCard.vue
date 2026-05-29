<template>
    <div class="card bg-base-100 shadow-md hover:shadow-xl transition-shadow">
        <figure class="px-4 pt-4">
            <img :src="product.image_url || 'https://placehold.co/400x400?text=No+Image'"
                 :alt="product.name"
                 class="rounded-xl h-48 w-full object-cover" />
        </figure>
        <div class="card-body">
            <span class="badge badge-ghost text-xs">{{ product.category }}</span>
            <h2 class="card-title text-base">{{ product.name }}</h2>
            <p class="text-sm text-gray-500 line-clamp-2">{{ product.description }}</p>
            <div class="flex items-center justify-between mt-2">
                <span class="text-lg font-bold text-primary">Rp {{ formatPrice(product.price) }}</span>
                <span class="text-xs" :class="product.stock > 0 ? 'text-green-600' : 'text-red-500'">
                    {{ product.stock > 0 ? product.stock + ' pcs' : 'Habis' }}
                </span>
            </div>
            <div class="card-actions mt-3">
                <router-link :to="`/products/${product.id}`" class="btn btn-primary btn-sm flex-1">
                    Detail
                </router-link>
                <button class="btn btn-outline btn-sm" @click="$emit('add-cart', product)" :disabled="product.stock === 0">
                    + Keranjang
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({ product: { type: Object, required: true } })
defineEmits(['add-cart'])

function formatPrice(val) {
    return Number(val).toLocaleString('id-ID')
}
</script>
