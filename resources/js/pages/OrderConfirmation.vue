<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 bg-white rounded-lg shadow-lg p-8">
      <div class="text-center mb-8">
        <CheckCircleIcon class="w-20 h-20 text-green-500 mx-auto mb-4" />
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Order Placed Successfully!</h1>
        <p class="text-lg text-gray-600">Thank you for your purchase. Your order has been confirmed.</p>
      </div>

      <div v-if="order" class="space-y-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
          <p class="text-sm text-green-800 font-medium">We've sent a confirmation email to <strong>{{ order.customer_email }}</strong> with your order details.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
            <p class="text-gray-700 mb-2"><strong>Order Number:</strong> {{ order.order_number }}</p>
            <p class="text-gray-700 mb-2"><strong>Order Date:</strong> {{ formatDate(order.created_at) }}</p>
            <p class="text-gray-700 mb-2"><strong>Total Amount:</strong> Ksh. {{ order.total_amount }}</p>
            <p class="text-gray-700 mb-2"><strong>Payment Status:</strong> <span class="capitalize">{{ order.payment_status }}</span></p>
            <p class="text-gray-700 mb-2"><strong>Order Status:</strong> <span class="capitalize">{{ order.order_status }}</span></p>
          </div>
          <div>
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Delivery Information</h2>
            <p class="text-gray-700 mb-2"><strong>Address:</strong> {{ order.delivery_address }}</p>
            <p v-if="order.delivery_city" class="text-gray-700 mb-2"><strong>City:</strong> {{ order.delivery_city }}</p>
            <p v-if="order.delivery_county" class="text-gray-700 mb-2"><strong>County:</strong> {{ order.delivery_county }}</p>
            <p v-if="order.delivery_town" class="text-gray-700 mb-2"><strong>Town:</strong> {{ order.delivery_town }}</p>
            <p v-if="order.delivery_phone" class="text-gray-700 mb-2"><strong>Phone:</strong> {{ order.delivery_phone }}</p>
          </div>
        </div>

        <div class="border-t border-gray-200 pt-6 mt-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Items Ordered</h2>
          <div class="space-y-4">
            <div v-for="item in order.items" :key="item.id" class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
              <div>
                <p class="font-medium text-gray-900">{{ item.product?.name || 'Product Name' }}</p>
                <p class="text-sm text-gray-600">Qty: {{ item.quantity }} x Ksh. {{ item.price }}</p>
              </div>
              <p class="font-semibold text-gray-900">Ksh. {{ item.subtotal.toFixed(2) }}</p>
            </div>
          </div>
        </div>

        <div class="mt-8 text-center space-x-4">
          <a :href="route('home')" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Continue Shopping
          </a>
          <a :href="route('orders.show', order.id)" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            View My Orders
          </a>
        </div>
      </div>
      <div v-else class="text-center py-10">
        <p class="text-lg text-gray-600">Order details could not be loaded.</p>
        <a :href="route('home')" class="mt-6 inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            Go to Homepage
          </a>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { CheckCircleIcon } from '@heroicons/vue/24/outline'
import { format } from 'date-fns'

declare function route(name: string, params?: any): string;

interface OrderItemData {
  id: number;
  product_id: number;
  quantity: number;
  price: number;
  subtotal: number;
  product?: { // Product relation might be loaded or not
    name: string;
  };
}

interface OrderData {
  id: number;
  shopper_id: number | null;
  order_number: string;
  total_amount: number;
  order_status: string;
  payment_status: string;
  delivery_address: string;
  delivery_city: string | null;
  delivery_county: string | null; // Assuming these are passed in the order object
  delivery_town: string | null;   // Assuming these are passed in the order object
  delivery_phone: string | null;
  customer_email: string; // Assuming we pass customer email to the confirmation page
  created_at: string;
  items: OrderItemData[];
}

const page = usePage()
const order = computed<OrderData | null>(() => (page.props as any).order)

const formatDate = (dateString: string) => {
  return format(new Date(dateString), 'MMM dd, yyyy')
}
</script>
