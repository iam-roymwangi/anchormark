<template>
  <div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#F5F5F0] text-[#333333] border-b border-[#333333]/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <a href="/" class="flex items-center gap-2">
            <img src="/assets/images/anchormark-logo.jpg" alt="AnchorMark Logo" class="w-8 h-8 object-cover rounded" />
          </a>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center gap-8">
            <Link href="/" class="hover:text-[#AE8625] transition-colors">Home</Link>
            <Link href="/products" class="hover:text-[#AE8625] transition-colors">Products</Link>
            <Link href="/about" class="hover:text-[#AE8625] transition-colors">About</Link>
            <Link href="/contact" class="hover:text-[#AE8625] transition-colors">Contact</Link>
            <button class="bg-[#AE8625] hover:bg-[#247047] px-6 py-2 rounded-lg transition-colors text-white">
              Get a Quote
            </button>
            <button @click="cartOpen = true" class="relative p-2 rounded-full hover:bg-[#333333]/10 transition-colors">
              <ShoppingCart class="w-6 h-6" />
              <span v-if="cartItemCountDisplay > 0" class="absolute -top-1 -right-1 bg-[#AE8625] text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                {{ cartItemCountDisplay }}
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
      <div v-if="mobileMenuOpen" class="md:hidden bg-[#F5F5F0] border-t border-[#333333]/10">
        <div class="px-4 py-4 space-y-3">
          <Link href="/" class="block hover:text-[#AE8625] transition-colors">Home</Link>
          <Link href="/products" class="block hover:text-[#AE8625] transition-colors">Products</Link>
          <Link href="/about" class="block hover:text-[#AE8625] transition-colors">About</Link>
          <Link href="/contact" class="block hover:text-[#AE8625] transition-colors">Contact</Link>
          <button class="w-full bg-[#AE8625] hover:bg-[#247047] px-6 py-2 rounded-lg transition-colors">
            Get a Quote
          </button>
        </div>
      </div>
    </nav>

   <div>
     <slot />
   </div>

    

  

   

   

    <!-- Cart Popup -->
    <div class="fixed bottom-4 right-4 z-50 p-4 bg-[#F5F5F0] rounded-[50%] border border-[#333333]/10 shadow-lg">
      <CartPopup
        :is-open="cartOpen"
        @close="cartOpen = false"
        @update:cartItemCount="cartItemCountDisplay = $event"
      />
    </div>

    <!-- Footer -->
    <footer class="bg-[#F5F5F0] text-[#333333] py-12 border-t border-[#333333]/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
          <div>
            <a href="/" class="flex items-center gap-2 mb-4">
              <img src="/assets/images/anchormark-logo.jpg" alt="AnchorMark Logo" class="w-8 h-8 object-cover rounded" />
              <span class="text-xl font-bold">Anchormark Supplies</span>
            </a>
            <p class="text-[#333333]/70 text-sm">
              Premium hospitality solutions for exceptional hotels worldwide.
            </p>
          </div>
          <div>
            <h4 class="font-bold mb-4">Products</h4>
            <ul class="space-y-2 text-sm text-[#333333]/70">
              <li><a href="#" class="hover:text-[#AE8625] transition-colors">Beddings</a></li>
              <li><a href="#" class="hover:text-[#AE8625] transition-colors">Kitchenware</a></li>
              <li><a href="#" class="hover:text-[#AE8625] transition-colors">Furniture</a></li>
              <li><a href="#" class="hover:text-[#AE8625] transition-colors">Accessories</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-bold mb-4">Company</h4>
            <ul class="space-y-2 text-sm text-[#333333]/70">
              <li><Link href="/about" class="hover:text-[#AE8625] transition-colors">About Us</Link></li>
              <li><Link href="/products" class="hover:text-[#AE8625] transition-colors">Products</Link></li>
              <li><Link href="#" class="hover:text-[#AE8625] transition-colors">Vision</Link></li>
              <li><Link href="/contact" class="hover:text-[#AE8625] transition-colors">Contact</Link></li>
            </ul>
          </div>
          <div>
            <h4 class="font-bold mb-4">Connect</h4>
            <div class="flex gap-3">
              <a href="#" class="w-10 h-10 bg-[#333333]/10 hover:bg-[#AE8625] hover:text-white rounded-lg flex items-center justify-center transition-colors">
                <Mail class="w-5 h-5" />
              </a>
              <a href="#" class="w-10 h-10 bg-[#333333]/10 hover:bg-[#AE8625] hover:text-white rounded-lg flex items-center justify-center transition-colors">
                <Phone class="w-5 h-5" />
              </a>
            </div>
          </div>
        </div>
        <div class="border-t border-[#333333]/10 pt-8 text-center text-sm text-[#333333]/70">
          <p>&copy; 2025 Anchormark Supplies. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref, provide, computed } from 'vue' // Added computed
import { 
  Menu, 
  X, 
  Mail,
  Phone,
  ShoppingCart
} from 'lucide-vue-next'
import CartPopup from '@/components/core/CartPopup.vue'
import { usePage, router, Link } from '@inertiajs/vue3' // Import usePage and router

const page = usePage()
const user = computed<User | null>(() => page.props.auth?.user as User | null) // Define user computed property

interface User { // Define User interface
  id: number;
  name: string;
  email: string;
}

interface ProductForCart { // Define ProductForCart interface for addToCart
  id: number
  name: string
  price: number
  image: string
  size?: string | null
  color?: string | null
}

const mobileMenuOpen = ref(false)
const cartOpen = ref(false)
const cartItemCountDisplay = ref(0) // New ref to display cart item count

// The global addToCart function to be provided
const addToCart = async (product: ProductForCart) => {
  const item = {
    product_id: product.id,
    name: product.name,
    image: product.image,
    price: product.price,
    size: product.size || null,
    color: product.color || null,
    quantity: 1, // Always add 1 when clicking "Add to Cart" from product listing
  }

  if (user.value) {
    // User is logged in, send to backend
    try {
      await router.post('/cart/add', item, {
        onSuccess: () => {
          console.log('Product added to cart successfully (logged in)')
          // Optionally, show a success message or update cart UI
          // The CartPopup will re-fetch its data automatically
        },
        onError: (errors) => {
          console.error('Error adding product to cart (logged in):', errors)
          // Optionally, show an error message
        }
      })
    } catch (error) {
      console.error('Network error adding product to cart:', error)
    }
  } else {
    // User is not logged in, add to local storage
    const guestCart = JSON.parse(localStorage.getItem('guestCart') || '[]')
    const existingItemIndex = guestCart.findIndex(
      (cartItem: any) => 
        cartItem.product_id === item.product_id &&
        cartItem.size === item.size &&
        cartItem.color === item.color
    )

    if (existingItemIndex > -1) {
      guestCart[existingItemIndex].quantity += item.quantity
    } else {
      guestCart.push(item)
    }
    localStorage.setItem('guestCart', JSON.stringify(guestCart))
    console.log('Product added to guest cart successfully:', guestCart)
    // The CartPopup will re-fetch its data automatically
  }
  cartOpen.value = true // Open cart popup after adding item
}

provide('addToCart', addToCart)



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
</style>
