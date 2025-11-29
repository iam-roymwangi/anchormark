<template>
    <div class="min-h-screen bg-white">
        <!-- Navigation -->
        <PublicLayout>
            <section class="bg-[#F5F5F0] px-4 pt-32 pb-20 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-7xl">
                    <!-- Hero Title -->
                    <div class="mb-12 text-center">
                        <h1
                            class="mb-6 font-serif text-5xl leading-tight text-[#333333] sm:text-6xl lg:text-5xl xl:text-6xl"
                        >
                            Premium hospitality<br />solutions for
                            exceptional<br /><span class="text-[#C09930]"
                                >hotels</span
                            >
                        </h1>
                        <p
                            class="mx-auto max-w-3xl text-lg leading-relaxed text-[#333333]/70 sm:text-xl"
                        >
                            Transform your guest experience with Anchormark's
                            curated collection of premium supplies.
                        </p>
                    </div>

                    <!-- Bento Grid Layout -->
                    <div class="mb-12 grid grid-cols-2 gap-4 md:grid-cols-4">
                        <!-- Large Product - First Product -->
                        <div
                            v-if="featuredProducts.length > 0"
                            class="group relative col-span-2 row-span-2 cursor-pointer overflow-hidden rounded-2xl"
                            :class="{ 'animate-fade-in': isVisible }"
                        >
                            <img
                                :src="featuredProducts[0].image"
                                :alt="featuredProducts[0].name"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#003366]/80 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                            >
                                <div
                                    class="absolute bottom-6 left-6 right-6 text-white"
                                >
                                    <h3 class="mb-2 font-serif text-2xl">
                                        {{ featuredProducts[0].name }}
                                    </h3>
                                    <p class="mb-4 text-sm opacity-90">
                                        {{ featuredProducts[0].description ? featuredProducts[0].description.substring(0, 100) + '...' : 'Premium quality product' }}
                                    </p>
                                    <Link
                                        :href="`/product-details?slug=${featuredProducts[0].slug}`"
                                        class="inline-flex items-center gap-2 rounded-full bg-[#C09930] px-6 py-2 text-sm font-medium text-white transition-all duration-300 hover:bg-[#247047] hover:scale-105"
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
                            class="group relative col-span-1 row-span-1 cursor-pointer overflow-hidden rounded-2xl"
                            :class="{
                                'animate-fade-in-delay-1': isVisible && index === 0,
                                'animate-fade-in-delay-2': isVisible && index === 1,
                                'animate-fade-in-delay-3': isVisible && index === 2,
                                'animate-fade-in-delay-4': isVisible && index === 3,
                            }"
                        >
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            />
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-[#C09930]/80 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                            >
                                <div
                                    class="absolute bottom-4 left-4 right-4 text-white"
                                >
                                    <h3 class="mb-2 font-serif text-lg">
                                        {{ product.name }}
                                    </h3>
                                    <Link
                                        :href="`/product-details?slug=${product.slug}`"
                                        class="inline-flex items-center gap-1 rounded-full bg-white/20 backdrop-blur-sm px-4 py-1.5 text-xs font-medium text-white transition-all duration-300 hover:bg-white/30 hover:scale-105"
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
                            class="col-span-2 row-span-2 flex items-center justify-center rounded-2xl bg-gray-100"
                        >
                            <p class="text-gray-500">No featured products available</p>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div
                        class="flex flex-col items-center justify-center gap-4 sm:flex-row"
                    >
                        <Link
                            href="/products"
                            class="flex items-center justify-center gap-2 rounded-full bg-[#C09930] px-10 py-5 text-lg font-medium text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-[#247047] hover:shadow-xl"
                        >
                            Explore Our Collection
                            <ArrowRight class="h-5 w-5" />
                        </Link>

                        <Link
                            href="/contact"
                            class="rounded-full border-2 border-[#003366] px-10 py-5 text-lg font-medium text-[#003366] shadow-lg transition-all duration-300 hover:scale-105 hover:bg-[#003366] hover:text-white hover:shadow-xl"
                        >
                            Contact Us
                        </Link>
                    </div>

                    <!-- Scroll Indicator -->
                    <div class="mt-16 flex justify-center">
                        <div class="animate-bounce">
                            <ChevronDown class="h-8 w-8 text-[#C09930]" />
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
                                class="mb-2 text-4xl font-bold text-[#C09930] sm:text-5xl"
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
                                                class="flex items-center gap-2 font-medium text-[#C09930] transition-all hover:gap-3"
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
                                class="mb-6 font-serif text-4xl text-[#333333] sm:text-5xl"
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
                                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-[#C09930]"
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
                            class="rounded-lg bg-[#C09930] px-8 py-4 text-lg font-medium text-white transition-colors hover:bg-[#247047]"
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
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600;700&display=swap');

.font-serif {
    font-family: 'Playfair Display', serif;
}

* {
    font-family: 'Inter', sans-serif;
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
</style>
