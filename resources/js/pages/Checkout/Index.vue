<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
        <p class="mt-2 text-gray-600">Complete your order</p>
      </div>

      <form @submit.prevent="submitOrder" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2">
          <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
              <h2 class="text-lg font-medium text-gray-900">Checkout Information</h2>
            </div>
            
            <div class="px-6 py-6">
              <!-- Guest Checkout Form -->
              <div v-if="!user" class="space-y-6">
                <div>
                  <h3 class="text-lg font-medium text-gray-900 mb-4">Guest Checkout</h3>
                  <p class="mt-2 text-gray-600">Enter your details to complete the order</p>
                </div>

                <!-- Contact Information -->
                <div class="space-y-4">
                  <h4 class="text-md font-medium text-gray-900">Contact Information</h4>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                      <input type="text" id="first_name" v-model="checkoutForm.first_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                      <p v-if="checkoutForm.errors.first_name" class="text-sm text-red-600 mt-1">{{ checkoutForm.errors.first_name }}</p>
                    </div>
                    <div>
                      <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                      <input type="text" id="last_name" v-model="checkoutForm.last_name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                      <p v-if="checkoutForm.errors.last_name" class="text-sm text-red-600 mt-1">{{ checkoutForm.errors.last_name }}</p>
                    </div>
                    <div class="md:col-span-2">
                      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                      <input type="email" id="email" v-model="checkoutForm.email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                      <p v-if="checkoutForm.errors.email" class="text-sm text-red-600 mt-1">{{ checkoutForm.errors.email }}</p>
                    </div>
                    <div class="md:col-span-2">
                      <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                      <input type="tel" id="phone_number" v-model="checkoutForm.phone_number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                      <p v-if="checkoutForm.errors.phone_number" class="text-sm text-red-600 mt-1">{{ checkoutForm.errors.phone_number }}</p>
                    </div>
                  </div>
                </div>

                <!-- Delivery Address -->
                <div class="space-y-4">
                  <h4 class="text-md font-medium text-gray-900">Delivery Address</h4>
                  <div class="space-y-4">
                    <div>
                      <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                      <textarea id="address" v-model="checkoutForm.address" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                      <p v-if="checkoutForm.errors.address" class="text-sm text-red-600 mt-1">{{ checkoutForm.errors.address }}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                      <div>
                        <label for="county" class="block text-sm font-medium text-gray-700">County</label>
                        <input type="text" id="county" v-model="checkoutForm.county" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p v-if="checkoutForm.errors.county" class="text-sm text-red-600 mt-1">{{ checkoutForm.errors.county }}</p>
                      </div>
                      <div>
                        <label for="town" class="block text-sm font-medium text-gray-700">Town</label>
                        <input type="text" id="town" v-model="checkoutForm.town" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p v-if="checkoutForm.errors.town" class="text-sm text-red-600 mt-1">{{ checkoutForm.errors.town }}</p>
                      </div>
                      <div>
                        <label for="landmark" class="block text-sm font-medium text-gray-700">Closest Landmark</label>
                        <input type="text" id="landmark" v-model="checkoutForm.landmark" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <p v-if="checkoutForm.errors.landmark" class="text-sm text-red-600 mt-1">{{ checkoutForm.errors.landmark }}</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Payment Method -->
                <div class="space-y-4">
                  <h4 class="text-md font-medium text-gray-900">Payment Method</h4>
                  <div class="space-y-3">
                    <label class="flex items-center">
                      <input type="radio" name="payment" value="mpesa" v-model="checkoutForm.payment_method" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                      <span class="ml-2 text-sm text-gray-700">M-Pesa</span>
                    </label>
                    <label class="flex items-center">
                      <input type="radio" name="payment" value="cash" v-model="checkoutForm.payment_method" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                      <span class="ml-2 text-sm text-gray-700">Cash on Delivery</span>
                    </label>
                  </div>
                  <p v-if="checkoutForm.errors.payment_method" class="text-sm text-red-600 mt-1">{{ checkoutForm.errors.payment_method }}</p>
                </div>
              </div>

              <!-- Authenticated User Checkout -->
              <div v-else class="space-y-6">
                <div>
                  <h3 class="text-lg font-medium text-gray-900 mb-4">Welcome back, {{ user.first_name }}!</h3>
                  <p class="mt-2 text-gray-600">Your order will be delivered to your registered address</p>
                </div>

                <!-- User Information Display (for reference, not editable here) -->
                <div class="bg-gray-50 rounded-lg p-4">
                  <h4 class="text-md font-medium text-gray-900 mb-2">Delivery Information</h4>
                  <p class="text-sm text-gray-600">{{ user.full_name }}</p>
                  <p class="text-sm text-gray-600">{{ user.email }}</p>
                  <p class="text-sm text-gray-600">{{ user.phone_number }}</p>
                  <p class="text-sm text-gray-600">{{ user.shopper?.address || 'N/A' }}</p>
                  <p class="text-sm text-gray-600">{{ user.shopper?.town || 'N/A' }}, {{ user.shopper?.county || 'N/A' }}</p>
                </div>

                <!-- Payment Method -->
                <div class="space-y-4">
                  <h4 class="text-md font-medium text-gray-900">Payment Method</h4>
                  <div class="space-y-3">
                    <label class="flex items-center">
                      <input type="radio" name="payment" value="mpesa" v-model="checkoutForm.payment_method" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                      <span class="ml-2 text-sm text-gray-700">M-Pesa</span>
                    </label>
                    <label class="flex items-center">
                      <input type="radio" name="payment" value="cash" v-model="checkoutForm.payment_method" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                      <span class="ml-2 text-sm text-gray-700">Cash on Delivery</span>
                    </label>
                  </div>
                  <p v-if="checkoutForm.errors.payment_method" class="text-sm text-red-600 mt-1">{{ checkoutForm.errors.payment_method }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">
          <div class="bg-white rounded-lg shadow sticky top-8">
            <div class="px-6 py-4 border-b border-gray-200">
              <h2 class="text-lg font-medium text-gray-900">Order Summary</h2>
            </div>
            
            <div class="px-6 py-6">
              <!-- Order Items -->
              <div v-if="cart && cart.total_items > 0" class="space-y-4">
                <div v-for="item in cart.cart_products" :key="item.id" class="flex justify-between text-sm">
                  <div>
                    <p class="font-medium text-gray-900">{{ item.product?.name || 'Product' }}</p>
                    <p class="text-gray-500">Qty: {{ item.quantity }}</p>
                  </div>
                  <p class="font-medium text-gray-900">${{ item.formatted_subtotal }}</p>
                </div>
              </div>

              <!-- Totals -->
              <div class="border-t border-gray-200 pt-4 mt-4 space-y-2">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Subtotal</span>
                  <span class="text-gray-900">${{ cart?.formatted_total_amount || '0.00' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-600">Delivery</span>
                  <span class="text-gray-900">$0.00</span>
                </div>
                <div class="flex justify-between text-lg font-medium border-t border-gray-200 pt-2">
                  <span>Total</span>
                  <span>${{ cart?.formatted_total_amount || '0.00' }}</span>
                </div>
              </div>

              <!-- Place Order Button -->
              <div class="mt-6">
                <button type="submit" :disabled="checkoutForm.processing" class="w-full bg-blue-600 border border-transparent rounded-md py-3 px-4 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                  {{ checkoutForm.processing ? 'Placing Order...' : 'Place Order' }}
                </button>
              </div>

              <!-- Security Notice -->
              <div class="mt-4 text-xs text-gray-500 text-center">
                <p>🔒 Your payment information is secure and encrypted</p>
              </div>
            </div>
          </div>
        </div>
      </form>

      <!-- Back to Cart -->
      <div class="mt-8 text-center">
        <a href="/cart" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
          ← Back to Cart
        </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { usePage, useForm, router } from '@inertiajs/vue3' // Import router

// Declare `route` as a global function to resolve TypeScript error if Ziggy is globally available
declare function route(name: string, params?: any): string;

interface UserShopper {
  address: string | null; // Assuming address is directly on shopper
  city: string | null;    // Assuming city is directly on shopper
  county: string | null;
  town: string | null;
  closest_landmark: string | null;
}

interface User {
  id: number;
  first_name: string;
  last_name: string;
  email: string;
  phone_number: string;
  full_name: string;
  shopper: UserShopper | null; // Shopper profile linked to user
}

interface CartProductItem {
  id: number;
  product_id: number;
  quantity: number;
  unit_price: number;
  subtotal: number;
  formatted_subtotal: string;
  product: {
    name: string;
  };
}

interface CartData {
  id: number;
  total_items: number;
  total_amount: number;
  formatted_total_amount: string;
  cart_products: CartProductItem[];
}

const page = usePage()
const user = computed<User | null>(() => (page.props as any).user)

defineProps<{ cart: CartData }>()

const checkoutForm = useForm({
  first_name: user.value?.first_name || '',
  last_name: user.value?.last_name || '',
  email: user.value?.email || '',
  phone_number: user.value?.phone_number || '',
  address: user.value?.shopper?.address || '', 
  city: user.value?.shopper?.city || '', 
  county: user.value?.shopper?.county || '',
  town: user.value?.shopper?.town || '',
  landmark: user.value?.shopper?.closest_landmark || '',
  payment_method: 'mpesa', // Default payment method
})

const submitOrder = () => {
  checkoutForm.post(route('cart.checkout'), {
    onSuccess: (page) => {
      console.log('Order submitted successfully!', page)
      const props = page.props as any
      if (props.flash?.success) {
        const orderId = props.order_id
        if (orderId) {
          router.visit(route('order.confirmation', { order: orderId }));
        } else {
          alert(props.flash.success);
        }
      }
    },
    onError: (errors) => {
      console.error('Order submission failed:', errors)
      alert('Failed to place order. Please check the form for errors.')
    },
  })
}

// Removed the old export default block as we are using script setup
</script>
