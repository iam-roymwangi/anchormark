<template>
  <div class="min-h-screen bg-white">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#003366] text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-[#2E8B57] rounded flex items-center justify-center">
              <Anchor class="w-5 h-5 text-white" />
            </div>
            <span class="text-xl font-bold">AnchorMark</span>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center gap-8">
            <a href="#products" class="hover:text-[#2E8B57] transition-colors">Products</a>
            <a href="#about" class="hover:text-[#2E8B57] transition-colors">About</a>
            <a href="#contact" class="hover:text-[#2E8B57] transition-colors">Contact</a>
            <button class="bg-[#2E8B57] hover:bg-[#247047] px-6 py-2 rounded-lg transition-colors">
              Get a Quote
            </button>
          </div>

          <!-- Mobile Menu Button -->
          <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden">
            <Menu v-if="!mobileMenuOpen" class="w-6 h-6" />
            <X v-else class="w-6 h-6" />
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div v-if="mobileMenuOpen" class="md:hidden bg-[#003366] border-t border-white/10">
        <div class="px-4 py-4 space-y-3">
          <a href="#products" class="block hover:text-[#2E8B57] transition-colors">Products</a>
          <a href="#about" class="block hover:text-[#2E8B57] transition-colors">About</a>
          <a href="#contact" class="block hover:text-[#2E8B57] transition-colors">Contact</a>
          <button class="w-full bg-[#2E8B57] hover:bg-[#247047] px-6 py-2 rounded-lg transition-colors">
            Get a Quote
          </button>
        </div>
      </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8 bg-[#E0E0E0]">
      <div class="max-w-7xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div>
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-serif leading-tight text-[#333333] mb-6">
              Premium hospitality solutions for exceptional hotels
            </h1>
            <p class="text-lg sm:text-xl text-[#333333]/80 mb-8 leading-relaxed">
              Transform your guest experience with AnchorMark's curated collection of premium beddings, kitchenware, and furniture designed for the modern hospitality industry.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
              <button class="bg-[#2E8B57] hover:bg-[#247047] text-white px-8 py-4 rounded-lg text-lg font-medium transition-colors flex items-center justify-center gap-2">
                Shop Now
                <ArrowRight class="w-5 h-5" />
              </button>
              <button class="border-2 border-[#003366] text-[#003366] hover:bg-[#003366] hover:text-white px-8 py-4 rounded-lg text-lg font-medium transition-colors">
                Learn Our Story
              </button>
            </div>
          </div>
          <div class="relative">
            <img 
              src="/placeholder.svg?height=600&width=600" 
              alt="Premium hotel room"
              class="rounded-2xl shadow-2xl w-full"
            />
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
          <div v-for="stat in stats" :key="stat.label" class="text-center">
            <div class="text-4xl sm:text-5xl font-bold text-[#2E8B57] mb-2">{{ stat.value }}</div>
            <div class="text-[#333333]/70 text-sm sm:text-base">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Product Categories -->
    <section id="products" class="py-20 bg-[#E0E0E0]">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-4xl sm:text-5xl font-serif text-[#333333] mb-4">Our Product Range</h2>
          <p class="text-lg text-[#333333]/70 max-w-2xl mx-auto">
            Carefully curated collections designed to elevate every aspect of your hospitality business
          </p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
          <div 
            v-for="category in categories" 
            :key="category.title"
            class="bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-shadow cursor-pointer group"
          >
            <div class="relative h-64 overflow-hidden">
              <img 
                :src="category.image" 
                :alt="category.title"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
              <component :is="category.icon" class="absolute top-4 right-4 w-8 h-8 text-white" />
            </div>
            <div class="p-6">
              <h3 class="text-2xl font-bold text-[#333333] mb-2">{{ category.title }}</h3>
              <p class="text-[#333333]/70 mb-4">{{ category.description }}</p>
              <button class="text-[#2E8B57] font-medium flex items-center gap-2 hover:gap-3 transition-all">
                Explore Collection
                <ArrowRight class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-20 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
          <div class="order-2 lg:order-1">
            <img 
              src="/placeholder.svg?height=500&width=600" 
              alt="Professional kitchenware"
              class="rounded-2xl shadow-xl w-full"
            />
          </div>
          <div class="order-1 lg:order-2">
            <h2 class="text-4xl sm:text-5xl font-serif text-[#333333] mb-6">
              Why leading hotels choose AnchorMark
            </h2>
            <div class="space-y-6">
              <div v-for="feature in features" :key="feature.title" class="flex gap-4">
                <div class="flex-shrink-0 w-12 h-12 bg-[#2E8B57] rounded-lg flex items-center justify-center">
                  <component :is="feature.icon" class="w-6 h-6 text-white" />
                </div>
                <div>
                  <h3 class="text-xl font-bold text-[#333333] mb-2">{{ feature.title }}</h3>
                  <p class="text-[#333333]/70">{{ feature.description }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-[#003366] text-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl sm:text-5xl font-serif mb-6">
          Ready to elevate your hospitality experience?
        </h2>
        <p class="text-lg sm:text-xl mb-8 text-white/90 leading-relaxed">
          Join hundreds of hotels worldwide who trust AnchorMark for their premium hospitality needs. Get a personalized quote today.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button class="bg-[#2E8B57] hover:bg-[#247047] text-white px-8 py-4 rounded-lg text-lg font-medium transition-colors">
            Request a Quote
          </button>
          <button class="border-2 border-white text-white hover:bg-white hover:text-[#003366] px-8 py-4 rounded-lg text-lg font-medium transition-colors">
            View Catalog
          </button>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-[#003366] text-white py-12 border-t border-white/10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-4 gap-8 mb-8">
          <div>
            <div class="flex items-center gap-2 mb-4">
              <div class="w-8 h-8 bg-[#2E8B57] rounded flex items-center justify-center">
                <Anchor class="w-5 h-5 text-white" />
              </div>
              <span class="text-xl font-bold">AnchorMark</span>
            </div>
            <p class="text-white/70 text-sm">
              Premium hospitality solutions for exceptional hotels worldwide.
            </p>
          </div>
          <div>
            <h4 class="font-bold mb-4">Products</h4>
            <ul class="space-y-2 text-sm text-white/70">
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Beddings</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Kitchenware</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Furniture</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Accessories</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-bold mb-4">Company</h4>
            <ul class="space-y-2 text-sm text-white/70">
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">About Us</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Sustainability</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Careers</a></li>
              <li><a href="#" class="hover:text-[#2E8B57] transition-colors">Contact</a></li>
            </ul>
          </div>
          <div>
            <h4 class="font-bold mb-4">Connect</h4>
            <div class="flex gap-3">
              <a href="#" class="w-10 h-10 bg-white/10 hover:bg-[#2E8B57] rounded-lg flex items-center justify-center transition-colors">
                <Mail class="w-5 h-5" />
              </a>
              <a href="#" class="w-10 h-10 bg-white/10 hover:bg-[#2E8B57] rounded-lg flex items-center justify-center transition-colors">
                <Phone class="w-5 h-5" />
              </a>
            </div>
          </div>
        </div>
        <div class="border-t border-white/10 pt-8 text-center text-sm text-white/70">
          <p>&copy; 2025 AnchorMark. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { 
  Menu, 
  X, 
  ArrowRight, 
  Anchor,
  Bed,
  UtensilsCrossed,
  Armchair,
  Award,
  Truck,
  Shield,
  Sparkles,
  Mail,
  Phone
} from 'lucide-vue-next'

const mobileMenuOpen = ref(false)

const stats = [
  { value: '500+', label: 'Hotels Served' },
  { value: '15+', label: 'Years Experience' },
  { value: '50K+', label: 'Products Delivered' },
  { value: '98%', label: 'Client Satisfaction' }
]

const categories = [
  {
    title: 'Premium Beddings',
    description: 'Luxurious linens, duvets, and pillows crafted for ultimate guest comfort',
    image: '/placeholder.svg?height=400&width=400',
    icon: Bed
  },
  {
    title: 'Professional Kitchenware',
    description: 'Commercial-grade cookware and dining essentials for hotel kitchens',
    image: '/placeholder.svg?height=400&width=400',
    icon: UtensilsCrossed
  },
  {
    title: 'Designer Furniture',
    description: 'Elegant and durable furniture pieces that define sophisticated spaces',
    image: '/placeholder.svg?height=400&width=400',
    icon: Armchair
  }
]

const features = [
  {
    title: 'Premium Quality',
    description: 'Every product is carefully selected and tested to meet the highest hospitality standards',
    icon: Award
  },
  {
    title: 'Fast Delivery',
    description: 'Reliable logistics network ensuring your orders arrive on time, every time',
    icon: Truck
  },
  {
    title: 'Warranty Protection',
    description: 'Comprehensive warranty coverage and dedicated support for all products',
    icon: Shield
  },
  {
    title: 'Eco-Friendly',
    description: 'Sustainable sourcing and environmentally conscious manufacturing practices',
    icon: Sparkles
  }
]
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600;700&display=swap');

.font-serif {
  font-family: 'Playfair Display', serif;
}

* {
  font-family: 'Inter', sans-serif;
}
</style>