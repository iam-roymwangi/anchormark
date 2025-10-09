<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
        <p class="mt-2 text-gray-600">Review your items before checkout</p>
      </div>

      <!-- Cart Content -->
      <div v-if="cart && cart.total_items > 0" class="bg-white rounded-lg shadow">
        <!-- Cart Items -->
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-medium text-gray-900">Cart Items ({{ totals.items }})</h2>
        </div>

        <div class="divide-y divide-gray-200">
          <div v-for="item in items" :key="item.id" class="px-6 py-4">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                  <div class="w-16 h-16 bg-gray-200 rounded-md"></div>
                </div>
                <div>
                  <h3 class="text-sm font-medium text-gray-900">{{ item.product?.name || 'Product Name' }}</h3>
                  <p class="text-sm text-gray-500">Quantity: {{ item.quantity }}</p>
                  <p class="text-sm text-gray-900">${{ item.formatted_unit_price }}</p>
                </div>
              </div>
              <div class="flex items-center space-x-4">
                <div class="text-right">
                  <p class="text-sm font-medium text-gray-900">${{ item.formatted_subtotal }}</p>
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
              <p class="text-lg font-medium text-gray-900">Total: ${{ totals.formatted_amount }}</p>
              <p class="text-sm text-gray-500">{{ totals.items }} items</p>
            </div>
            <div class="space-x-4">
              <button class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Update Prices
              </button>
              <button class="px-6 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700">
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

<script>
import { Head } from '@inertiajs/vue3'

export default {
  name: 'CartIndex',
  components: {
    Head
  },
  props: {
    cart: Object,
    items: Array,
    priceChanges: Array,
    totals: Object,
    canCheckout: Boolean
  },
  layout: 'public' // or whatever layout you're using
}
</script>
