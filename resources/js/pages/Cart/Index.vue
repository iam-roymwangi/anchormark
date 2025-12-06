<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
        <p class="mt-2 text-gray-600">Review your items before checkout</p>
      </div>

      <!-- Cart Content -->
      <div v-if="hasItems" class="bg-white rounded-lg shadow">
        <!-- Cart Items -->
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Cart Items ({{ displayTotals.items }})</h2>
        </div>

        <div class="divide-y divide-gray-200">
          <div v-for="item in displayItems" :key="item.id" class="px-6 py-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <img 
                    v-if="item.image" 
                    :src="item.image" 
                    :alt="item.product?.name || item.name"
                    class="w-16 h-16 object-cover rounded-md"
                  />
                  <div v-else class="w-16 h-16 bg-gray-200 rounded-md"></div>
                </div>
                <div>
                  <h3 class="text-sm font-medium text-gray-900">{{ item.product?.name || item.name }}</h3>
                  <p v-if="item.size || item.color" class="text-sm text-gray-500">
                    {{ item.size }} {{ item.color ? '• ' + item.color : '' }}
                  </p>
                  <p class="text-sm text-gray-500">Quantity: {{ item.quantity }}</p>
                  <p class="text-sm text-gray-900">{{ item.formatted_unit_price || `$${item.price.toFixed(2)}` }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <div class="text-right">
                  <p class="text-sm font-medium text-gray-900">{{ item.formatted_subtotal || `$${(item.price * item.quantity).toFixed(2)}` }}</p>
                </div>
                <button class="text-red-600 hover:text-red-800 text-sm">
                  Remove
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Cart Summary -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
          <div class="flex justify-between items-center">
            <div>
              <p class="text-lg font-medium text-gray-900">Total: {{ displayTotals.formatted_amount || `$${displayTotals.amount.toFixed(2)}` }}</p>
              <p class="text-sm text-gray-500">{{ displayTotals.items }} items</p>
            </div>
            <div class="space-x-4">
              <button v-if="user" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Update Prices
              </button>
              <button 
                ref="checkoutButton"
                @click="proceedToQuote"
                type="button"
                class="px-6 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!hasItems"
              >
                Proceed to Checkout
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty Cart -->
      <div v-else class="bg-white rounded-lg shadow text-center py-12">
        <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
          </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
        <p class="text-gray-500 mb-6">Add some items to get started</p>
        <a href="/products" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
          Continue Shopping
        </a>
      </div>

      <!-- Price Changes Alert -->
      <div v-if="priceChanges && priceChanges.length > 0" class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
        <div class="flex">
          <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
          </div>
          <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">Price Changes Detected</h3>
            <div class="mt-2 text-sm text-yellow-700">
              <p>Some items in your cart have price changes. Please review before checkout.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, nextTick } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

interface User {
  id: number;
  name: string;
  email: string;
}

interface CartItem {
  id: number | string
  product_id: number
  name: string
  price: number
  quantity: number
  image?: string
  size?: string | null
  color?: string | null
  product?: {
    name: string
  }
  formatted_unit_price?: string
  formatted_subtotal?: string
}

const page = usePage()
const user = computed<User | null>(() => page.props.auth?.user as User | null)

const props = defineProps<{
  cart?: object
  items?: CartItem[]
  priceChanges?: Array<any>
  totals?: {
    items: number
    amount: number
    formatted_amount?: string
  }
  canCheckout?: boolean
}>()

// For guest users, also check localStorage
const guestCartItems = ref<CartItem[]>([])
const checkoutButton = ref<HTMLButtonElement | null>(null)

const displayItems = computed(() => {
  // If user is logged in, use backend items
  if (user.value && props.items) {
    return props.items
  }
  // For guest users, use localStorage items
  return guestCartItems.value.length > 0 ? guestCartItems.value : (props.items || [])
})

const displayTotals = computed(() => {
  if (user.value && props.totals) {
    return props.totals
  }
  // Calculate totals from guest cart
  const items = guestCartItems.value
  const itemCount = items.reduce((total, item) => total + item.quantity, 0)
  const amount = items.reduce((total, item) => total + (item.price * item.quantity), 0)
  return {
    items: itemCount,
    amount: amount,
    formatted_amount: `$${amount.toFixed(2)}`
  }
})

const hasItems = computed(() => {
  if (user.value) {
    return props.cart && (props.cart as any).total_items > 0
  }
  return guestCartItems.value.length > 0
})

onMounted(() => {
  console.log('Cart component mounted!', {
    hasItems: hasItems.value,
    user: user.value,
    displayItemsCount: displayItems.value.length
  })
  
  // Load guest cart from localStorage if user is not logged in
  if (!user.value) {
    try {
      const guestCart = JSON.parse(localStorage.getItem('guestCart') || '[]') as CartItem[]
      // Ensure all items have required fields and format them
      guestCartItems.value = guestCart.map((item, index) => ({
        ...item,
        id: item.id || `guest-${Date.now()}-${index}`,
        formatted_unit_price: `$${item.price.toFixed(2)}`,
        formatted_subtotal: `$${(item.price * item.quantity).toFixed(2)}`,
        product: {
          name: item.name
        }
      }))
    } catch (error) {
      console.error('Error loading guest cart:', error)
    }
  }
  
  // Listen for cart updates
  window.addEventListener('cartUpdated', handleCartUpdate)
  
  // Add direct event listener as fallback (wait for DOM to be ready)
  nextTick(() => {
    if (checkoutButton.value) {
      console.log('Adding direct event listener to checkout button')
      checkoutButton.value.addEventListener('click', (e) => {
        e.preventDefault()
        e.stopPropagation()
        console.log('Direct event listener triggered!')
        proceedToQuote()
      })
    } else {
      console.warn('Checkout button ref is null')
    }
  })
})

const handleCartUpdate = () => {
  if (!user.value) {
    try {
      const guestCart = JSON.parse(localStorage.getItem('guestCart') || '[]') as CartItem[]
      guestCartItems.value = guestCart.map((item, index) => ({
        ...item,
        id: item.id || `guest-${Date.now()}-${index}`,
        formatted_unit_price: `$${item.price.toFixed(2)}`,
        formatted_subtotal: `$${(item.price * item.quantity).toFixed(2)}`,
        product: {
          name: item.name
        }
      }))
    } catch (error) {
      console.error('Error updating guest cart:', error)
    }
  }
}

const proceedToQuote = () => {
  console.log('=== proceedToQuote function called ===', { 
    hasItems: hasItems.value,
    router: typeof router,
    displayItems: displayItems.value.length,
    routerType: router?.constructor?.name
  })
  
  // Navigate to quote page
  if (!hasItems.value) {
    console.warn('Cannot proceed to checkout: cart is empty')
    alert('Your cart is empty. Please add items before proceeding to checkout.')
    return
  }
  
  console.log('Navigating to quote page...')
  
  // Use router.get() for GET requests (like other pages do)
  try {
    if (router && typeof router.get === 'function') {
      router.get('/quote', {}, {
        onStart: () => {
          console.log('Navigation started')
        },
        onFinish: () => {
          console.log('Navigation finished')
        },
        onError: (errors) => {
          console.error('Error navigating to quote page:', errors)
          alert('Error navigating to checkout. Please try again.')
        }
      })
    } else {
      console.warn('Router.get not available, using window.location')
      window.location.href = '/quote'
    }
  } catch (error) {
    console.error('Exception in proceedToQuote:', error)
    // Fallback
    window.location.href = '/quote'
  }
}
</script>
