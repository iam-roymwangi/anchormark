<template>
  <div class="min-h-screen bg-[#F5F5F0]">
    <PublicLayout>
      <div class="flex pt-8">
        <!-- Sidebar -->
        <ProductSidebar
          :is-open="sidebarOpen"
          :is-mobile="isMobile"
          :selected-category="selectedCategory"
          :categories="categoriesWithProducts"
          @toggle="sidebarOpen = !sidebarOpen"
          @select-category="selectCategory"
          @select-product="scrollToProduct"
        />

        <!-- Main Content -->
        <main class="w-full flex-1 transition-all duration-300">
          <!-- Hero Section -->
          <section class="px-4 py-12 sm:px-6 sm:py-16 md:py-20 lg:py-24">
            <div class="mx-auto max-w-4xl text-center">
              <h2
                class="animate-fade-in mb-4 font-serif text-3xl leading-tight text-[#333333] sm:mb-6 sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl"
              >
                Premium Hospitality Products
              </h2>
              <p
                class="animate-fade-in-delay mx-auto max-w-2xl text-base leading-relaxed text-[#666666] sm:text-lg md:text-xl"
              >
                Elevate your hotel experience with our curated collection of luxury beddings, elegant kitchenware, and sophisticated furniture.
              </p>
            </div>
          </section>

          <!-- Products Grid -->
          <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 sm:pb-16 lg:px-8 lg:pb-20">
            <TransitionGroup
              name="product"
              tag="div"
              class="grid grid-cols-1 gap-6 sm:grid-cols-2 sm:gap-8 lg:grid-cols-3"
            >
              <div
                v-for="product in filteredProducts"
                :key="product.id"
                :ref="(el) => (productRefs[product.id] = el)"
                class="group cursor-pointer"
                @mouseenter="hoveredProduct = product.id"
                @mouseleave="hoveredProduct = null"
              >
                <div
                  class="overflow-hidden rounded-lg bg-white shadow-sm transition-all duration-500 hover:shadow-xl"
                >
                  <!-- Product Image -->
                  <div class="relative aspect-square overflow-hidden bg-[#F5F5F0]">
                    <img
                      :src="product.image"
                      :alt="product.name"
                      class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                    />
                    <div
                      :class="[
                        'bg-opacity-0 group-hover:bg-opacity-10 absolute inset-0 bg-[#003366] transition-all duration-500',
                        hoveredProduct === product.id ? 'opacity-100' : 'opacity-0',
                      ]"
                    />
                    <!-- Quick Actions -->
                    <div
                      :class="[
                        'absolute inset-0 flex items-center justify-center gap-3 px-4 transition-all duration-300',
                        hoveredProduct === product.id ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0',
                      ]"
                    >
                      <button
                        class="flex items-center gap-2 rounded-full bg-[#2E8B57] px-4 py-2.5 text-sm font-medium text-white transition-colors hover:bg-[#267347] sm:px-6 sm:py-3 sm:text-base"
                      >
                        <Eye :size="16" class="sm:w-[18px] sm:h-[18px]" />
                        <span class="hidden sm:inline">Quick View</span>
                        <span class="sm:hidden">View</span>
                      </button>
                    </div>
                  </div>

                  <!-- Product Info -->
                  <div class="p-4 sm:p-6">
                    <div class="mb-2 flex items-start justify-between gap-2">
                      <h3
                        class="font-serif text-base text-[#333333] transition-colors group-hover:text-[#2E8B57] sm:text-lg"
                      >
                        {{ product.name }}
                      </h3>
                      <button
                        class="flex-shrink-0 text-[#666666] transition-colors hover:text-[#2E8B57]"
                      >
                        <Heart :size="18" class="sm:w-5 sm:h-5" />
                      </button>
                    </div>
                    <p class="mb-3 text-xs text-[#666666] sm:mb-4 sm:text-sm">
                      {{ product.description }}
                    </p>
                    <div class="mb-3 flex items-center justify-between sm:mb-4">
                      <span class="text-base font-medium text-[#333333] sm:text-lg">
                        {{ product.price }}
                      </span>
                      <span
                        class="bg-opacity-10 rounded-full bg-[#2E8B57] px-2.5 py-1 text-xs text-[#2E8B57] sm:px-3"
                      >
                        {{ product.category }}
                      </span>
                    </div>
                    <!-- Add to Cart Button -->
                    <button
                      @click="addToCart?.(product)"
                      class="flex w-full transform items-center justify-center gap-2 rounded-lg bg-[#2E8B57] py-2.5 text-sm font-medium text-white transition-all hover:scale-[1.02] hover:bg-[#267347] active:scale-[0.98] sm:py-3 sm:text-base"
                    >
                      <ShoppingCart :size="16" class="sm:w-[18px] sm:h-[18px]" />
                      Add to Cart
                    </button>
                  </div>
                </div>
              </div>
            </TransitionGroup>
          </div>

          <!-- ? -->
          <!-- <section class="mb-12 bg-[#003366] px-4 py-12 text-white sm:mb-16 sm:px-6 sm:py-16 lg:mb-20 lg:py-20">
            <div class="mx-auto max-w-4xl text-center">
              <h3 class="mb-4 font-serif text-2xl leading-tight sm:mb-6 sm:text-3xl md:text-4xl lg:text-5xl">
                Discover Our Signature Collection
              </h3>
              <p
                class="mx-auto mb-6 max-w-2xl text-base leading-relaxed text-gray-300 sm:mb-8 sm:text-lg"
              >
                Handpicked products that define luxury and comfort for the modern hotel experience.
              </p>
              <button
                class="inline-flex items-center gap-2 rounded-full bg-[#2E8B57] px-6 py-3 text-sm font-medium text-white transition-all duration-300 hover:scale-105 hover:bg-[#267347] sm:px-8 sm:py-4 sm:text-base"
              >
                Explore Collection
                <ArrowRight :size="18" class="sm:w-5 sm:h-5" />
              </button>
            </div>
          </section> -->
        </main>
      </div>
    </PublicLayout>

  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { ShoppingCart, Eye, Heart, Bed, UtensilsCrossed, Armchair } from 'lucide-vue-next'
