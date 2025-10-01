<template>
  <div class="min-h-screen bg-[#F5F5F0]">
    <!-- Navigation -->
    <nav class="bg-[#003366] text-white sticky top-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center space-x-8">
            <h1 class="text-2xl font-serif font-bold">AnchorMark</h1>
            <div class="hidden md:flex space-x-6 text-sm">
              <a href="#" class="hover:text-[#2E8B57] transition-colors">Home</a>
              <a href="#" class="text-[#2E8B57]">Products</a>
              <a href="#" class="hover:text-[#2E8B57] transition-colors">About</a>
            </div>
          </div>
          <div class="flex items-center space-x-4">
            <button class="hover:text-[#2E8B57] transition-colors">
              <Search :size="20" />
            </button>
            <button class="hover:text-[#2E8B57] transition-colors">
              <ShoppingCart :size="20" />
            </button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="py-16 md:py-24 px-4">
      <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-5xl md:text-7xl font-serif text-[#333333] mb-6 animate-fade-in">
          Premium Hospitality Products
        </h2>
        <p class="text-lg md:text-xl text-[#666666] max-w-2xl mx-auto animate-fade-in-delay">
          Elevate your hotel experience with our curated collection of luxury beddings, 
          elegant kitchenware, and sophisticated furniture.
        </p>
      </div>
    </section>

    <!-- Category Filter -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
      <div class="flex flex-wrap justify-center gap-4">
        <button
          v-for="category in categories"
          :key="category.id"
          @click="selectedCategory = category.id"
          :class="[
            'px-6 py-3 rounded-full text-sm font-medium transition-all duration-300',
            selectedCategory === category.id
              ? 'bg-[#2E8B57] text-white shadow-lg scale-105'
              : 'bg-white text-[#333333] hover:bg-[#E0E0E0]'
          ]"
        >
          {{ category.name }}
        </button>
      </div>
    </div>

    <!-- Products Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
      <TransitionGroup
        name="product"
        tag="div"
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8"
      >
        <div
          v-for="product in filteredProducts"
          :key="product.id"
          class="group cursor-pointer"
          @mouseenter="hoveredProduct = product.id"
          @mouseleave="hoveredProduct = null"
        >
          <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-xl transition-all duration-500">
            <!-- Product Image -->
            <div class="relative aspect-square bg-[#F5F5F0] overflow-hidden">
              <img
                :src="product.image"
                :alt="product.name"
                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
              />
              <div
                :class="[
                  'absolute inset-0 bg-[#003366] bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-500',
                  hoveredProduct === product.id ? 'opacity-100' : 'opacity-0'
                ]"
              />
              <!-- Quick View Button -->
              <div
                :class="[
                  'absolute inset-0 flex items-center justify-center transition-all duration-300',
                  hoveredProduct === product.id ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'
                ]"
              >
                <button class="bg-[#2E8B57] text-white px-6 py-3 rounded-full font-medium hover:bg-[#267347] transition-colors flex items-center gap-2">
                  <Eye :size="18" />
                  Quick View
                </button>
              </div>
            </div>

            <!-- Product Info -->
            <div class="p-6">
              <div class="flex items-start justify-between mb-2">
                <h3 class="text-lg font-serif text-[#333333] group-hover:text-[#2E8B57] transition-colors">
                  {{ product.name }}
                </h3>
                <button class="text-[#666666] hover:text-[#2E8B57] transition-colors">
                  <Heart :size="20" />
                </button>
              </div>
              <p class="text-sm text-[#666666] mb-4">{{ product.description }}</p>
              <div class="flex items-center justify-between">
                <span class="text-[#333333] font-medium">{{ product.price }}</span>
                <span class="text-xs text-[#2E8B57] bg-[#2E8B57] bg-opacity-10 px-3 py-1 rounded-full">
                  {{ product.category }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </TransitionGroup>
    </div>

    <!-- Featured Collection Banner -->
    <section class="bg-[#003366] text-white py-20 px-4 mb-20">
      <div class="max-w-4xl mx-auto text-center">
        <h3 class="text-4xl md:text-5xl font-serif mb-6">
          Discover Our Signature Collection
        </h3>
        <p class="text-lg text-gray-300 mb-8 max-w-2xl mx-auto">
          Handpicked products that define luxury and comfort for the modern hotel experience.
        </p>
        <button class="bg-[#2E8B57] text-white px-8 py-4 rounded-full font-medium hover:bg-[#267347] transition-all duration-300 hover:scale-105 inline-flex items-center gap-2">
          Explore Collection
          <ArrowRight :size="20" />
        </button>
      </div>
    </section>

    <!-- Instagram-style Product Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
      <h3 class="text-3xl font-serif text-center text-[#333333] mb-4">
        Inspiration Gallery
      </h3>
      <p class="text-center text-[#666666] mb-12">
        See our products in real hotel environments
      </p>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div
          v-for="i in 8"
          :key="i"
          class="aspect-square bg-[#E0E0E0] rounded-lg overflow-hidden group cursor-pointer"
        >
          <img
            :src="`/placeholder.svg?height=400&width=400&query=hotel room ${i}`"
            :alt="`Gallery ${i}`"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
          />
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#003366] text-white py-12 px-4">
      <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8">
        <div>
          <h4 class="text-2xl font-serif mb-4">AnchorMark</h4>
          <p class="text-gray-300 text-sm">
            Premium hospitality products for exceptional hotel experiences.
          </p>
        </div>
        <div>
          <h5 class="font-medium mb-4">Products</h5>
          <ul class="space-y-2 text-sm text-gray-300">
            <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Beddings</a></li>
            <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Kitchenware</a></li>
            <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Furniture</a></li>
          </ul>
        </div>
        <div>
          <h5 class="font-medium mb-4">Company</h5>
          <ul class="space-y-2 text-sm text-gray-300">
            <li><a href="#" class="hover:text-[#2E8B57] transition-colors">About Us</a></li>
            <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Contact</a></li>
            <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Careers</a></li>
          </ul>
        </div>
        <div>
          <h5 class="font-medium mb-4">Follow Us</h5>
          <div class="flex space-x-4">
            <a href="#" class="hover:text-[#2E8B57] transition-colors">
              <Instagram :size="20" />
            </a>
            <a href="#" class="hover:text-[#2E8B57] transition-colors">
              <Facebook :size="20" />
            </a>
            <a href="#" class="hover:text-[#2E8B57] transition-colors">
              <Linkedin :size="20" />
            </a>
          </div>
        </div>
      </div>
      <div class="max-w-7xl mx-auto mt-8 pt-8 border-t border-gray-700 text-center text-sm text-gray-400">
        © 2025 AnchorMark. All rights reserved.
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Search, ShoppingCart, Eye, Heart, ArrowRight, Instagram, Facebook, Linkedin } from 'lucide-vue-next'

