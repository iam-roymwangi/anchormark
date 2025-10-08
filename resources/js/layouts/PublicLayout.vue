<template>
  <div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#003366] text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-[#2E8B57] rounded flex items-center justify-center">
              <Anchor class="w-5 h-5 text-white" />
            </div>
            <span class="text-xl font-bold">AnchorMark</span>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center gap-8">
            <a href="/products" class="hover:text-[#2E8B57] transition-colors">Products</a>
            <a href="/about" class="hover:text-[#2E8B57] transition-colors">About</a>
            <a href="/contact" class="hover:text-[#2E8B57] transition-colors">Contact</a>
            <button class="bg-[#2E8B57] hover:bg-[#247047] px-6 py-2 rounded-lg transition-colors">
              Get a Quote
            </button>
            <button @click="cartOpen = true" class="relative p-2 rounded-full hover:bg-white/10 transition-colors">
              <ShoppingCart class="w-6 h-6" />
              <span v-if="cartItems.length" class="absolute -top-1 -right-1 bg-[#2E8B57] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                {{ cartItems.length }}
              </span>
            </button>
          </div>

          <!-- Mobile Menu Button -->
          <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden">
            <Menu v-if="!mobileMenuOpen" class="w-6 h-6" />
            <X v-else class="w-6 h-6" />
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div v-if="mobileMenuOpen" class="md:hidden bg-[#003366] border-t border-white/10">
        <div class="px-4 py-4 space-y-3">
          <a href="#products" class="block hover:text-[#2E8B57] transition-colors">Products</a>
          <a href="#about" class="block hover:text-[#2E8B57] transition-colors">About</a>
          <a href="#contact" class="block hover:text-[#2E8B57] transition-colors">Contact</a>
          <button class="w-full bg-[#2E8B57] hover:bg-[#247047] px-6 py-2 rounded-lg transition-colors">
            Get a Quote
          </button>
        </div>
      </div>
    </nav>

   <div>
     <slot />
   </div>

    

  

   

   

    <!-- Cart Popup -->
    <div class="fixed bottom-4 right-4 z-50 p-4 bg-[#003366] rounded-[50%]">
      <CartPopup
        :is-open="cartOpen"
        :cart-items="cartItems"
        @close="cartOpen = false"
        @update-quantity="updateCartQuantity"
        @remove-item="removeFromCart"
      />
    </div>

    <!-- Footer -->
    <footer class="bg-[#003366] text-white py-12 border-t border-white/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
          <div>
            <div class="flex items-center gap-2 mb-4">
              <div class="w-8 h-8 bg-[#2E8B57] rounded flex items-center justify-center">
                <Anchor class="w-5 h-5 text-white" />
              </div>
              <span class="text-xl font-bold">AnchorMark</span>
            </div>
            <p class="text-white/70 text-sm">
              Premium hospitality solutions for exceptional hotels worldwide.
            </p>
          </div>
          <div>
            <h4 class="font-bold mb-4">Products</h4>
            <ul class="space-y-2 text-sm text-white/70">
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Beddings</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Kitchenware</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Furniture</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Accessories</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-bold mb-4">Company</h4>
            <ul class="space-y-2 text-sm text-white/70">
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">About Us</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Sustainability</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Careers</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Contact</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-bold mb-4">Connect</h4>
            <div class="flex gap-3">
              <a href="#" class="w-10 h-10 bg-white/10 hover:bg-[#2E8B57] rounded-lg flex items-center justify-center transition-colors">
                <Mail class="w-5 h-5" />
              </a>
              <a href="#" class="w-10 h-10 bg-white/10 hover:bg-[#2E8B57] rounded-lg flex items-center justify-center transition-colors">
                <Phone class="w-5 h-5" />
              </a>
            </div>
          </div>
        </div>
        <div class="border-t border-white/10 pt-8 text-center text-sm text-white/70">
          <p>&copy; 2025 AnchorMark. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref, provide } from 'vue'
import { 
  Menu, 
  X, 
  Anchor,
  Mail,
  Phone,
  ShoppingCart
} from 'lucide-vue-next'
import CartPopup from '@/components/core/CartPopup.vue'

interface Product {
  id: number
  name: string
  description: string
  price: string
  category: string
  image: string
}

interface CartItem extends Product {
  quantity: number
}

const mobileMenuOpen = ref(false)
const cartOpen = ref(false)
const cartItems = ref<CartItem[]>([])

const addToCart = (product: Product) => {
  const existingItem = cartItems.value.find(item => item.id === product.id)
  if (existingItem) {
    existingItem.quantity++
  } else {
    cartItems.value.push({ ...product, quantity: 1 })
  }
  cartOpen.value = true
}

const updateCartQuantity = ({ id, quantity }: { id: number; quantity: number }) => {
  const item = cartItems.value.find(item => item.id === id)
  if (item) {
    item.quantity = quantity
  }
}

const removeFromCart = (id: number) => {
  const index = cartItems.value.findIndex(item => item.id === id)
  if (index > -1) {
    cartItems.value.splice(index, 1)
  }
}

provide('addToCart', addToCart)



</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600;700&display=swap');

.font-serif {
  font-family: 'Playfair Display', serif;
}

* {
  font-family: 'Inter', sans-serif;
}
</style>
