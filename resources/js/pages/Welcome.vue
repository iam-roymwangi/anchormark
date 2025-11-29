<template>
    <div class="min-h-screen bg-white">
        <!-- Navigation -->
        <PublicLayout>
            <section class="relative px-4 pt-40 pb-32 sm:px-6 lg:px-8 overflow-hidden">
                <!-- Background Image -->
                <div class="absolute inset-0 z-0">
                    <img 
                        src="/assets/images/Hotel-design.webp" 
                        alt="Luxury Hotel Background" 
                        class="w-full h-full object-cover"
                    />
                    <!-- Dark Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-b from-black/70 via-black/60 to-black/50"></div>
                </div>

                <!-- Decorative Background Elements -->
                <div class="absolute inset-0 overflow-hidden pointer-events-none z-10">
                    <div class="absolute top-20 left-10 w-72 h-72 bg-[#AE8625]/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-20 right-10 w-96 h-96 bg-[#AE8625]/8 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#AE8625]/5 rounded-full blur-3xl"></div>
                </div>

                <div class="relative mx-auto max-w-7xl z-20">
                    <!-- Hero Title Section -->
                    <div class="mb-16 text-center">
                        <!-- Decorative Divider -->
                        <div class="flex items-center justify-center gap-4 mb-8">
                            <div class="h-px w-16 bg-gradient-to-r from-transparent via-[#AE8625]/50 to-[#AE8625]"></div>
                            <div class="w-2 h-2 rounded-full bg-[#AE8625] shadow-lg shadow-[#AE8625]/50"></div>
                            <div class="h-px w-16 bg-gradient-to-l from-transparent via-[#AE8625]/50 to-[#AE8625]"></div>
                        </div>

                        <!-- Main Heading -->
                        <h1
                            class="mb-8 font-serif text-3xl leading-[1.1] text-white sm:text-4xl lg:text-5xl xl:text-5xl tracking-tight drop-shadow-2xl"
                            :class="{ 'animate-fade-in': isVisible }"
                        >
                            <span class="block">Premium</span>
                            <span class="block italic font-light text-[#AE8625] drop-shadow-lg">hospitality</span>
                            <span class="block">solutions for</span>
                            <span class="block italic font-light">exceptional</span>
                            <span class="block text-[#AE8625] drop-shadow-lg">hotels</span>
                        </h1>

                        <!-- Subtitle -->
                        <p
                            class="mx-auto max-w-2xl text-xl leading-relaxed text-white/90 sm:text-2xl mb-12 font-light tracking-wide drop-shadow-lg"
                            :class="{ 'animate-fade-in-delay-1': isVisible }"
                        >
                            Transform your guest experience with our
                            <span class="italic text-[#AE8625]">curated collection</span>
                            of premium hospitality supplies.
                        </p>

                        <!-- CTA Buttons - Premium Style -->
                        <div
                            class="flex flex-col items-center justify-center gap-6 sm:flex-row mb-16"
                            :class="{ 'animate-fade-in-delay-2': isVisible }"
                        >
                            <Link
                                href="/products"
                                class="group relative flex items-center justify-center gap-3 px-12 py-5 text-lg font-medium text-white bg-[#AE8625] rounded-full shadow-xl shadow-[#AE8625]/20 transition-all duration-500 hover:scale-105 hover:bg-[#9A7520] hover:shadow-2xl hover:shadow-[#AE8625]/30 overflow-hidden"
                            >
                                <span class="absolute inset-0 bg-gradient-to-r from-[#AE8625] to-[#9A7520] opacity-0 group-hover:opacity-100 transition-opacity duration-500"></span>
                                <span class="relative z-10 flex items-center gap-3">
                                    Explore Our Collection
                                    <ArrowRight class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1" />
                                </span>
                            </Link>

                            <Link
                                href="/contact"
                                class="group relative flex items-center justify-center gap-3 px-12 py-5 text-lg font-medium text-white bg-transparent border-2 border-white/30 rounded-full shadow-lg transition-all duration-500 hover:scale-105 hover:border-[#AE8625] hover:text-[#AE8625] hover:shadow-xl hover:bg-white/10 backdrop-blur-sm"
                            >
                                <span>Contact Us</span>
                            </Link>
                        </div>

                        <!-- Decorative Divider -->
                        <div class="flex items-center justify-center gap-4">
                            <div class="h-px w-16 bg-gradient-to-r from-transparent via-[#AE8625]/50 to-[#AE8625]"></div>
                            <div class="w-2 h-2 rounded-full bg-[#AE8625] shadow-lg shadow-[#AE8625]/50"></div>
                            <div class="h-px w-16 bg-gradient-to-l from-transparent via-[#AE8625]/50 to-[#AE8625]"></div>
                        </div>
                    </div>

                    <!-- Featured Products Grid - Enhanced -->
                    <div class="mb-20 grid grid-cols-2 gap-6 md:grid-cols-4">
                        <!-- Large Product - First Product -->
                        <div
                            v-if="featuredProducts.length > 0"
                            class="group relative col-span-2 row-span-2 cursor-pointer overflow-hidden rounded-3xl shadow-2xl"
                            :class="{ 'animate-fade-in-delay-3': isVisible }"
                        >
                            <div class="absolute inset-0 bg-gradient-to-br from-[#AE8625]/10 to-transparent z-10"></div>
                            <img
                                :src="featuredProducts[0].image"
                                :alt="featuredProducts[0].name"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#333333]/90 via-[#333333]/50 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                            >
                                <div
                                    class="absolute bottom-8 left-8 right-8 text-white"
                                >
                                    <div class="mb-3 text-xs font-light tracking-widest uppercase text-[#AE8625] opacity-90">Featured</div>
                                    <h3 class="mb-3 font-serif text-3xl font-light">
                                        {{ featuredProducts[0].name }}
                                    </h3>
                                    <p class="mb-6 text-sm opacity-90 font-light leading-relaxed">
                                        {{ featuredProducts[0].description ? featuredProducts[0].description.substring(0, 120) + '...' : 'Premium quality product' }}
                                    </p>
                                    <Link
                                        :href="`/product-details?slug=${featuredProducts[0].slug}`"
                                        class="inline-flex items-center gap-2 rounded-full bg-[#AE8625] px-8 py-3 text-sm font-medium text-white transition-all duration-300 hover:bg-[#9A7520] hover:scale-105 shadow-lg"
                                        @click.stop
                                    >
                                        View Details
                                        <ArrowRight class="h-4 w-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Small Products - Remaining 4 Products -->
                        <div
                            v-for="(product, index) in featuredProducts.slice(1, 5)"
                            :key="product.id"
                            class="group relative col-span-1 row-span-1 cursor-pointer overflow-hidden rounded-3xl shadow-xl"
                            :class="{
                                'animate-fade-in-delay-4': isVisible && index === 0,
                                'animate-fade-in-delay-5': isVisible && index === 1,
                                'animate-fade-in-delay-6': isVisible && index === 2,
                                'animate-fade-in-delay-7': isVisible && index === 3,
                            }"
                        >
                            <div class="absolute inset-0 bg-gradient-to-br from-[#AE8625]/5 to-transparent z-10"></div>
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#AE8625]/90 via-[#AE8625]/60 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                            >
                                <div
                                    class="absolute bottom-5 left-5 right-5 text-white"
                                >
                                    <h3 class="mb-3 font-serif text-lg font-light">
                                        {{ product.name }}
                                    </h3>
                                    <Link
                                        :href="`/product-details?slug=${product.slug}`"
                                        class="inline-flex items-center gap-1 rounded-full bg-white/20 backdrop-blur-sm px-4 py-2 text-xs font-medium text-white transition-all duration-300 hover:bg-white/30 hover:scale-105 border border-white/20"
                                        @click.stop
                                    >
                                        View Details
                                        <ArrowRight class="h-3 w-3" />
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Fallback: Show placeholder if no products -->
                        <div
                            v-if="featuredProducts.length === 0"
                            class="col-span-2 row-span-2 flex items-center justify-center rounded-3xl bg-gradient-to-br from-gray-100 to-gray-50 border border-gray-200"
                        >
                            <p class="text-gray-400 font-light">No featured products available</p>
                        </div>
                    </div>

                    <!-- Scroll Indicator - Enhanced -->
                    <div class="flex flex-col items-center justify-center gap-3">
                        <span class="text-xs font-light tracking-widest uppercase text-white/60 drop-shadow-md">Scroll</span>
                        <div class="animate-bounce">
                            <ChevronDown class="h-6 w-6 text-[#AE8625] drop-shadow-lg" />
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="bg-white py-16">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 gap-8 lg:grid-cols-4">
                        <div
                            v-for="stat in stats"
                            :key="stat.label"
                            class="text-center"
                        >
                            <div
                                class="mb-2 text-4xl font-bold text-[#AE8625] sm:text-5xl"
                            >
                                {{ stat.value }}
                            </div>
                            <div class="text-sm text-[#333333]/70 sm:text-base">
                                {{ stat.label }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Product Categories -->
            <section id="products" class="bg-[#E0E0E0] py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="mb-16 text-center">
                        <h2
                            class="mb-4 font-serif text-4xl text-[#333333] sm:text-5xl"
                        >
                            Our Product Range
                        </h2>
                        <p class="mx-auto max-w-2xl text-lg text-[#333333]/70">
                            Carefully curated collections designed to elevate
                            every aspect of your hospitality business
                        </p>
                    </div>

                    <div class="relative">
                        <swiper v-bind="swiperOptions" class="mySwiper">
                            <swiper-slide
                                v-for="category in categories"
                                :key="category.id"
                            >
                                <div
                                    class="group cursor-pointer overflow-hidden rounded-2xl bg-white shadow-lg transition-shadow hover:shadow-2xl"
                                >
                                    <Link :href="`/products?category=${category.slug}`">
                                        <div class="relative h-64 overflow-hidden">
                                            <img
                                                :src="category.image"
                                                :alt="category.title"
                                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                                            />
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"
                                            ></div>
                                            <component
                                                :is="category.icon"
                                                class="absolute top-4 right-4 h-8 w-8 text-white"
                                            />
                                        </div>
                                        <div class="p-6">
                                            <h3
                                                class="mb-2 text-2xl font-bold text-[#333333]"
                                            >
                                                {{ category.title }}
                                            </h3>
                                            <p class="mb-4 text-[#333333]/70">
                                                {{ category.description }}
                                            </p>
                                            <div
                                                class="flex items-center gap-2 font-medium text-[#AE8625] transition-all hover:gap-3"
                                            >
                                                Explore Collection
                                                <ArrowRight class="h-4 w-4" />
                                            </div>
                                        </div>
                                    </Link>
                                </div>
                            </swiper-slide>
                        </swiper>

                        <!-- Custom Navigation Buttons -->
                        <div class="swiper-button-prev absolute top-1/2 left-0 z-10 -translate-y-1/2 cursor-pointer rounded-full bg-white/80 p-3 shadow-md transition-all hover:bg-white focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6 text-gray-800">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                        </div>
                        <div class="swiper-button-next absolute top-1/2 right-0 z-10 -translate-y-1/2 cursor-pointer rounded-full bg-white/80 p-3 shadow-md transition-all hover:bg-white focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6 text-gray-800">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>

                        <!-- Custom Pagination -->
                        <div class="swiper-pagination mt-8 text-center"></div>
                    </div>
                </div>
            </section>

            <!-- Why Choose Us -->
            <section class="bg-white py-20">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="grid items-center gap-12 lg:grid-cols-2">
                        <div class="order-2 lg:order-1">
                            <img
                                src="/assets/images/Hotel-design.webp"
                                alt="Professional kitchenware"
                                class="w-full rounded-2xl shadow-xl"
                            />
                        </div>
                        <div class="order-1 lg:order-2">
                            <h2
                                class="mb-6 font-serif text-xl text-[#333333] sm:text-3xl"
                            >
                                Why leading hotels choose AnchorMark
                            </h2>
                            <div class="space-y-6">
                                <div
                                    v-for="feature in features"
                                    :key="feature.title"
                                    class="flex gap-4"
                                >
                                    <div
                                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-[#AE8625]"
                                    >
                                        <component
                                            :is="feature.icon"
                                            class="h-6 w-6 text-white"
                                        />
                                    </div>
                                    <div>
                                        <h3
                                            class="mb-2 text-xl font-bold text-[#333333]"
                                        >
                                            {{ feature.title }}
                                        </h3>
                                        <p class="text-[#333333]/70">
                                            {{ feature.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="bg-[#F5F5F0] py-20 text-[#333333]">
                <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
                    <h2 class="mb-6 font-serif text-4xl sm:text-5xl">
                        Ready to elevate your hospitality experience?
                    </h2>
                    <p
                        class="mb-8 text-lg leading-relaxed text-[#333333]/90 sm:text-xl"
                    >
                        Join hundreds of hotels worldwide who trust AnchorMark
                        for their premium hospitality needs. Get a personalized
                        quote today.
                    </p>
                    <div class="flex flex-col justify-center gap-4 sm:flex-row">
                        <button
                            class="rounded-lg bg-[#AE8625] px-8 py-4 text-lg font-medium text-white transition-colors hover:bg-[#247047]"
                        >
                            Request a Quote
                        </button>
                        <button
                            class="rounded-lg border-2 border-[#333333] px-8 py-4 text-lg font-medium text-[#333333] transition-colors hover:bg-white hover:text-[#003366]"
                        >
                            View Catalog
                        </button>
                    </div>
                </div>
            </section>
        </PublicLayout>
    </div>
</template>

<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
import {
    Armchair,
    ArrowRight,
    Award,
    Bed,
    ChevronDown,
    Shield,
    Sparkles,
    Truck,
    UtensilsCrossed,
} from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';

// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from 'swiper/vue';

// Import Swiper modules
import { Navigation, Pagination } from 'swiper/modules';

// Import Swiper styles
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
interface Category {
    id: number;
    name: string;
    slug: string;
    description: string;
    image: string | null;
}

interface FeaturedProduct {
    id: number;
    name: string;
    slug: string;
    description: string;
    price: number;
    image: string;
    category: {
        id: number;
        name: string;
        slug: string;
    };
}

interface Props {
    categories?: Category[];
    featuredProducts?: FeaturedProduct[];
}

const props = withDefaults(defineProps<Props>(), {
    categories: () => [],
    featuredProducts: () => [],
});

const stats = [
    { value: '500+', label: 'Hotels Served' },
    { value: '15+', label: 'Years Experience' },
    { value: '50K+', label: 'Products Delivered' },
    { value: '98%', label: 'Client Satisfaction' },
];

// Icon mapping based on category name
const getCategoryIcon = (categoryName: string) => {
    const name = categoryName.toLowerCase();
    if (name.includes('bed') || name.includes('bedding') || name.includes('linen')) {
        return Bed;
    }
    if (name.includes('kitchen') || name.includes('cookware') || name.includes('dining')) {
        return UtensilsCrossed;
    }
    if (name.includes('furniture') || name.includes('chair') || name.includes('table')) {
        return Armchair;
    }
    // Default icon
    return Bed;
};

// Image mapping - use category image from database or fallback to default
const getCategoryImage = (categoryImage: string | null) => {
    // Use category image from database if available, otherwise use default
    return categoryImage ? `/storage/${categoryImage}` : '/assets/images/Hotel-design.webp';
};

// Transform database categories to component format
const categories = computed(() => {
    return props.categories.map((category) => ({
        id: category.id,
        title: category.name,
        description: category.description || `Explore our ${category.name} collection`,
        image: getCategoryImage(category.image),
        icon: getCategoryIcon(category.name),
        slug: category.slug,
    }));
});

const features = [
    {
        title: 'Premium Quality',
        description:
            'Every product is carefully selected and tested to meet the highest hospitality standards',
        icon: Award,
    },
    {
        title: 'Fast Delivery',
        description:
            'Reliable logistics network ensuring your orders arrive on time, every time',
        icon: Truck,
    },
    {
        title: 'Warranty Protection',
        description:
            'Comprehensive warranty coverage and dedicated support for all products',
        icon: Shield,
    },
    {
        title: 'Eco-Friendly',
        description:
            'Sustainable sourcing and environmentally conscious manufacturing practices',
        icon: Sparkles,
    },
];

const isVisible = ref(false);

// Swiper options
const swiperOptions = {
    modules: [Navigation, Pagination],
    slidesPerView: 1,
    spaceBetween: 24,
    loop: true,
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
    pagination: {
        clickable: true,
        el: '.swiper-pagination',
    },
    breakpoints: {
        768: {
            slidesPerView: 2,
            spaceBetween: 24,
        },
        1024: {
            slidesPerView: 3,
            spaceBetween: 24,
        },
    },
};

onMounted(() => {
    setTimeout(() => {
        isVisible.value = true;
    }, 100);
});
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

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out forwards;
}

.animate-fade-in-delay-1 {
    animation: fadeIn 0.8s ease-out 0.2s forwards;
    opacity: 0;
}

.animate-fade-in-delay-2 {
    animation: fadeIn 0.8s ease-out 0.4s forwards;
    opacity: 0;
}

.animate-fade-in-delay-3 {
    animation: fadeIn 0.8s ease-out 0.6s forwards;
    opacity: 0;
}

.animate-fade-in-delay-4 {
    animation: fadeIn 0.8s ease-out 0.8s forwards;
    opacity: 0;
}

.animate-fade-in-delay-5 {
    animation: fadeIn 0.8s ease-out 1s forwards;
    opacity: 0;
}

.animate-fade-in-delay-6 {
    animation: fadeIn 0.8s ease-out 1.2s forwards;
    opacity: 0;
}

.animate-fade-in-delay-7 {
    animation: fadeIn 0.8s ease-out 1.4s forwards;
    opacity: 0;
}
</style>
