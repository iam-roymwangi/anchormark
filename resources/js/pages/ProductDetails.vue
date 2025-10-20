<template>
  <div class="min-h-screen bg-[#F5F5F0]">
    <PublicLayout>

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 pt-24">
      <div class="flex items-center space-x-2 text-sm text-[#333333]">
        <a href="/" class="hover:text-[#C09930] transition-colors">Home</a>
        <template v-if="product && product.category">
          <ChevronRight :size="16" />
          <a :href="`/products?category=${product.category.slug}`" class="hover:text-[#C09930] transition-colors">{{ product.category.name }}</a>
        </template>
        <ChevronRight :size="16" />
        <span class="text-[#C09930]">{{ product.name }}</span>
      </div>
    </div>

    <!-- Product Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Image Gallery -->
        <div class="space-y-4">
          <!-- Main Image with Zoom -->
          <div 
            class="relative bg-white rounded-lg overflow-hidden aspect-square cursor-zoom-in"
            @mouseenter="showZoom = true"
            @mouseleave="showZoom = false"
            @mousemove="handleMouseMove"
          >
            <img 
              :src="selectedImage" 
              :alt="product.name"
              class="w-full h-full object-cover transition-transform duration-300"
              :class="{ 'scale-150': showZoom }"
              :style="zoomStyle"
            />
            <div 
              v-if="product.badge"
              class="absolute top-4 left-4 bg-[#C09930] text-white px-3 py-1 rounded-full text-sm font-medium"
            >
              {{ product.badge }}
            </div>
          </div>

          <!-- Thumbnails -->
          <div class="grid grid-cols-4 gap-4">
            <button
              v-for="(image, index) in product.images"
              :key="index"
              @click="selectImage(image)"
              class="aspect-square bg-white rounded-lg overflow-hidden border-2 transition-all duration-300 hover:border-[#C09930]"
              :class="selectedImage === image ? 'border-[#C09930]' : 'border-transparent'"
            >
              <img :src="image" :alt="`Product view ${index + 1}`" class="w-full h-full object-cover" />
            </button>
          </div>
        </div>

        <!-- Product Details -->
        <div class="space-y-6">
          <div>
            <h1 class="text-4xl md:text-5xl font-serif text-[#333333] mb-2">
              {{ product.name }}
            </h1>
            <p class="text-[#666666] text-sm">SKU: {{ product.sku }}</p>
          </div>

          <!-- Rating -->
          <div class="flex items-center space-x-2">
            <div class="flex">
              <Star 
                v-for="i in 5" 
                :key="i"
                :size="20"
                :class="i <= product.rating ? 'fill-[#C09930] text-[#C09930]' : 'text-gray-300'"
              />
            </div>
            <span class="text-[#333333]">{{ product.rating }} ({{ product.reviewCount }} reviews)</span>
          </div>

          <!-- Price -->
          <div class="flex items-baseline space-x-3">
            <span class="text-4xl font-bold text-[#C09930]">${{ product.price }}</span>
            <span v-if="product.originalPrice" class="text-xl text-gray-400 line-through">
              ${{ product.originalPrice }}
            </span>
          </div>

          <!-- Short Description -->
          <p class="text-[#666666] leading-relaxed">
            {{ product.shortDescription }}
          </p>

          <!-- Options -->
          <div class="space-y-4">
            <!-- Size Selection -->
            <div>
              <label class="block text-sm font-medium text-[#333333] mb-2">Size</label>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="size in product.sizes"
                  :key="size"
                  @click="selectedSize = size"
                  class="px-4 py-2 border-2 rounded-lg transition-all duration-300"
                  :class="selectedSize === size 
                    ? 'border-[#C09930] bg-[#C09930] text-white' 
                    : 'border-gray-300 hover:border-[#C09930] text-black'"
                >
                  {{ size }}
                </button>
              </div>
            </div>

            <!-- Color Selection -->
            <div>
              <label class="block text-sm font-medium text-[#333333] mb-2">Color</label>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="color in product.colors"
                  :key="color.name"
                  @click="selectedColor = color.name"
                  class="w-10 h-10 rounded-full border-2 transition-all duration-300"
                  :style="{ backgroundColor: color.hex }"
                  :class="selectedColor === color.name ? 'border-[#C09930] ring-2 ring-[#C09930] ring-offset-2' : 'border-gray-300'"
                  :title="color.name"
                />
              </div>
            </div>

            <!-- Quantity -->
            <div>
              <label class="block text-sm font-medium text-[#333333] mb-2">Quantity</label>
              <div class="flex items-center space-x-3">
                <button
                  @click="decreaseQuantity"
                  class="w-10 h-10 border-2 border-gray-300 rounded-lg hover:border-[#C09930] transition-colors text-black"
                >
                  <Minus :size="16" class="mx-auto" />
                </button>
                <input
                  v-model.number="quantity"
                  type="number"
                  min="1"
                  class="w-20 text-center border-2 border-gray-300 rounded-lg py-2 focus:border-[#C09930] focus:outline-none text-black"
                />
                <button
                  @click="increaseQuantity"
                  class="w-10 h-10 border-2 border-gray-300 rounded-lg hover:border-[#C09930] transition-colors text-black"
                >
                  <Plus :size="16" class="mx-auto" />
                </button>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="space-y-3">
            <button 
              @click="addToCart"
              class="w-full bg-[#C09930] text-white py-4 rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02]"
            >
              Add to Cart
            </button>
            <button class="w-full border-2 border-[#C09930] text-[#C09930] py-4 rounded-lg font-medium hover:bg-[#C09930] hover:text-white transition-all duration-300">
              Request Quote
            </button>
          </div>

          <!-- Features -->
          <div class="grid grid-cols-2 gap-4 pt-6 border-t border-gray-300">
            <div class="flex items-center space-x-2">
              <Truck :size="20" class="text-[#C09930]" />
              <span class="text-sm text-[#666666]">Free Shipping</span>
            </div>
            <div class="flex items-center space-x-2">
              <Shield :size="20" class="text-[#C09930]" />
              <span class="text-sm text-[#666666]">2 Year Warranty</span>
            </div>
            <div class="flex items-center space-x-2">
              <Package :size="20" class="text-[#C09930]" />
              <span class="text-sm text-[#666666]">Easy Returns</span>
            </div>
            <div class="flex items-center space-x-2">
              <Award :size="20" class="text-[#C09930]" />
              <span class="text-sm text-[#666666]">Premium Quality</span>
            </div>
          </div>

          <!-- Payment Options -->
          <div class="pt-6 border-t border-gray-300">
            <p class="text-sm text-[#666666] mb-3">We accept:</p>
            <div class="flex items-center space-x-4">
              <CreditCard :size="32" class="text-[#333333]" />
              <span class="text-sm text-[#666666]">Visa, Mastercard, Amex, PayPal</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs Section -->
      <div class="mt-16">
        <div class="border-b border-gray-300">
          <div class="flex space-x-8">
            <button
              v-for="tab in tabs"
              :key="tab"
              @click="activeTab = tab"
              class="pb-4 font-medium transition-all duration-300 relative"
              :class="activeTab === tab ? 'text-[#C09930]' : 'text-[#666666] hover:text-[#C09930]'"
            >
              {{ tab }}
              <div
                v-if="activeTab === tab"
                class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#C09930]"
              />
            </button>
          </div>
        </div>

        <div class="py-8">
          <!-- Description Tab -->
          <div v-if="activeTab === 'Description'" class="prose max-w-none">
            <p class="text-[#666666] leading-relaxed mb-4">
              {{ product.fullDescription }}
            </p>
            <h3 class="text-2xl font-serif text-[#333333] mt-6 mb-4">Key Features</h3>
            <ul class="space-y-2">
              <li v-for="feature in product.features" :key="feature" class="flex items-start space-x-2">
                <Check :size="20" class="text-[#C09930] mt-1 flex-shrink-0" />
                <span class="text-[#666666]">{{ feature }}</span>
              </li>
            </ul>
          </div>

          <!-- Specifications Tab -->
          <div v-if="activeTab === 'Specifications'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
              v-for="(value, key) in product.specifications"
              :key="key"
              class="flex justify-between py-3 border-b border-gray-200"
            >
              <span class="font-medium text-[#333333]">{{ key }}</span>
              <span class="text-[#666666]">{{ value }}</span>
            </div>
          </div>

          <!-- Reviews Tab -->
          <div v-if="activeTab === 'Reviews'" class="space-y-8">
            <!-- Review Summary -->
            <div class="bg-white rounded-lg p-6">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                  <div class="text-5xl font-bold text-[#C09930] mb-2">{{ product.rating }}</div>
                  <div class="flex justify-center mb-2">
                    <Star 
                      v-for="i in 5" 
                      :key="i"
                      :size="20"
                      :class="i <= product.rating ? 'fill-[#C09930] text-[#C09930]' : 'text-gray-300'"
                    />
                  </div>
                  <p class="text-sm text-[#666666]">Based on {{ product.reviewCount }} reviews</p>
                </div>
                <div class="md:col-span-2 space-y-2">
                  <div v-for="star in [5, 4, 3, 2, 1]" :key="star" class="flex items-center space-x-2">
                    <span class="text-sm text-[#666666] w-12">{{ star }} star</span>
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                      <div 
                        class="bg-[#C09930] h-2 rounded-full transition-all duration-500"
                        :style="{ width: `${getStarPercentage(star)}%` }"
                      />
                    </div>
                    <span class="text-sm text-[#666666] w-12">{{ getStarCount(star) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Individual Reviews -->
            <div class="space-y-6">
              <div
                v-for="review in product.reviews"
                :key="review.id"
                class="bg-white rounded-lg p-6 transition-all duration-300 hover:shadow-lg"
              >
                <div class="flex items-start justify-between mb-4">
                  <div>
                    <h4 class="font-medium text-[#333333]">{{ review.author }}</h4>
                    <p class="text-sm text-[#666666]">{{ review.date }}</p>
                  </div>
                  <div class="flex">
                    <Star 
                      v-for="i in 5" 
                      :key="i"
                      :size="16"
                      :class="i <= review.rating ? 'fill-[#C09930] text-[#C09930]' : 'text-gray-300'"
                    />
                  </div>
                </div>
                <p class="text-[#666666] leading-relaxed">{{ review.comment }}</p>
                <div v-if="review.verified" class="mt-3 flex items-center space-x-1 text-sm text-[#C09930]">
                  <Check :size="16" />
                  <span>Verified Purchase</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Shipping Tab -->
          <div v-if="activeTab === 'Shipping'" class="space-y-6">
            <div class="bg-white rounded-lg p-6">
              <h3 class="text-xl font-serif text-[#333333] mb-4">Shipping Information</h3>
              <div class="space-y-4 text-[#666666]">
                <p>We offer free standard shipping on all orders over $500.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <h4 class="font-medium text-[#333333] mb-2">Standard Shipping</h4>
                    <p class="text-sm">5-7 business days - Free over $500</p>
                  </div>
                  <div>
                    <h4 class="font-medium text-[#333333] mb-2">Express Shipping</h4>
                    <p class="text-sm">2-3 business days - $49.99</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Similar Products -->
      <div class="mt-16">
        <h2 class="text-3xl md:text-4xl font-serif text-[#333333] mb-8 text-center">
          Similar Products
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="similar in similarProducts"
            :key="similar.id"
            class="group bg-white rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl cursor-pointer"
          >
            <div class="aspect-square overflow-hidden bg-[#F5F5F0]">
              <img
                :src="similar.image"
                :alt="similar.name"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              />
            </div>
            <div class="p-4">
              <h3 class="font-medium text-[#333333] mb-2 group-hover:text-[#C09930] transition-colors">
                {{ similar.name }}
              </h3>
              <div class="flex items-center justify-between">
                <span class="text-lg font-bold text-[#C09930]">${{ similar.price }}</span>
                <div class="flex">
                  <Star 
                    v-for="i in 5" 
                    :key="i"
                    :size="14"
                    :class="i <= similar.rating ? 'fill-[#C09930] text-[#C09930]' : 'text-gray-300'"
                  />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    </PublicLayout>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { 
   ChevronRight, Star, Minus, Plus, 
  Truck, Shield, Package, Award, CreditCard, Check 
} from 'lucide-vue-next'
import PublicLayout from '@/layouts/PublicLayout.vue'
import { usePage, router } from '@inertiajs/vue3'

interface User {
  id: number;
  name: string;
  email: string;
}

interface Category {
  id: number
  name: string
  slug: string
}

interface ProductProp {
  id: number
  name: string
  sku: string
  price: number
  originalPrice?: number
  rating: number
  reviewCount: number
  shortDescription: string
  fullDescription: string
  badge?: string
  images: string[]
  sizes: string[]
  colors: { name: string; hex: string }[]
  features: string[]
  specifications: Record<string, string>
  reviews: Review[]
  category: Category // Added category
}

interface Review {
  id: number
  author: string
  date: string
  rating: number
  comment: string
  verified: boolean
}

interface SimilarProduct {
  id: number
  name: string
  price: number
  rating: number
  image: string
}

const page = usePage()
const user = computed<User | null>(() => page.props.auth?.user as User | null)

const props = defineProps<{
  product: ProductProp
}>()

const product = ref<ProductProp>(props.product)

const similarProducts = ref<SimilarProduct[]>([
  {
    id: 2,
    name: 'Luxury Silk Pillowcase Set',
    price: 89,
    rating: 4.9,
    image: 'https://images.ctfassets.net/h81st780aesh/3p269F8scsqoNoyIonFxTT/4796d33fc3eb4e7deacbec577fe48d06/restaurant-decor-ideas.jpeg'
  },
  {
    id: 3,
    name: 'Hotel Collection Bed Sheets',
    price: 149,
    rating: 4.7,
    image: 'https://images.ctfassets.net/h81st780aesh/3p269F8scsqoNoyIonFxTT/4796d33fc3eb4e7deacbec577fe48d06/restaurant-decor-ideas.jpeg'
  },
  {
    id: 4,
    name: 'Premium Down Comforter',
    price: 399,
    rating: 4.8,
    image: 'https://images.ctfassets.net/h81st780aesh/3p269F8scsqoNoyIonFxTT/4796d33fc3eb4e7deacbec577fe48d06/restaurant-decor-ideas.jpeg'
  },
  {
    id: 5,
    name: 'Bamboo Mattress Protector',
    price: 79,
    rating: 4.6,
    image: 'https://images.ctfassets.net/h81st780aesh/3p269F8scsqoNoyIonFxTT/4796d33fc3eb4e7deacbec577fe48d06/restaurant-decor-ideas.jpeg'
  }
])

const selectedImage = ref(props.product.images[0] || '')
const selectedSize = ref(props.product.sizes[0] || '')
const selectedColor = ref(props.product.colors[0]?.name || '')
const quantity = ref(1)
const activeTab = ref('Description')
const tabs = ['Description', 'Specifications', 'Reviews', 'Shipping']
const showZoom = ref(false)
const zoomStyle = ref({})

// Watch for changes in the product prop and update reactive data
watch(() => props.product, (newProduct) => {
  product.value = newProduct
  selectedImage.value = newProduct.images[0] || ''
  selectedSize.value = newProduct.sizes[0] || ''
  selectedColor.value = newProduct.colors[0]?.name || ''
  quantity.value = 1 // Reset quantity when product changes
  activeTab.value = 'Description' // Reset active tab
}, { deep: true, immediate: true })

const selectImage = (image: string) => {
  selectedImage.value = image
}

const increaseQuantity = () => {
  quantity.value++
}

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--
  }
}

