import { createRouter, createWebHistory } from 'vue-router'

import HomePage from '../views/HomePage.vue'
import ProductsPage from '../views/ProductsPage.vue'
import ProductDetail from '../views/ProductDetail.vue'
import CartPage from '../views/CartPage.vue'
import OrdersPage from '../views/OrdersPage.vue'

const routes = [
    { path: '/', name: 'home', component: HomePage },
    { path: '/products', name: 'products', component: ProductsPage },
    { path: '/products/:id', name: 'product-detail', component: ProductDetail },
    { path: '/cart', name: 'cart', component: CartPage },
    { path: '/orders', name: 'orders', component: OrdersPage },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
