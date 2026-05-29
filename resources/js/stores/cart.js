import { reactive, computed } from 'vue'

const state = reactive({
    items: JSON.parse(localStorage.getItem('cart') || '[]'),
})

function save() {
    localStorage.setItem('cart', JSON.stringify(state.items))
}

export function useCartStore() {
    function addItem(product, quantity = 1) {
        const existing = state.items.find(i => i.product_id === product.id)
        if (existing) {
            existing.quantity += quantity
        } else {
            state.items.push({
                product_id: product.id,
                name: product.name,
                price: product.price,
                image_url: product.image_url,
                quantity,
            })
        }
        save()
    }

    function removeItem(productId) {
        state.items = state.items.filter(i => i.product_id !== productId)
        save()
    }

    function updateQuantity(productId, qty) {
        const item = state.items.find(i => i.product_id === productId)
        if (item) {
            item.quantity = Math.max(1, qty)
            save()
        }
    }

    function clear() {
        state.items = []
        save()
    }

    const totalItems = computed(() => state.items.reduce((sum, i) => sum + i.quantity, 0))
    const totalPrice = computed(() => state.items.reduce((sum, i) => sum + i.price * i.quantity, 0))

    return {
        items: state.items,
        addItem,
        removeItem,
        updateQuantity,
        clear,
        totalItems,
        totalPrice,
    }
}