const handleMouseMove = (event: MouseEvent) => {
  if (!showZoom.value) return
  
  const target = event.currentTarget as HTMLElement
  const rect = target.getBoundingClientRect()
  const x = ((event.clientX - rect.left) / rect.width) * 100
  const y = ((event.clientY - rect.top) / rect.height) * 100
  
  zoomStyle.value = {
    transformOrigin: `${x}% ${y}%`
  }
}

const getStarPercentage = (star: number): number => {
  const distribution: Record<number, number> = {
    5: 75,
    4: 15,
    3: 7,
    2: 2,
    1: 1
  }
  return distribution[star] || 0
}

const getStarCount = (star: number): number => {
  const percentage = getStarPercentage(star)
  return Math.round((percentage / 100) * product.value.reviewCount)
}

const addToCart = async () => {
  const item = {
    product_id: product.value.id,
    name: product.value.name,
    image: product.value.images[0],
    price: product.value.price,
    size: selectedSize.value,
    color: selectedColor.value,
    quantity: quantity.value,
  }

  if (user.value) {
    // User is logged in, send to backend
    try {
      await router.post('/cart/add', item, {
        onSuccess: () => {
          console.log('Product added to cart successfully (logged in)')
          // Optionally, show a success message or update cart UI
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
    // User is not logged in, add to session storage
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
    // Optionally, show a success message or update cart UI
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600&display=swap');

.font-serif {
  font-family: 'Playfair Display', serif;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
}
</style>
