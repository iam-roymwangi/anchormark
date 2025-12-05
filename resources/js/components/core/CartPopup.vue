<template>
  <div>
     <!-- Cart Button Trigger  -->
    <button
      @click="openCart"
      class="relative p-2 text-black hover:text-[#AE8625] transition-colors"
    >
      <ShoppingCart :size="24" />
      <span
        v-if="cartItemCount > 0"
        class="absolute -top-1 -right-1 bg-[#AE8625] text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"
      >
        {{ cartItemCount }}
      </span>
    </button>

     <!-- Overlay  -->
    <Transition name="fade">
      <div
        v-if="isOpen"
        @click="isOpen = false"
        class="fixed inset-0 bg-black/40 z-40 backdrop-blur-sm"
      />
    </Transition>

     <!-- Cart Drawer  -->
    <Transition name="slide">
      <div
        v-if="isOpen"
        class="fixed top-0 right-0 h-full w-full sm:w-[480px] bg-[#F5F5F0] shadow-2xl z-50 flex flex-col"
      >
         <!-- Header  -->
        <div class="bg-[#003366] text-white px-6 py-5 flex items-center justify-between">
          <div class="flex items-center gap-3">
            <ShoppingCart :size="24" />
            <h2 class="text-xl font-bold">Shopping Cart</h2>
          </div>
          <button
            @click="isOpen = false"
            class="hover:text-[#AE8625] transition-colors p-1"
          >
            <X :size="24" />
          </button>
        </div>

         <!-- Cart Items  -->
        <div class="flex-1 overflow-y-auto px-6 py-6">
           <!-- Debug Info (remove in production) -->
          <div v-if="false" class="mb-4 p-2 bg-gray-100 text-xs">
            <p>User: {{ user ? 'Logged in' : 'Guest' }}</p>
            <p>Items count: {{ cartData.items.length }}</p>
            <p>Totals: {{ JSON.stringify(cartData.totals) }}</p>
            <p>Items: {{ JSON.stringify(cartData.items) }}</p>
          </div>
          
           <!-- Empty State  -->
          <div
            v-if="cartData.items.length === 0"
            class="flex flex-col items-center justify-center h-full text-center"
          >
            <div class="w-24 h-24 bg-[#E0E0E0] rounded-full flex items-center justify-center mb-6">
              <ShoppingCart :size="48" class="text-[#666666]" />
            </div>
            <h3 class="text-2xl font-serif font-bold text-[#333333] mb-3">
              Your cart is empty
            </h3>
            <p class="text-[#666666] mb-6 max-w-xs">
              Add some premium hospitality products to get started
            </p>
            <button
              @click="isOpen = false"
              class="bg-[#AE8625] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#267347] transition-all transform hover:scale-105"
            >
              Continue Shopping
            </button>
          </div>
          <div v-else class="space-y-4">
            <TransitionGroup name="list">
              <div
                v-for="item in cartData.items"
                :key="item.id"
                class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition-all"
              >
                <div class="flex gap-4">
                   <!-- Product Image  -->
                  <div class="w-24 h-24 bg-[#F5F5F0] rounded-lg overflow-hidden flex-shrink-0">
                    <img
                      :src="item.image"
                      :alt="item.name"
                      class="w-full h-full object-cover"
                    />
                  </div>

                   <!-- Product Details  -->
                  <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-[#333333] mb-1 truncate">
                      {{ item.name }}
                    </h3>
                    <p class="text-sm text-[#666666] mb-2">
                      {{ item.size }} • {{ item.color }}
                    </p>
                    <div class="flex items-center justify-between">
                      <span class="text-lg font-bold text-[#AE8625]">
                        ${{ item.price }}
                      </span>

                       <!-- Quantity Controls  -->
                      <div class="flex items-center gap-2">
                        <button
                          @click="decreaseQuantity(item.id)"
                          class="w-8 h-8 border-2 border-[#E0E0E0] text-black rounded-lg hover:border-[#AE8625] transition-colors flex items-center justify-center"
                        >
                          <Minus :size="14" />
                        </button>
                        <span class="w-8 text-center font-medium text-[#333333]">
                          {{ item.quantity }}
                        </span>
                        <button
                          @click="increaseQuantity(item.id)"
                          class="w-8 h-8 border-2 border-[#E0E0E0] text-black rounded-lg hover:border-[#AE8625] transition-colors flex items-center justify-center"
                        >
                          <Plus :size="14" />
                        </button>
                      </div>
                    </div>
                  </div>

                   <!-- Remove Button  -->
                  <button
                    @click="removeItem(item.id)"
                    class="text-[#666666] hover:text-red-500 transition-colors p-1"
                  >
                    <Trash2 :size="18" />
                  </button>
                </div>
              </div>
            </TransitionGroup>
          </div>
        </div>

         <!-- Footer  -->
        <div v-if="cartData.items.length > 0" class="border-t border-[#E0E0E0] bg-white px-6 py-6">
           <!-- Subtotal  -->
          <div class="space-y-3 mb-6">
            <div class="flex justify-between text-[#666666]">
              <span>Subtotal</span>
              <span>${{ cartData.totals.amount.toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-[#666666]">
              <span>Shipping</span>
              <span>{{ shipping === 0 ? 'Free' : `$${shipping.toFixed(2)}` }}</span>
            </div>
            <div class="flex justify-between text-[#666666]">
              <span>Tax</span>
              <span>${{ tax.toFixed(2) }}</span>
            </div>
            <div class="border-t border-[#E0E0E0] pt-3 flex justify-between text-xl font-bold text-[#333333]">
              <span>Total</span>
              <span class="text-[#AE8625]">${{ total.toFixed(2) }}</span>
            </div>
          </div>

           <!-- Action Buttons  -->
          <div class="space-y-3">
            <button
              class="w-full bg-[#AE8625] text-white py-4 rounded-lg font-semibold hover:bg-[#267347] transition-all transform hover:scale-[1.02] active:scale-[0.98]"
            >
              Proceed to Checkout
            </button>
            <button
              @click="isOpen = false"
              class="w-full border-2 border-[#AE8625] text-[#AE8625] py-4 rounded-lg font-semibold hover:bg-[#AE8625] hover:text-white transition-all"
            >
              Continue Shopping
            </button>
          </div>

           <!-- Trust Badges  -->
          <div class="mt-6 flex items-center justify-center gap-4 text-sm text-[#666666]">
            <div class="flex items-center gap-1">
              <Shield :size="16" class="text-[#AE8625]" />
              <span>Secure Checkout</span>
            </div>
            <div class="flex items-center gap-1">
              <Truck :size="16" class="text-[#AE8625]" />
              <span>Free Shipping</span>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { ShoppingCart, X, Minus, Plus, Trash2, Shield, Truck } from 'lucide-vue-next'
import { usePage } from '@inertiajs/vue3' // Removed router import
import axios from 'axios' // Import axios

const emit = defineEmits(['update:cartItemCount']) // Define emits

interface User {
  id: number;
  name: string;
  email: string;
}

interface CartItem {
  id: number | string
  product_id: number // Added product_id for backend interaction
  name: string
  price: number
  quantity: number
  image: string
  size: string | null
  color: string | null
}

const isOpen = ref(false)
const page = usePage()
const user = computed<User | null>(() => page.props.auth?.user as User | null)

const cartData = ref<{ items: CartItem[], totals: { items: number, amount: number } }>({
  items: [],
  totals: { items: 0, amount: 0 }
})

const fetchCart = async () => {
  if (user.value) {
    // Fetch from backend for logged-in users
    try {
      const response = await axios.get('/cart-data')
      console.log('Cart API response:', response.data)
      const items = response.data.cart?.items || []
      // Transform backend items to match CartItem interface
      cartData.value.items = items.map((item: any) => ({
        id: item.id,
        product_id: item.product_id,
        name: item.name,
        price: item.price,
        quantity: item.quantity,
        image: item.image || '/placeholder.svg',
        size: item.size || null,
        color: item.color || null
      }))
      cartData.value.totals = response.data.cart?.totals || { items: 0, amount: 0 }
      console.log('Cart fetched (logged in):', cartData.value)
    } catch (error) {
      console.error('Error fetching cart data (logged in):', error)
      cartData.value.items = []
      cartData.value.totals = { items: 0, amount: 0 }
    }
  } else {
    // Fetch from local storage for guest users
    try {
      const guestCartStr = localStorage.getItem('guestCart')
      console.log('Raw guest cart from localStorage:', guestCartStr)
      const guestCart = guestCartStr ? JSON.parse(guestCartStr) as CartItem[] : []
      console.log('Parsed guest cart:', guestCart)
      
      if (!Array.isArray(guestCart)) {
        console.error('Guest cart is not an array:', guestCart)
        cartData.value.items = []
        cartData.value.totals = { items: 0, amount: 0 }
        return
      }
      
      // Ensure all items have IDs (for backward compatibility)
      const cartWithIds: CartItem[] = guestCart.map((item, index) => ({
        ...item,
        id: item.id || `guest-${Date.now()}-${index}`
      }))
      cartData.value.items = cartWithIds
      cartData.value.totals.items = cartWithIds.reduce((total: number, item: CartItem) => total + item.quantity, 0)
      cartData.value.totals.amount = cartWithIds.reduce((total: number, item: CartItem) => total + item.price * item.quantity, 0)
      console.log('Cart fetched (guest):', cartData.value)
    } catch (error) {
      console.error('Error parsing guest cart:', error)
      cartData.value.items = []
      cartData.value.totals = { items: 0, amount: 0 }
    }
  }
}

const handleCartUpdate = () => {
  // Refresh cart data when cart is updated
  console.log('Cart update event received, refreshing cart...')
  fetchCart()
}

const openCart = () => {
  // Refresh cart when opening
  fetchCart()
  isOpen.value = true
}

onMounted(() => {
  fetchCart()
  
  // Listen for cart updates from other components
  window.addEventListener('cartUpdated', handleCartUpdate)
  
  // Also listen for storage changes (for guest cart updates from other tabs)
  window.addEventListener('storage', (e) => {
    if (e.key === 'guestCart' && !user.value) {
      handleCartUpdate()
    }
  })
})

onUnmounted(() => {
  window.removeEventListener('cartUpdated', handleCartUpdate)
  window.removeEventListener('storage', handleCartUpdate)
})

const cartItemCount = computed(() => {
  return cartData.value.totals.items
})

// Watch cartItemCount and emit to parent
watch(cartItemCount, (newCount: number) => { // Explicitly typed newCount
  emit('update:cartItemCount', newCount)
}, { immediate: true }) // Emit immediately on mount

const subtotal = computed(() => {
  return cartData.value.items.reduce((total: number, item: CartItem) => total + item.price * item.quantity, 0)
})

const shipping = computed(() => {
  return subtotal.value >= 500 ? 0 : 49.99
})

const tax = computed(() => {
  return subtotal.value * 0.08 // 8% tax
})

const total = computed(() => {
  return subtotal.value + shipping.value + tax.value
})

const increaseQuantity = async (itemId: number | string) => {
  const item = cartData.value.items.find(i => i.id === itemId)
  if (item) {
    const newQuantity = item.quantity + 1
    if (user.value) {
      try {
        await axios.put(`/cart/update/${item.id}`, { quantity: newQuantity })
        fetchCart()
      } catch (errors) {
        console.error('Error updating cart item quantity (logged in):', errors)
      }
    } else {
      const guestCart = JSON.parse(localStorage.getItem('guestCart') || '[]') as CartItem[]
      const existingItemIndex = guestCart.findIndex(cartItem => cartItem.id === itemId)
      if (existingItemIndex > -1) {
        guestCart[existingItemIndex].quantity = newQuantity
        localStorage.setItem('guestCart', JSON.stringify(guestCart))
        fetchCart()
      }
    }
  }
}

const decreaseQuantity = async (itemId: number | string) => {
  const item = cartData.value.items.find(i => i.id === itemId)
  if (item && item.quantity > 1) {
    const newQuantity = item.quantity - 1
    if (user.value) {
      try {
        await axios.put(`/cart/update/${item.id}`, { quantity: newQuantity })
        fetchCart()
      } catch (errors) {
        console.error('Error updating cart item quantity (logged in):', errors)
      }
    } else {
      const guestCart = JSON.parse(localStorage.getItem('guestCart') || '[]') as CartItem[]
      const existingItemIndex = guestCart.findIndex(cartItem => cartItem.id === itemId)
      if (existingItemIndex > -1) {
        guestCart[existingItemIndex].quantity = newQuantity
        localStorage.setItem('guestCart', JSON.stringify(guestCart))
        fetchCart()
      }
    }
  }
}

const removeItem = async (itemId: number | string) => {
  if (user.value) {
    try {
      await axios.delete(`/cart/remove/${itemId}`)
      fetchCart()
    } catch (errors) {
      console.error('Error removing cart item (logged in):', errors)
    }
  } else {
    const guestCart = JSON.parse(localStorage.getItem('guestCart') || '[]') as CartItem[]
    const updatedGuestCart = guestCart.filter(item => item.id !== itemId)
    localStorage.setItem('guestCart', JSON.stringify(updatedGuestCart))
    fetchCart()
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap');

.font-serif {
  font-family: 'Playfair Display', serif;
}

* {
  font-family: 'Cormorant Garamond', serif;
  font-weight: 400;
  letter-spacing: 0.01em;
}

h1, h2, h3, h4, h5, h6 {
  font-family: 'Playfair Display', serif;
  font-weight: 600;
  letter-spacing: 0.02em;
}

/* Slide transition for drawer */
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease-out;
}

.slide-enter-from {
  transform: translateX(100%);
}

.slide-leave-to {
  transform: translateX(100%);
}

/* Fade transition for overlay */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* List transition for cart items */
.list-enter-active,
.list-leave-active {
  transition: all 0.3s ease;
}

.list-enter-from {
  opacity: 0;
  transform: translateX(30px);
}

.list-leave-to {
  opacity: 0;
  transform: translateX(-30px);
}

.list-move {
  transition: transform 0.3s ease;
}
</style>
