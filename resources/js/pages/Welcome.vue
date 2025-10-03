<template>
    <div class="min-h-screen bg-white">
        <!-- Navigation -->
       <PublicLayout>
        <section class="bg-[#F5F5F0] px-4 pt-32 pb-20 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-7xl">
                <!-- Hero Title -->
                <div class="mb-12 text-center">
                    <h1
                        class="mb-6 font-serif text-5xl leading-tight text-[#333333] sm:text-6xl lg:text-7xl xl:text-8xl"
                    >
                        Premium hospitality<br />solutions for exceptional<br /><span
                            class="text-[#2E8B57]"
                            >hotels</span
                        >
                    </h1>
                    <p
                        class="mx-auto max-w-3xl text-lg leading-relaxed text-[#333333]/70 sm:text-xl"
                    >
                        Transform your guest experience with AnchorMark's
                        curated collection of premium beddings, kitchenware, and
                        furniture
                    </p>
                </div>

                <!-- Bento Grid Layout -->
                <div class="mb-12 grid grid-cols-2 gap-4 md:grid-cols-4">
                    <!-- Large Image - Luxury Bedding -->
                    <div
                        class="group relative col-span-2 row-span-2 cursor-pointer overflow-hidden rounded-2xl"
                        :class="{ 'animate-fade-in': isVisible }"
                    >
                        <img
                            src="/assets/images/Hotel-design.webp"
                            alt="Premium hotel bedding"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[#003366]/80 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        >
                            <div class="absolute bottom-6 left-6 text-white">
                                <h3 class="mb-2 font-serif text-2xl">
                                    Premium Bedding
                                </h3>
                                <p class="text-sm opacity-90">
                                    Luxury linens for ultimate comfort
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Kitchenware Image -->
                    <div
                        class="group relative col-span-1 row-span-1 cursor-pointer overflow-hidden rounded-2xl"
                        :class="{ 'animate-fade-in-delay-1': isVisible }"
                    >
                        <img
                            src="https://www.cookmanequipments.com/uploaded_files/897750.png"
                            alt="Premium kitchenware"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[#2E8B57]/80 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        >
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="font-serif text-lg">Kitchenware</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Furniture Image -->
                    <div
                        class="group relative col-span-1 row-span-1 cursor-pointer overflow-hidden rounded-2xl"
                        :class="{ 'animate-fade-in-delay-2': isVisible }"
                    >
                        <img
                            src="https://hotelfurnitureconcept.com/wp-content/uploads/2022/04/hotel-furniture-manufacturers-2.jpg"
                            alt="Hotel furniture"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[#2E8B57]/80 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        >
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="font-serif text-lg">Furniture</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Hotel Room Detail -->
                    <div
                        class="group relative col-span-1 row-span-1 cursor-pointer overflow-hidden rounded-2xl"
                        :class="{ 'animate-fade-in-delay-3': isVisible }"
                    >
                        <img
                            src="/assets/images/Hotel-design.webp"
                            alt="Hotel bathroom amenities"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[#003366]/80 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        >
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="font-serif text-lg">Amenities</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Restaurant Setting -->
                    <div
                        class="group relative col-span-1 row-span-1 cursor-pointer overflow-hidden rounded-2xl"
                        :class="{ 'animate-fade-in-delay-4': isVisible }"
                    >
                        <img
                            src="https://images.unsplash.com/photo-1682778418768-16081e4470a1?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cmVzdGF1cmFudCUyMGJhY2tncm91bmR8ZW58MHx8MHx8fDA%3D"
                            alt="Restaurant table setting"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                        />
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-[#003366]/80 to-transparent opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        >
                            <div class="absolute bottom-4 left-4 text-white">
                                <h3 class="font-serif text-lg">Dining</h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div
                    class="flex flex-col items-center justify-center gap-4 sm:flex-row"
                >
                    <button
                        class="flex items-center justify-center gap-2 rounded-full bg-[#2E8B57] px-10 py-5 text-lg font-medium text-white shadow-lg transition-all duration-300 hover:scale-105 hover:bg-[#247047] hover:shadow-xl"
                    >
                        Explore Our Collection
                        <ArrowRight class="h-5 w-5" />
                    </button>
                    <button
                        class="rounded-full border-2 border-[#003366] px-10 py-5 text-lg font-medium text-[#003366] shadow-lg transition-all duration-300 hover:scale-105 hover:bg-[#003366] hover:text-white hover:shadow-xl"
                    >
                        Request Catalog
                    </button>
                </div>

                <!-- Scroll Indicator -->
                <div class="mt-16 flex justify-center">
                    <div class="animate-bounce">
                        <ChevronDown class="h-8 w-8 text-[#2E8B57]" />
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
                            class="mb-2 text-4xl font-bold text-[#2E8B57] sm:text-5xl"
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
                        Carefully curated collections designed to elevate every
                        aspect of your hospitality business
                    </p>
                </div>

                <div class="grid gap-8 md:grid-cols-3">
                    <div
                        v-for="category in categories"
                        :key="category.title"
                        class="group cursor-pointer overflow-hidden rounded-2xl bg-white shadow-lg transition-shadow hover:shadow-2xl"
                    >
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
                            <h3 class="mb-2 text-2xl font-bold text-[#333333]">
                                {{ category.title }}
                            </h3>
                            <p class="mb-4 text-[#333333]/70">
                                {{ category.description }}
                            </p>
                            <button
                                class="flex items-center gap-2 font-medium text-[#2E8B57] transition-all hover:gap-3"
                            >
                                Explore Collection
                                <ArrowRight class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
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
                                    class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-lg bg-[#2E8B57]"
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
        <section class="bg-[#003366] py-20 text-white">
            <div class="mx-auto max-w-4xl px-4 text-center sm:px-6 lg:px-8">
                <h2 class="mb-6 font-serif text-4xl sm:text-5xl">
                    Ready to elevate your hospitality experience?
                </h2>
                <p
                    class="mb-8 text-lg leading-relaxed text-white/90 sm:text-xl"
                >
                    Join hundreds of hotels worldwide who trust AnchorMark for
                    their premium hospitality needs. Get a personalized quote
                    today.
                </p>
                <div class="flex flex-col justify-center gap-4 sm:flex-row">
                    <button
                        class="rounded-lg bg-[#2E8B57] px-8 py-4 text-lg font-medium text-white transition-colors hover:bg-[#247047]"
                    >
                        Request a Quote
                    </button>
                    <button
                        class="rounded-lg border-2 border-white px-8 py-4 text-lg font-medium text-white transition-colors hover:bg-white hover:text-[#003366]"
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
import { onMounted, ref } from 'vue';

const stats = [
    { value: '500+', label: 'Hotels Served' },
    { value: '15+', label: 'Years Experience' },
    { value: '50K+', label: 'Products Delivered' },
    { value: '98%', label: 'Client Satisfaction' },
];

const categories = [
    {
        title: 'Premium Beddings',
        description:
            'Luxurious linens, duvets, and pillows crafted for ultimate guest comfort',
        image: '/assets/images/Hotel-design.webp',
        icon: Bed,
    },
    {
        title: 'Professional Kitchenware',
        description:
            'Commercial-grade cookware and dining essentials for hotel kitchens',
        image: '/assets/images/Hotel-design.webp',
        icon: UtensilsCrossed,
    },
    {
        title: 'Designer Furniture',
        description:
            'Elegant and durable furniture pieces that define sophisticated spaces',
        image: '/assets/images/Hotel-design.webp',
        icon: Armchair,
    },
];

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
