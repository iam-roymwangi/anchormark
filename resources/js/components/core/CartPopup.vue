<template>
  <div>
     <!-- Cart Button Trigger  -->
    <button
      @click="isOpen = true"
      class="relative p-2 hover:text-[#2E8B57] transition-colors"
    >
      <ShoppingCart :size="24" />
      <span
        v-if="cartItemCount > 0"
        class="absolute -top-1 -right-1 bg-[#2E8B57] text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center"
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
            class="hover:text-[#2E8B57] transition-colors p-1"
          >
            <X :size="24" />
          </button>
        </div>

         <!-- Cart Items  -->
        <div class="flex-1 overflow-y-auto px-6 py-6">
           <!-- Empty State  -->
          <div
            v-if="cartItems.length === 0"
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
              class="bg-[#2E8B57] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#267347] transition-all transform hover:scale-105"
            >
              Continue Shopping
            </button>
          </div>
          <div v-else class="space-y-4">
            <TransitionGroup name="list">
              <div
                v-for="item in cartItems"
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
                      <span class="text-lg font-bold text-[#2E8B57]">
                        ${{ item.price }}
                      </span>

                       <!-- Quantity Controls  -->
                      <div class="flex items-center gap-2">
                        <button
                          @click="decreaseQuantity(item.id)"
                          class="w-8 h-8 border-2 border-[#E0E0E0] rounded-lg hover:border-[#2E8B57] transition-colors flex items-center justify-center"
                        >
                          <Minus :size="14" />
                        </button>
                        <span class="w-8 text-center font-medium text-[#333333]">
                          {{ item.quantity }}
                        </span>
                        <button
                          @click="increaseQuantity(item.id)"
                          class="w-8 h-8 border-2 border-[#E0E0E0] rounded-lg hover:border-[#2E8B57] transition-colors flex items-center justify-center"
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
        <div v-if="cartItems.length > 0" class="border-t border-[#E0E0E0] bg-white px-6 py-6">
           <!-- Subtotal  -->
          <div class="space-y-3 mb-6">
            <div class="flex justify-between text-[#666666]">
              <span>Subtotal</span>
              <span>${{ subtotal.toFixed(2) }}</span>
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
              <span class="text-[#2E8B57]">${{ total.toFixed(2) }}</span>
            </div>
          </div>

           <!-- Action Buttons  -->
          <div class="space-y-3">
            <button
              class="w-full bg-[#2E8B57] text-white py-4 rounded-lg font-semibold hover:bg-[#267347] transition-all transform hover:scale-[1.02] active:scale-[0.98]"
            >
              Proceed to Checkout
            </button>
            <button
              @click="isOpen = false"
              class="w-full border-2 border-[#2E8B57] text-[#2E8B57] py-4 rounded-lg font-semibold hover:bg-[#2E8B57] hover:text-white transition-all"
            >
              Continue Shopping
            </button>
          </div>

           <!-- Trust Badges  -->
          <div class="mt-6 flex items-center justify-center gap-4 text-sm text-[#666666]">
            <div class="flex items-center gap-1">
              <Shield :size="16" class="text-[#2E8B57]" />
              <span>Secure Checkout</span>
            </div>
            <div class="flex items-center gap-1">
              <Truck :size="16" class="text-[#2E8B57]" />
              <span>Free Shipping</span>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { ShoppingCart, X, Minus, Plus, Trash2, Shield, Truck } from 'lucide-vue-next'

interface CartItem {
  id: number
  name: string
  price: number
  quantity: number
  image: string
  size: string
  color: string
}

const isOpen = ref(false)

// Sample cart items - replace with your actual cart state management
const cartItems = ref<CartItem[]>([
  {
    id: 1,
    name: 'Premium Cotton Duvet Set',
    price: 299,
    quantity: 2,
    image: '/placeholder.svg?height=200&width=200',
    size: 'King',
    color: 'White'
  },
  {
    id: 2,
    name: 'Luxury Silk Pillowcase Set',
    price: 89,
    quantity: 1,
    image: '/placeholder.svg?height=200&width=200',
    size: 'Standard',
    color: 'Ivory'
  },
  {
    id: 3,
    name: 'Hotel Collection Bed Sheets',
    price: 149,
    quantity: 1,
    image: '/placeholder.svg?height=200&width=200',
    size: 'Queen',
    color: 'Light Gray'
  }
])

const cartItemCount = computed(() => {
  return cartItems.value.reduce((total, item) => total + item.quantity, 0)
})

const subtotal = computed(() => {
  return cartItems.value.reduce((total, item) => total + item.price * item.quantity, 0)
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

const increaseQuantity = (itemId: number) => {
  const item = cartItems.value.find(i => i.id === itemId)
  if (item) {
    item.quantity++
  }
}

const decreaseQuantity = (itemId: number) => {
  const item = cartItems.value.find(i => i.id === itemId)
  if (item && item.quantity > 1) {
    item.quantity--
  }
}

const removeItem = (itemId: number) => {
  cartItems.value = cartItems.value.filter(item => item.id !== itemId)
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600;700&display=swap');

.font-serif {
  font-family: 'Playfair Display', serif;
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