interface Category {
  id: string
  name: string
}

interface Product {
  id: number
  name: string
  description: string
  price: string
  category: string
  image: string
}

const categories: Category[] = [
  { id: 'all', name: 'All Products' },
  { id: 'beddings', name: 'Beddings' },
  { id: 'kitchenware', name: 'Kitchenware' },
  { id: 'furniture', name: 'Furniture' }
]

const products: Product[] = [
  {
    id: 1,
    name: 'Luxury Egyptian Cotton Sheets',
    description: '1000 thread count, hotel-grade bedding',
    price: 'From $299',
    category: 'beddings',
    image: '/placeholder.svg?height=600&width=600'
  },
  {
    id: 2,
    name: 'Premium Down Pillows',
    description: 'Hypoallergenic, cloud-like comfort',
    price: 'From $89',
    category: 'beddings',
    image: '/placeholder.svg?height=600&width=600'
  },
  {
    id: 3,
    name: 'Professional Cookware Set',
    description: 'Commercial-grade stainless steel',
    price: 'From $599',
    category: 'kitchenware',
    image: '/placeholder.svg?height=600&width=600'
  },
  {
    id: 4,
    name: 'Fine Dining Plate Collection',
    description: 'Elegant porcelain dinnerware',
    price: 'From $199',
    category: 'kitchenware',
    image: '/placeholder.svg?height=600&width=600'
  },
  {
    id: 5,
    name: 'Executive Desk Chair',
    description: 'Ergonomic leather office seating',
    price: 'From $799',
    category: 'furniture',
    image: '/placeholder.svg?height=600&width=600'
  },
  {
    id: 6,
    name: 'Modern Lounge Sofa',
    description: 'Contemporary design, premium fabric',
    price: 'From $1,899',
    category: 'furniture',
    image: '/placeholder.svg?height=600&width=600'
  },
  {
    id: 7,
    name: 'Silk Duvet Cover Set',
    description: 'Luxurious mulberry silk bedding',
    price: 'From $449',
    category: 'beddings',
    image: '/placeholder.svg?height=600&width=600'
  },
  {
    id: 8,
    name: 'Crystal Glassware Set',
    description: 'Hand-blown crystal stemware',
    price: 'From $349',
    category: 'kitchenware',
    image: '/placeholder.svg?height=600&width=600'
  },
  {
    id: 9,
    name: 'Boutique Nightstand',
    description: 'Solid wood with brass accents',
    price: 'From $599',
    category: 'furniture',
    image: '/placeholder.svg?height=600&width=600'
  }
]

const selectedCategory = ref<string>('all')
const hoveredProduct = ref<number | null>(null)

const filteredProducts = computed(() => {
  if (selectedCategory.value === 'all') {
    return products
  }
  return products.filter(product => product.category === selectedCategory.value)
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