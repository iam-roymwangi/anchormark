<template>
  <div class="min-h-screen bg-[#F5F5F0]">
    <PublicLayout>

    
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
            :src="`https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8cmVzdGF1cmFudHxlbnwwfHwwfHx8MA%3D%3D`"
            :alt="`Gallery ${i}`"
            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
          />
        </div>
      </div>
    </section>

    </PublicLayout>

  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { Eye, Heart, ArrowRight } from 'lucide-vue-next'
import PublicLayout from '@/layouts/PublicLayout.vue'

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
    image: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8cmVzdGF1cmFudHxlbnwwfHwwfHx8MA%3D%3D'
  },
  {
    id: 2,
    name: 'Premium Down Pillows',
    description: 'Hypoallergenic, cloud-like comfort',
    price: 'From $89',
    category: 'beddings',
    image: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8cmVzdGF1cmFudHxlbnwwfHwwfHx8MA%3D%3D'
  },
  {
    id: 3,
    name: 'Professional Cookware Set',
    description: 'Commercial-grade stainless steel',
    price: 'From $599',
    category: 'kitchenware',
    image: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8cmVzdGF1cmFudHxlbnwwfHwwfHx8MA%3D%3D'
  },
  {
    id: 4,
    name: 'Fine Dining Plate Collection',
    description: 'Elegant porcelain dinnerware',
    price: 'From $199',
    category: 'kitchenware',
    image: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8cmVzdGF1cmFudHxlbnwwfHwwfHx8MA%3D%3D'
  },
  {
    id: 5,
    name: 'Executive Desk Chair',
    description: 'Ergonomic leather office seating',
    price: 'From $799',
    category: 'furniture',
    image: 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8cmVzdGF1cmFudHxlbnwwfHwwfHx8MA%3D%3D'
  },
  {
    id: 6,
    name: 'Modern Lounge Sofa',
    description: 'Contemporary design, premium fabric',
    price: 'From $1,899',
    category: 'furniture',
    image: 'https://images.ctfassets.net/h81st780aesh/3p269F8scsqoNoyIonFxTT/4796d33fc3eb4e7deacbec577fe48d06/restaurant-decor-ideas.jpeg'
  },
  {
    id: 7,
    name: 'Silk Duvet Cover Set',
    description: 'Luxurious mulberry silk bedding',
    price: 'From $449',
    category: 'beddings',
    image: 'https://images.ctfassets.net/h81st780aesh/3p269F8scsqoNoyIonFxTT/4796d33fc3eb4e7deacbec577fe48d06/restaurant-decor-ideas.jpeg'
  },
  {
    id: 8,
    name: 'Crystal Glassware Set',
    description: 'Hand-blown crystal stemware',
    price: 'From $349',
    category: 'kitchenware',
    image: 'https://images.ctfassets.net/h81st780aesh/3p269F8scsqoNoyIonFxTT/4796d33fc3eb4e7deacbec577fe48d06/restaurant-decor-ideas.jpeg'
  },
  {
    id: 9,
    name: 'Boutique Nightstand',
    description: 'Solid wood with brass accents',
    price: 'From $599',
    category: 'furniture',
    image: 'https://images.ctfassets.net/h81st780aesh/3p269F8scsqoNoyIonFxTT/4796d33fc3eb4e7deacbec577fe48d06/restaurant-decor-ideas.jpeg'
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