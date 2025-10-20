<template>
  <div class="min-h-screen bg-white">
     <PublicLayout>

     <!-- Hero Section with Image Overlay  -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden">
      <div class="absolute inset-0 bg-gradient-to-b from-[#003366]/80 to-[#003366]/60 z-10"></div>
      <img 
        src="/assets/images/Hotel-design.webp" 
        alt="Luxury Hotel" 
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div class="relative z-20 text-center text-white px-6 max-w-4xl mx-auto" :class="{ 'animate-fade-in': isVisible }">
        <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
          Elevating hospitality through quality and innovation
        </h1>
        <p class="text-xl md:text-2xl text-gray-200 max-w-3xl mx-auto leading-relaxed">
          AnchorMark is a trusted partner in the hospitality industry, providing premium products that transform guest experiences.
        </p>
      </div>
      <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-20 animate-bounce">
        <ChevronDown :size="32" class="text-white" />
      </div>
    </section>

     <!-- Mission Statement  -->
    <section class="py-24 px-6 bg-white">
      <div class="max-w-4xl mx-auto text-center">
        <div class="inline-block px-4 py-2 bg-[#E0E0E0] rounded-full text-sm font-semibold text-[#003366] mb-6">
          Our Mission
        </div>
        <h2 class="text-4xl md:text-5xl font-bold text-[#003366] mb-8 leading-tight">
          Crafting exceptional experiences, one product at a time
        </h2>
        <p class="text-xl text-[#333333] leading-relaxed">
          We believe that every detail matters in creating memorable guest experiences. From luxurious beddings to elegant kitchenware and timeless furniture, we supply hotels with products that embody quality, comfort, and sophistication.
        </p>
      </div>
    </section>

     <!-- Core Values - Numbered Sections  -->
    <section class="py-24 px-6 bg-[#F5F5F0]">
      <div class="max-w-6xl mx-auto">
        <h2 class="text-4xl md:text-5xl font-bold text-[#003366] mb-16 text-center">Our Core Values</h2>
        <div class="grid md:grid-cols-2 gap-16">
          <div 
            v-for="(value, index) in values" 
            :key="index"
            class="group"
            :class="{ 'animate-slide-up': isVisible }"
            :style="{ animationDelay: `${index * 100}ms` }"
          >
            <div class="text-8xl font-bold text-[#C09930] opacity-20 mb-4">{{ index + 1 }}</div>
            <h3 class="text-2xl font-bold text-[#003366] mb-4">{{ value.title }}</h3>
            <p class="text-lg text-[#333333] leading-relaxed">{{ value.description }}</p>
          </div>
        </div>
      </div>
    </section>

     <!-- Image Section with Text Overlay  -->
    <section class="relative h-[600px] flex items-center justify-center overflow-hidden">
      <div class="absolute inset-0 bg-[#003366]/70 z-10"></div>
      <img 
        src="/placeholder.svg?height=600&width=1920" 
        alt="Craftsmanship" 
        class="absolute inset-0 w-full h-full object-cover"
      />
      <div class="relative z-20 text-center text-white px-6 max-w-4xl mx-auto">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">Combining innovation, durability, and elegance</h2>
        <p class="text-xl text-gray-200 leading-relaxed">
          AnchorMark serves as a key player in the hospitality supply chain. We provide sustainable solutions that meet the evolving needs of modern hotels.
        </p>
      </div>
    </section>

     <!-- Stats Section  -->
    <section class="py-24 px-6 bg-[#003366] text-white">
      <div class="max-w-7xl mx-auto">
        <div class="grid md:grid-cols-4 gap-12">
          <div 
            v-for="(stat, index) in stats" 
            :key="index"
            class="text-center group hover:scale-105 transition-transform duration-300"
          >
            <div class="text-5xl md:text-6xl font-bold text-[#C09930] mb-4">{{ stat.value }}</div>
            <div class="text-xl text-gray-300">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </section>

     <!-- Approach Section - Three Cards  -->
    <section class="py-24 px-6 bg-white">
      <div class="max-w-7xl mx-auto">
        <h2 class="text-4xl md:text-5xl font-bold text-[#003366] mb-6 text-center">
          A comprehensive approach for lasting partnerships
        </h2>
        <p class="text-xl text-[#333333] text-center mb-16 max-w-3xl mx-auto">
          We work closely with hotels to understand their unique needs and deliver tailored solutions.
        </p>
        <div class="grid md:grid-cols-3 gap-8">
          <div 
            v-for="(approach, index) in approaches" 
            :key="index"
            class="group relative overflow-hidden rounded-lg shadow-lg hover:shadow-2xl transition-all duration-500"
          >
            <div class="relative h-80 overflow-hidden">
              <img 
                :src="approach.image" 
                :alt="approach.title"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-[#003366] to-transparent opacity-80"></div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
              <h3 class="text-2xl font-bold mb-2">{{ approach.title }}</h3>
              <p class="text-gray-200">{{ approach.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

     <!-- Timeline Section  -->
    <section class="py-24 px-6 bg-[#F5F5F0]">
      <div class="max-w-4xl mx-auto">
        <h2 class="text-4xl md:text-5xl font-bold text-[#003366] mb-16 text-center">Our Journey</h2>
        <div class="space-y-12">
          <div 
            v-for="(milestone, index) in timeline" 
            :key="index"
            class="flex gap-8 group"
            :class="{ 'animate-slide-in': isVisible }"
            :style="{ animationDelay: `${index * 150}ms` }"
          >
            <div class="flex-shrink-0 w-32 text-right">
              <div class="text-2xl font-bold text-[#C09930]">{{ milestone.year }}</div>
            </div>
            <div class="flex-shrink-0 w-px bg-[#C09930] relative">
              <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-4 h-4 bg-[#C09930] rounded-full group-hover:scale-150 transition-transform"></div>
            </div>
            <div class="flex-1 pb-12">
              <h3 class="text-xl font-bold text-[#003366] mb-2">{{ milestone.title }}</h3>
              <p class="text-[#333333] leading-relaxed">{{ milestone.description }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

     <!-- Team Section  -->
    <section class="py-24 px-6 bg-white">
      <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
          <h2 class="text-4xl md:text-5xl font-bold text-[#003366] mb-6">Meet Our Leadership</h2>
          <p class="text-xl text-[#333333] max-w-3xl mx-auto">
            Our experienced team brings decades of hospitality industry expertise to every partnership.
          </p>
        </div>
        <div class="grid md:grid-cols-3 gap-12">
          <div 
            v-for="(member, index) in team" 
            :key="index"
            class="group text-center"
          >
            <div class="relative mb-6 overflow-hidden rounded-lg">
              <img 
                :src="member.image" 
                :alt="member.name"
                class="w-full h-80 object-cover group-hover:scale-105 transition-transform duration-500"
              />
              <div class="absolute inset-0 bg-[#003366]/0 group-hover:bg-[#003366]/20 transition-all duration-300"></div>
            </div>
            <h3 class="text-2xl font-bold text-[#003366] mb-2">{{ member.name }}</h3>
            <p class="text-[#C09930] font-semibold mb-3">{{ member.role }}</p>
            <p class="text-[#333333] leading-relaxed">{{ member.bio }}</p>
          </div>
        </div>
      </div>
    </section>

     <!-- CTA Section  -->
    <section class="py-24 px-6 bg-[#003366] text-white text-center">
      <div class="max-w-4xl mx-auto">
        <h2 class="text-4xl md:text-5xl font-bold mb-6">Ready to elevate your hotel experience?</h2>
        <p class="text-xl text-gray-200 mb-8 leading-relaxed">
          Partner with AnchorMark and discover how our premium products can transform your guest satisfaction.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <button class="bg-[#C09930] text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-[#267347] transition-all hover:scale-105">
            Request a Catalog
          </button>
          <button class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-white hover:text-[#003366] transition-all">
            Schedule a Consultation
          </button>
        </div>
      </div>
    </section>

    </PublicLayout>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { ChevronDown } from 'lucide-vue-next'
import PublicLayout from '@/layouts/PublicLayout.vue'

const isVisible = ref(false)

interface Value {
  title: string
  description: string
}

interface Stat {
  value: string
  label: string
}

interface Approach {
  title: string
  description: string
  image: string
}

interface Timeline {
  year: string
  title: string
  description: string
}

interface TeamMember {
  name: string
  role: string
  bio: string
  image: string
}

const values: Value[] = [
  {
    title: 'Quality First',
    description: 'We partner with hotels to ensure every product meets the highest standards of craftsmanship and durability, creating lasting impressions for guests.'
  },
  {
    title: 'Innovation & Sustainability',
    description: 'We embrace innovation through sustainable practices, offering eco-friendly solutions that align with modern hospitality values.'
  },
  {
    title: 'Customer Partnership',
    description: 'We build long-term relationships with our clients, understanding their unique needs and delivering tailored solutions that exceed expectations.'
  },
  {
    title: 'Excellence in Service',
    description: 'From initial consultation to delivery, we provide exceptional service at every touchpoint, ensuring seamless integration of our products.'
  }
]

const stats: Stat[] = [
  { value: '500+', label: 'Hotels Served' },
  { value: '15+', label: 'Years Experience' },
  { value: '50K+', label: 'Products Delivered' },
  { value: '98%', label: 'Client Satisfaction' }
]

const approaches: Approach[] = [
  {
    title: 'Partnership',
    description: 'Building lasting relationships through trust and collaboration',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    title: 'Customization',
    description: 'Tailored solutions that meet your unique hospitality needs',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    title: 'Reliability',
    description: 'Consistent quality and timely delivery you can depend on',
    image: '/placeholder.svg?height=400&width=400'
  }
]

const timeline: Timeline[] = [
  {
    year: '2009',
    title: 'Foundation',
    description: 'AnchorMark was founded with a vision to revolutionize hospitality product supply, starting with premium beddings for boutique hotels.'
  },
  {
    year: '2013',
    title: 'Expansion',
    description: 'Expanded our product line to include kitchenware and dining essentials, serving over 100 hotels across the region.'
  },
  {
    year: '2017',
    title: 'Furniture Division',
    description: 'Launched our furniture division, offering custom-designed pieces that blend functionality with elegant aesthetics.'
  },
  {
    year: '2020',
    title: 'Sustainability Initiative',
    description: 'Introduced our eco-friendly product line, partnering with sustainable manufacturers to reduce environmental impact.'
  },
  {
    year: '2024',
    title: 'Global Reach',
    description: 'Now serving 500+ hotels worldwide, maintaining our commitment to quality and innovation in every product we deliver.'
  }
]

const team: TeamMember[] = [
  {
    name: 'Sarah Mitchell',
    role: 'Chief Executive Officer',
    bio: 'With 20 years in hospitality management, Sarah leads AnchorMark with a vision for excellence.',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    name: 'David Chen',
    role: 'Head of Product Development',
    bio: 'David brings innovative design thinking to every product, ensuring quality and functionality.',
    image: '/placeholder.svg?height=400&width=400'
  },
  {
    name: 'Maria Rodriguez',
    role: 'Director of Client Relations',
    bio: 'Maria builds lasting partnerships with hotels, understanding their unique needs and challenges.',
    image: '/placeholder.svg?height=400&width=400'
  }
]

onMounted(() => {
  isVisible.value = true
})
</script>

<style scoped>
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

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateX(-40px);
  }
  to {
    opacity: 1;
    transform: translateX(0);
  }
}

.animate-fade-in {
  animation: fadeIn 1s ease-out forwards;
}

.animate-slide-up {
  animation: slideUp 0.8s ease-out forwards;
  opacity: 0;
}

.animate-slide-in {
  animation: slideIn 0.8s ease-out forwards;
  opacity: 0;
}

html {
  scroll-behavior: smooth;
}
</style>
