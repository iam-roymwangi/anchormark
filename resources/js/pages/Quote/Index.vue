<template>
  <div class="min-h-screen bg-[#F5F5F0]">
    <PublicLayout>
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pt-24">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl md:text-4xl font-serif text-[#333333] mb-2">Request a Quote</h1>
          <p class="text-[#666666]">Complete the form below to request a quote for your cart items</p>
        </div>

        <!-- Step Progress Indicator -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
          <div class="flex items-center justify-between">
            <!-- Step 1 -->
            <div class="flex items-center flex-1">
              <div class="flex flex-col items-center flex-1">
                <div
                  class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300"
                  :class="quoteStep >= 1 ? 'bg-[#AE8625] text-white' : 'bg-gray-300 text-gray-600'"
                >
                  <User v-if="quoteStep === 1" :size="20" />
                  <Check v-else-if="quoteStep > 1" :size="20" />
                  <span v-else class="text-sm font-bold">1</span>
                </div>
                <span class="mt-2 text-xs font-medium" :class="quoteStep >= 1 ? 'text-[#AE8625]' : 'text-gray-500'">
                  Customer Details
                </span>
              </div>
              <div
                class="h-1 flex-1 mx-2 transition-all duration-300"
                :class="quoteStep >= 2 ? 'bg-[#AE8625]' : 'bg-gray-300'"
              ></div>
            </div>

            <!-- Step 2 -->
            <div class="flex items-center flex-1">
              <div class="flex flex-col items-center flex-1">
                <div
                  class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300"
                  :class="quoteStep >= 2 ? 'bg-[#AE8625] text-white' : 'bg-gray-300 text-gray-600'"
                >
                  <FileText v-if="quoteStep === 2" :size="20" />
                  <Check v-else-if="quoteStep > 2" :size="20" />
                  <span v-else class="text-sm font-bold">2</span>
                </div>
                <span class="mt-2 text-xs font-medium" :class="quoteStep >= 2 ? 'text-[#AE8625]' : 'text-gray-500'">
                  Review Details
                </span>
              </div>
              <div
                class="h-1 flex-1 mx-2 transition-all duration-300"
                :class="quoteStep >= 3 ? 'bg-[#AE8625]' : 'bg-gray-300'"
              ></div>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col items-center flex-1">
              <div
                class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300"
                :class="quoteStep >= 3 ? 'bg-[#AE8625] text-white' : 'bg-gray-300 text-gray-600'"
              >
                <Send v-if="quoteStep === 3" :size="20" />
                <Check v-else-if="quoteStep > 3" :size="20" />
                <span v-else class="text-sm font-bold">3</span>
              </div>
              <span class="mt-2 text-xs font-medium" :class="quoteStep >= 3 ? 'text-[#AE8625]' : 'text-gray-500'">
                Submit Request
              </span>
            </div>
          </div>
        </div>

        <!-- Form Content -->
        <div class="bg-white rounded-lg shadow-sm">
          <!-- Step 1: Customer Details -->
          <div v-if="quoteStep === 1" class="p-6 space-y-5">
            <!-- Cart Items Summary -->
            <div class="bg-[#F5F5F0] rounded-lg p-4 mb-6">
              <h3 class="font-medium text-[#333333] mb-3 flex items-center gap-2">
                <Package :size="20" class="text-[#AE8625]" />
                Cart Items Summary ({{ cartItems.length }})
              </h3>
              <div class="space-y-3">
                <div
                  v-for="(item, index) in cartItems"
                  :key="item.id || index"
                  class="flex items-start space-x-4 bg-white rounded-lg p-3"
                >
                  <img
                    v-if="item.image"
                    :src="item.image"
                    :alt="item.product?.name || item.name"
                    class="w-16 h-16 object-cover rounded-lg"
                  />
                  <div v-else class="w-16 h-16 bg-gray-200 rounded-lg"></div>
                  <div class="flex-1">
                    <h4 class="font-medium text-[#333333]">{{ item.product?.name || item.name }}</h4>
                    <p v-if="item.size || item.color" class="text-sm text-[#666666]">
                      {{ item.size }} {{ item.color ? '• ' + item.color : '' }}
                    </p>
                    <p class="text-sm text-[#666666]">Quantity: {{ item.quantity }}</p>
                    <p class="text-sm font-medium text-[#AE8625]">
                      {{ item.formatted_subtotal || `Ksh. ${(item.price * item.quantity).toFixed(2)}` }}
                    </p>
                  </div>
                </div>
                <div class="pt-3 border-t border-gray-300 flex justify-between items-center">
                  <span class="font-medium text-[#333333]">Total:</span>
                  <span class="text-lg font-bold text-[#AE8625]">
                    {{ formattedTotal }}
                  </span>
                </div>
              </div>
            </div>

            <h3 class="text-xl font-serif text-[#333333] mb-4">Customer Information</h3>

            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-[#333333] mb-2">
                Name <span class="text-red-500">*</span>
              </label>
              <input
                id="name"
                v-model="quoteForm.name"
                type="text"
                required
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333]"
                placeholder="Enter your full name"
              />
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-[#333333] mb-2">
                Email <span class="text-red-500">*</span>
              </label>
              <input
                id="email"
                v-model="quoteForm.email"
                type="email"
                required
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333]"
                placeholder="Enter your email address"
              />
            </div>

            <!-- Telephone -->
            <div>
              <label for="telephone" class="block text-sm font-medium text-[#333333] mb-2">
                Telephone <span class="text-red-500">*</span>
              </label>
              <input
                id="telephone"
                v-model="quoteForm.telephone"
                type="tel"
                required
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333]"
                placeholder="Enter your phone number"
              />
            </div>

            <!-- Company -->
            <div>
              <label for="company" class="block text-sm font-medium text-[#333333] mb-2">
                Company
              </label>
              <input
                id="company"
                v-model="quoteForm.company"
                type="text"
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333]"
                placeholder="Enter your company name (optional)"
              />
            </div>

            <!-- Item Description -->
            <div>
              <label for="itemDescription" class="block text-sm font-medium text-[#333333] mb-2">
                Item Description <span class="text-red-500">*</span>
              </label>
              <textarea
                id="itemDescription"
                v-model="quoteForm.itemDescription"
                rows="4"
                required
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333] resize-none"
                placeholder="Describe the items you need..."
              ></textarea>
              <p class="mt-1 text-xs text-[#666666]">
                Pre-filled with your cart items. You can modify as needed.
              </p>
            </div>

            <!-- Expected Delivery Date -->
            <div>
              <label for="deliveryDate" class="block text-sm font-medium text-[#333333] mb-2">
                Expected Delivery Date <span class="text-red-500">*</span>
              </label>
              <input
                id="deliveryDate"
                v-model="quoteForm.expectedDeliveryDate"
                type="date"
                required
                :min="minDeliveryDate"
                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333]"
              />
            </div>

            <!-- Step 1 Actions -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
              <button
                type="button"
                @click="goBackToCart"
                class="flex-1 px-6 py-3 border-2 border-gray-300 text-[#333333] rounded-lg font-medium hover:bg-gray-50 transition-all duration-300"
              >
                Back to Cart
              </button>
              <button
                type="button"
                @click="nextStep"
                class="flex-1 px-6 py-3 bg-[#AE8625] text-white rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2"
              >
                Continue
                <ArrowRight :size="20" />
              </button>
            </div>
          </div>

          <!-- Step 2: Review Details -->
          <div v-if="quoteStep === 2" class="p-6 space-y-6">
            <h3 class="text-xl font-serif text-[#333333] mb-4">Review Your Order</h3>

            <!-- Cart Items Details Section -->
            <div class="bg-[#F5F5F0] rounded-lg p-6">
              <h4 class="text-lg font-medium text-[#333333] mb-4 flex items-center gap-2">
                <Package :size="20" class="text-[#AE8625]" />
                Cart Items ({{ cartItems.length }})
              </h4>
              <div class="space-y-4">
                <div
                  v-for="(item, index) in cartItems"
                  :key="item.id || index"
                  class="bg-white rounded-lg p-4"
                >
                  <div class="flex items-start space-x-4">
                    <img
                      v-if="item.image"
                      :src="item.image"
                      :alt="item.product?.name || item.name"
                      class="w-24 h-24 object-cover rounded-lg"
                    />
                    <div v-else class="w-24 h-24 bg-gray-200 rounded-lg"></div>
                    <div class="flex-1">
                      <h5 class="font-medium text-[#333333]">{{ item.product?.name || item.name }}</h5>
                      <p v-if="item.product?.sku" class="text-sm text-[#666666]">SKU: {{ item.product.sku }}</p>
                      <div class="mt-2 space-y-1 text-sm">
                        <div class="flex justify-between">
                          <span class="text-[#666666]">Size:</span>
                          <span class="font-medium text-[#333333]">{{ item.size || 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-[#666666]">Color:</span>
                          <span class="font-medium text-[#333333]">{{ item.color || 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-[#666666]">Quantity:</span>
                          <span class="font-medium text-[#333333]">{{ item.quantity }}</span>
                        </div>
                        <div class="flex justify-between">
                          <span class="text-[#666666]">Unit Price:</span>
                          <span class="font-medium text-[#333333]">
                            {{ item.formatted_unit_price || `Ksh. ${item.price.toFixed(2)}` }}
                          </span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-300">
                          <span class="text-[#666666]">Subtotal:</span>
                          <span class="font-bold text-[#333333]">
                            {{ item.formatted_subtotal || `Ksh. ${(item.price * item.quantity).toFixed(2)}` }}
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="pt-4 border-t border-gray-300 flex justify-between items-center">
                  <span class="text-lg font-medium text-[#333333]">Total:</span>
                  <span class="text-2xl font-bold text-[#AE8625]">{{ formattedTotal }}</span>
                </div>
              </div>
            </div>

            <!-- Customer Details Section -->
            <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
              <h4 class="text-lg font-medium text-[#333333] mb-4 flex items-center gap-2">
                <User :size="20" class="text-[#AE8625]" />
                Customer Information
              </h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <p class="text-sm text-[#666666] mb-1">Name</p>
                  <p class="font-medium text-[#333333]">{{ quoteForm.name }}</p>
                </div>
                <div>
                  <p class="text-sm text-[#666666] mb-1">Email</p>
                  <p class="font-medium text-[#333333]">{{ quoteForm.email }}</p>
                </div>
                <div>
                  <p class="text-sm text-[#666666] mb-1">Telephone</p>
                  <p class="font-medium text-[#333333]">{{ quoteForm.telephone }}</p>
                </div>
                <div v-if="quoteForm.company">
                  <p class="text-sm text-[#666666] mb-1">Company</p>
                  <p class="font-medium text-[#333333]">{{ quoteForm.company }}</p>
                </div>
                <div class="md:col-span-2">
                  <p class="text-sm text-[#666666] mb-1">Expected Delivery Date</p>
                  <p class="font-medium text-[#333333]">{{ formatDate(quoteForm.expectedDeliveryDate) }}</p>
                </div>
                <div class="md:col-span-2">
                  <p class="text-sm text-[#666666] mb-1">Item Description</p>
                  <p class="font-medium text-[#333333] whitespace-pre-line">{{ quoteForm.itemDescription }}</p>
                </div>
              </div>
            </div>

            <!-- Step 2 Actions -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
              <button
                type="button"
                @click="prevStep"
                class="flex-1 px-6 py-3 border-2 border-gray-300 text-[#333333] rounded-lg font-medium hover:bg-gray-50 transition-all duration-300 flex items-center justify-center gap-2"
              >
                <ArrowLeft :size="20" />
                Back
              </button>
              <button
                type="button"
                @click="nextStep"
                class="flex-1 px-6 py-3 bg-[#AE8625] text-white rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2"
              >
                Continue to Submit
                <ArrowRight :size="20" />
              </button>
            </div>
          </div>

          <!-- Step 3: Submit Request -->
          <div v-if="quoteStep === 3" class="p-6 space-y-6">
            <div v-if="!quoteSubmitted" class="text-center py-8">
              <div class="w-20 h-20 bg-[#F5F5F0] rounded-full flex items-center justify-center mx-auto mb-4">
                <Send :size="40" class="text-[#AE8625]" />
              </div>
              <h3 class="text-2xl font-serif text-[#333333] mb-2">Ready to Submit?</h3>
              <p class="text-[#666666] mb-6">
                Your order has been sent to our team. We'll contact you shortly with a detailed quote.
              </p>
              <div class="bg-[#F5F5F0] rounded-lg p-4 mb-6 text-left">
                <p class="text-sm text-[#666666] mb-2">
                  <strong class="text-[#333333]">What happens next?</strong>
                </p>
                <ul class="text-sm text-[#666666] space-y-1 list-disc list-inside">
                  <li>You'll receive a confirmation email</li>
                  <li>Our team will review your request</li>
                  <li>We'll send you a detailed quote within 24-48 hours</li>
                  <li>You can download your quote invoice as PDF</li>
                </ul>
              </div>
            </div>

            <!-- Success State -->
            <div v-else class="text-center py-8">
              <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <Check :size="40" class="text-green-600" />
              </div>
              <h3 class="text-2xl font-serif text-[#333333] mb-2">Your order has been placed!</h3>
              <p class="text-[#666666] mb-6">
                Thank you for your request. We've sent a confirmation email to <strong>{{ quoteForm.email }}</strong>
              </p>
              <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-green-800">
                  ✓ Confirmation email sent to your inbox<br>
                  ✓ Our team has been notified<br>
                  ✓ You'll receive a detailed quote within 24-48 hours
                </p>
              </div>
              <div v-if="quoteInvoiceUrl" class="mb-6">
                <a
                  :href="quoteInvoiceUrl"
                  target="_blank"
                  class="inline-flex items-center gap-2 px-6 py-3 bg-[#AE8625] text-white rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02]"
                >
                  <Download :size="20" />
                  Download Quote Invoice (PDF)
                </a>
              </div>
            </div>

            <!-- Step 3 Actions -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4">
              <button
                v-if="!quoteSubmitted"
                type="button"
                @click="prevStep"
                class="flex-1 px-6 py-3 border-2 border-gray-300 text-[#333333] rounded-lg font-medium hover:bg-gray-50 transition-all duration-300 flex items-center justify-center gap-2"
              >
                <ArrowLeft :size="20" />
                Back
              </button>
              <button
                v-if="!quoteSubmitted"
                type="button"
                @click="submitQuote"
                :disabled="isSubmittingQuote"
                class="flex-1 px-6 py-3 bg-[#AE8625] text-white rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2"
              >
                <Loader2 v-if="isSubmittingQuote" :size="20" class="animate-spin" />
                <Send v-else :size="20" />
                {{ isSubmittingQuote ? 'Submitting...' : 'Place Order' }}
              </button>
              <button
                v-else
                type="button"
                @click="goToHome"
                class="flex-1 px-6 py-3 bg-[#AE8625] text-white rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02]"
              >
                Continue Shopping
              </button>
            </div>
          </div>
        </div>
      </div>
    </PublicLayout>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { 
  User, FileText, Send, Check, Download, ArrowLeft, ArrowRight,
  Package, Loader2
} from 'lucide-vue-next'
import PublicLayout from '@/layouts/PublicLayout.vue'
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
    sku?: string
  }
  formatted_unit_price?: string
  formatted_subtotal?: string
}

const page = usePage()
const user = computed<User | null>(() => page.props.auth?.user as User | null)

const props = defineProps<{
  items?: CartItem[]
  totals?: {
    items: number
    amount: number
    formatted_amount?: string
  }
}>()

const cartItems = ref<CartItem[]>([])
const quoteStep = ref(1) // 1: Customer Details, 2: Review, 3: Submit
const quoteSubmitted = ref(false)
const quoteInvoiceUrl = ref('')
const isSubmittingQuote = ref(false)

interface QuoteForm {
  name: string
  email: string
  telephone: string
  company: string
  itemDescription: string
  expectedDeliveryDate: string
}

const quoteForm = ref<QuoteForm>({
  name: '',
  email: '',
  telephone: '',
  company: '',
  itemDescription: '',
  expectedDeliveryDate: ''
})

// Calculate minimum delivery date (today)
const minDeliveryDate = computed(() => {
  const today = new Date()
  today.setDate(today.getDate() + 1) // Minimum 1 day from today
  return today.toISOString().split('T')[0]
})

// Calculate total
const formattedTotal = computed(() => {
  if (props.totals?.formatted_amount) {
    return props.totals.formatted_amount
  }
  const total = cartItems.value.reduce((sum, item) => {
    return sum + (item.price * item.quantity)
  }, 0)
  return `$${total.toFixed(2)}`
})

// Generate item description from cart items
const generateItemDescription = (): string => {
  if (cartItems.value.length === 0) return ''
  
  let description = 'Cart Items:\n\n'
  cartItems.value.forEach((item, index) => {
    description += `${index + 1}. ${item.product?.name || item.name}\n`
    if (item.product?.sku) {
      description += `   SKU: ${item.product.sku}\n`
    }
    if (item.size) {
      description += `   Size: ${item.size}\n`
    }
    if (item.color) {
      description += `   Color: ${item.color}\n`
    }
    description += `   Quantity: ${item.quantity}\n`
    description += `   Price: ${item.formatted_unit_price || `$${item.price.toFixed(2)}`}\n`
    description += `   Subtotal: ${item.formatted_subtotal || `$${(item.price * item.quantity).toFixed(2)}`}\n\n`
  })
  description += `Total: ${formattedTotal.value}`
  return description
}

onMounted(() => {
  // Load cart items from props or localStorage
  if (props.items && props.items.length > 0) {
    cartItems.value = props.items
  } else if (!user.value) {
    // For guest users, load from localStorage
    try {
      const guestCart = JSON.parse(localStorage.getItem('guestCart') || '[]') as CartItem[]
      cartItems.value = guestCart.map((item, index) => ({
        ...item,
        id: item.id || `guest-${Date.now()}-${index}`,
        formatted_unit_price: `Ksh. ${item.price.toFixed(2)}`,
        formatted_subtotal: `Ksh. ${(item.price * item.quantity).toFixed(2)}`,
        product: {
          name: item.name
        }
      }))
    } catch (error) {
      console.error('Error loading guest cart:', error)
    }
  } else {
    // For logged-in users, if no items in props, try to fetch from backend
    // This handles the case where the quote page is accessed directly
    if (cartItems.value.length === 0) {
      router.visit('/cart')
      return
    }
  }

  // Pre-fill item description
  quoteForm.value.itemDescription = generateItemDescription()
  
  // Pre-fill user info if logged in
  if (user.value) {
    quoteForm.value.name = user.value.name
    quoteForm.value.email = user.value.email
  }

  // If no items, redirect back to cart
  if (cartItems.value.length === 0) {
    router.visit('/cart')
  }
})

const goBackToCart = () => {
  router.visit('/cart')
}

const goToHome = () => {
  router.visit('/')
}

const nextStep = () => {
  // Validate current step before proceeding
  if (quoteStep.value === 1) {
    // Validate step 1 fields
    if (!quoteForm.value.name || !quoteForm.value.email || !quoteForm.value.telephone || !quoteForm.value.itemDescription || !quoteForm.value.expectedDeliveryDate) {
      alert('Please fill in all required fields')
      return
    }
  }
  
  if (quoteStep.value < 3) {
    quoteStep.value++
  }
}

const prevStep = () => {
  if (quoteStep.value > 1) {
    quoteStep.value--
  }
}

const formatDate = (dateString: string): string => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}

const submitQuote = async () => {
  isSubmittingQuote.value = true
  
  // Prepare quote data for all cart items
  const quoteData = {
    ...quoteForm.value,
    cart_items: cartItems.value.map(item => ({
      product_id: item.product_id,
      product_name: item.product?.name || item.name,
      sku: item.product?.sku || '',
      size: item.size || null,
      color: item.color || null,
      quantity: item.quantity,
      price: item.price,
      subtotal: (item.price * item.quantity).toFixed(2)
    })),
    total_amount: props.totals?.amount || cartItems.value.reduce((sum, item) => sum + (item.price * item.quantity), 0)
  }

  try {
    await router.post('/quotes/request-cart', quoteData, {
      onSuccess: (page: any) => {
        console.log('Order placed successfully', page)
        
        // Mark as submitted
        quoteSubmitted.value = true
        
        // Get invoice URL from response if available
        if (page?.props?.invoice_url) {
          quoteInvoiceUrl.value = page.props.invoice_url as string
        } else if (page?.props?.quote_id) {
          // Generate invoice URL if quote ID is provided
          quoteInvoiceUrl.value = `/quotes/${page.props.quote_id}/invoice`
        }
        
        // Clear cart after successful submission
        if (!user.value) {
          localStorage.removeItem('guestCart')
          window.dispatchEvent(new CustomEvent('cartUpdated'))
        }
      },
      onError: (errors) => {
        console.error('Error submitting quote request:', errors)
        console.error('Full error object:', JSON.stringify(errors, null, 2))
        
        // Get error message from various possible locations
        let errorMessage = 'There was an error placing your order. Please try again.'
        
        if (errors?.message) {
          errorMessage = errors.message
        } else if (typeof errors === 'string') {
          errorMessage = errors
        } else if (errors && typeof errors === 'object') {
          // Try to get first error message
          const firstError = Object.values(errors)[0]
          if (Array.isArray(firstError) && firstError.length > 0) {
            errorMessage = firstError[0]
          } else if (typeof firstError === 'string') {
            errorMessage = firstError
          }
        }
        
        alert('Error: ' + errorMessage)
        isSubmittingQuote.value = false
      },
      onFinish: () => {
        isSubmittingQuote.value = false
      }
    })
  } catch (error) {
    console.error('Network error placing order:', error)
    alert('Network error. Please check your connection and try again.')
    isSubmittingQuote.value = false
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
</style>

