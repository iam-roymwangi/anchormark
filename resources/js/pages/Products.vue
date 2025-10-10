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
           <!-- <section class="px-4 py-12 sm:px-6 sm:py-16 md:py-20 lg:py-24">
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
          </section>  -->

          <!-- Search Bar -->
          <div class="mx-auto max-w-7xl px-4 pb-6 sm:px-6 lg:px-8 mt-16">
            <div class="relative max-w-md mx-auto">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Search products..."
                class="w-full px-4 py-3 pl-10 pr-4 text-sm border text-black border-gray-300 rounded-lg focus:ring-2 focus:ring-[#2E8B57] focus:border-transparent"
              />
              <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Products Grid -->
          <div class="mx-auto max-w-7xl px-4 pb-12 sm:px-6 sm:pb-16 lg:px-8 lg:pb-20">
            <!-- Loading State -->
            <div v-if="isLoading" class="flex justify-center items-center py-12">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-[#2E8B57]"></div>
            </div>

            <!-- No Products Message -->
            <div v-else-if="filteredProducts.length === 0" class="text-center py-12">
              <div class="text-gray-500 text-lg mb-4">No products found</div>
              <p class="text-gray-400">Try adjusting your search or filter criteria</p>
            </div>

            <!-- Products Grid -->
            <TransitionGroup
              v-else
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
                      :src="product.images.length > 0 ? product.images[0].image_url : '/placeholder.svg?height=400&width=400'"
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
                        ${{ product.price.toFixed(2) }}
                      </span>
                      <span
                        class="bg-opacity-10 rounded-full bg-[#2E8B57] px-2.5 py-1 text-xs text-[#2E8B57] sm:px-3"
                      >
                        {{ product.category.name }}
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

            <!-- Pagination -->
            <div v-if="!isLoading && pagination.last_page > 1" class="mt-12 flex items-center justify-center">
              <nav class="flex items-center space-x-2">
                <!-- Previous Button -->
                <button
                  @click="goToPage(pagination.current_page - 1)"
                  :disabled="pagination.current_page === 1"
                  class="flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <ChevronLeft :size="16" class="mr-1" />
                  Previous
                </button>

                <!-- Page Numbers -->
                <template v-for="link in paginationLinks" :key="link.label">
                  <button
                    v-if="link.url"
                    @click="goToPage(parseInt(link.label))"
                    :class="[
                      'px-3 py-2 text-sm font-medium border rounded-md',
                      link.active
                        ? 'text-white bg-[#2E8B57] border-[#2E8B57]'
                        : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'
                    ]"
                  >
                    {{ link.label }}
                  </button>
                  <span
                    v-else
                    class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-md"
                  >
                    {{ link.label }}
                  </span>
                </template>

                <!-- Next Button -->
                <button
                  @click="goToPage(pagination.current_page + 1)"
                  :disabled="pagination.current_page === pagination.last_page"
                  class="flex items-center px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Next
                  <ChevronRight :size="16" class="ml-1" />
                </button>
              </nav>
            </div>

            <!-- Results Info -->
            <div v-if="!isLoading" class="mt-6 text-center text-sm text-gray-600">
              Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} results
            </div>
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
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { ShoppingCart, Eye, Heart, Bed, UtensilsCrossed, Armchair, ChevronLeft, ChevronRight } from 'lucide-vue-next'
import ProductSidebar from '@/components/core/ProductSidebar.vue'
import PublicLayout from '../layouts/PublicLayout.vue'
import { inject } from 'vue'
import { router } from '@inertiajs/vue3'

interface ProductImage {
  id: number
  product_id: number
  image_url: string
}

interface Category {
  id: number
  name: string
  slug: string
}

interface Product {
  id: number
  name: string
  description: string
  price: number
  category: Category
  images: ProductImage[]
  slug: string
  status: string
  created_at: string
  updated_at: string
}

interface PaginationData {
  current_page: number
  last_page: number
  per_page: number
  total: number
  from: number
  to: number
}

interface ProductsResponse {
  data: Product[]
  links: Array<{
    url: string | null
    label: string
    active: boolean
  }>
  meta: PaginationData
}

interface Props {
  products?: ProductsResponse
  categories?: Category[]
  filters?: {
    category: string
    search: string
    per_page: number
  }
}

const props = defineProps<Props>()

// Reactive state
const selectedCategory = ref<string>(props.filters?.category || 'all')
const hoveredProduct = ref<number | null>(null)
const sidebarOpen = ref(true)
const isMobile = ref(false)
const productRefs = ref<Record<number, any>>({})
const searchQuery = ref(props.filters?.search || '')
const currentPage = ref(props.products?.meta?.current_page || 1)
const isLoading = ref(false)

// Computed properties
const categoriesWithProducts = computed(() => {
  const categoryMap: Record<string, any> = {}
  
  if (props.categories) {
    props.categories.forEach(category => {
      categoryMap[category.slug] = {
        id: category.slug,
        name: category.name,
        icon: getCategoryIcon(category.slug),
        products: (props.products?.data || []).filter(p => p.category.slug === category.slug)
      }
    })
  }
  
  return Object.values(categoryMap)
})

const filteredProducts = computed(() => {
  return props.products?.data || []
})

const pagination = computed(() => props.products?.meta || {
  current_page: 1,
  last_page: 1,
  per_page: 9,
  total: 0,
  from: 0,
  to: 0
})

const paginationLinks = computed(() => props.products?.links || [])

// Helper function to get category icon
const getCategoryIcon = (slug: string) => {
  const iconMap: Record<string, any> = {
    'beddings': Bed,
    'kitchenware': UtensilsCrossed,
    'furniture': Armchair
  }
  return iconMap[slug] || Bed
}

const selectCategory = (categoryId: string) => {
  selectedCategory.value = categoryId
  currentPage.value = 1
  if (isMobile.value) {
    sidebarOpen.value = false
  }
  fetchProducts()
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

const fetchProducts = () => {
  isLoading.value = true
  router.get('/products', {
    category: selectedCategory.value,
    search: searchQuery.value,
    page: currentPage.value,
    per_page: props.filters?.per_page || 9
  }, {
    preserveState: true,
    onFinish: () => {
      isLoading.value = false
    }
  })
}

const goToPage = (page: number) => {
  if (page >= 1 && page <= pagination.value.last_page) {
    currentPage.value = page
    fetchProducts()
  }
}

const searchProducts = () => {
  currentPage.value = 1
  fetchProducts()
}

// Watch for search query changes with debounce
let searchTimeout: number
watch(searchQuery, () => {
  clearTimeout(searchTimeout)
  searchTimeout = setTimeout(() => {
    searchProducts()
  }, 500)
})

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