import ProductSidebar from '@/components/core/ProductSidebar.vue'
import PublicLayout from '../layouts/PublicLayout.vue'
import { inject } from 'vue'

interface Product {
  id: number
  name: string
  description: string
  price: string
  category: string
  image: string
}

const products: Product[] = [
  {
    id: 1,
    name: 'Luxury Egyptian Cotton Sheets',
    description: '1000 thread count, hotel-grade bedding',
    price: '$299',
    category: 'beddings',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    id: 2,
    name: 'Premium Down Pillows',
    description: 'Hypoallergenic, cloud-like comfort',
    price: '$89',
    category: 'beddings',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    id: 3,
    name: 'Professional Cookware Set',
    description: 'Commercial-grade stainless steel',
    price: '$599',
    category: 'kitchenware',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    id: 4,
    name: 'Fine Dining Plate Collection',
    description: 'Elegant porcelain dinnerware',
    price: '$199',
    category: 'kitchenware',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    id: 5,
    name: 'Executive Desk Chair',
    description: 'Ergonomic leather office seating',
    price: '$799',
    category: 'furniture',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    id: 6,
    name: 'Modern Lounge Sofa',
    description: 'Contemporary design, premium fabric',
    price: '$1,899',
    category: 'furniture',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    id: 7,
    name: 'Silk Duvet Cover Set',
    description: 'Luxurious mulberry silk bedding',
    price: '$449',
    category: 'beddings',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    id: 8,
    name: 'Crystal Glassware Set',
    description: 'Hand-blown crystal stemware',
    price: '$349',
    category: 'kitchenware',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    id: 9,
    name: 'Boutique Nightstand',
    description: 'Solid wood with brass accents',
    price: '$599',
    category: 'furniture',
    image: '/placeholder.svg?height=400&width=400'
  }
]

const selectedCategory = ref<string>('all')
const hoveredProduct = ref<number | null>(null)
const sidebarOpen = ref(true)
const isMobile = ref(false)
const productRefs = ref<Record<number, any>>({})

const categoriesWithProducts = computed(() => [
  {
    id: 'beddings',
    name: 'Beddings',
    icon: Bed,
    products: products.filter(p => p.category === 'beddings')
  },
  {
    id: 'kitchenware',
    name: 'Kitchenware',
    icon: UtensilsCrossed,
    products: products.filter(p => p.category === 'kitchenware')
  },
  {
    id: 'furniture',
    name: 'Furniture',
    icon: Armchair,
    products: products.filter(p => p.category === 'furniture')
  }
])

const filteredProducts = computed(() => {
  if (selectedCategory.value === 'all') {
    return products
  }
  return products.filter(product => product.category === selectedCategory.value)
})

const selectCategory = (categoryId: string) => {
  selectedCategory.value = categoryId
  if (isMobile.value) {
    sidebarOpen.value = false
  }
}

const scrollToProduct = (product: Product) => {
  const element = productRefs.value[product.id]
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'center' })
  }
  if (isMobile.value) {
    sidebarOpen.value = false
  }
}

const addToCart = inject<(product: Product) => void>('addToCart')

const checkMobile = () => {
  isMobile.value = window.innerWidth < 1024
  if (!isMobile.value) {
    sidebarOpen.value = true
  } else {
    sidebarOpen.value = false
  }
}

onMounted(() => {
  checkMobile()
  window.addEventListener('resize', checkMobile)
})

onUnmounted(() => {
  window.removeEventListener('resize', checkMobile)
})
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600&display=swap');

* {
  font-family: 'Inter', sans-serif;
}

h1, h2, h3, h4 {
  font-family: 'Playfair Display', serif;
}

/* Fade-in animations */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-fade-in {
  animation: fadeIn 0.8s ease-out;
}

.animate-fade-in-delay {
  animation: fadeIn 0.8s ease-out 0.2s both;
}

/* Product transition animations */
.product-enter-active,
.product-leave-active {
  transition: all 0.5s ease;
}

.product-enter-from {
  opacity: 0;
  transform: scale(0.9) translateY(20px);
}

.product-leave-to {
  opacity: 0;
  transform: scale(0.9) translateY(-20px);
}

.product-move {
  transition: transform 0.5s ease;
}
</style>
