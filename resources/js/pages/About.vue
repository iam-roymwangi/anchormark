<template>
    <div class="min-h-screen bg-white">
        <PublicLayout>
            <!-- Hero Section with Image Overlay  -->
            <section
                class="relative flex h-screen items-center justify-center overflow-hidden"
            >
                <div
                    class="absolute inset-0 z-10 bg-gradient-to-b from-[#003366]/80 to-[#003366]/60"
                ></div>
                <img
                    src="/assets/images/Hotel-design.webp"
                    alt="Luxury Hotel"
                    class="absolute inset-0 h-full w-full object-cover"
                />
                <div
                    class="relative z-20 mx-auto max-w-4xl px-6 text-center text-white"
                    :class="{ 'animate-fade-in': isVisible }"
                >
                    <h1
                        class="mb-6 text-5xl leading-tight font-bold md:text-7xl"
                    >
                        Elevating hospitality through quality and innovation
                    </h1>
                    <p
                        class="mx-auto max-w-3xl text-xl leading-relaxed text-gray-200 md:text-2xl"
                    >
                        AnchorMark is a trusted partner in the hospitality
                        industry, providing premium products that transform
                        guest experiences.
                    </p>
                </div>
                <div
                    class="absolute bottom-8 left-1/2 z-20 -translate-x-1/2 transform animate-bounce"
                >
                    <ChevronDown :size="32" class="text-white" />
                </div>
            </section>

            <!-- Mission Statement  -->
            <section class="bg-white px-6 py-24">
                <div class="mx-auto max-w-4xl text-center">
                    <div
                        class="mb-6 inline-block rounded-full bg-[#E0E0E0] px-4 py-2 text-sm font-semibold text-[#003366]"
                    >
                        Our Mission
                    </div>
                    <h2
                        class="mb-8 text-4xl leading-tight font-bold text-[#003366] md:text-5xl"
                    >
                        Crafting exceptional experiences, one product at a time
                    </h2>
                    <p class="text-xl leading-relaxed text-[#333333]">
                        We believe that every detail matters in creating
                        memorable guest experiences. From luxurious beddings to
                        elegant kitchenware and timeless furniture, we supply
                        hotels with products that embody quality, comfort, and
                        sophistication.
                    </p>
                </div>
            </section>

            <!-- Core Values - Numbered Sections  -->
            <section class="bg-[#F5F5F0] px-6 py-24">
                <div class="mx-auto max-w-6xl">
                    <h2
                        class="mb-16 text-center text-4xl font-bold text-[#003366] md:text-5xl"
                    >
                        Our Core Values
                    </h2>
                    <div class="grid gap-16 md:grid-cols-2">
                        <div
                            v-for="(value, index) in values"
                            :key="index"
                            class="group"
                            :class="{ 'animate-slide-up': isVisible }"
                            :style="{ animationDelay: `${index * 100}ms` }"
                        >
                            <div
                                class="mb-4 text-8xl font-bold text-[#C09930] opacity-20"
                            >
                                {{ index + 1 }}
                            </div>
                            <h3 class="mb-4 text-2xl font-bold text-[#003366]">
                                {{ value.title }}
                            </h3>
                            <p class="text-lg leading-relaxed text-[#333333]">
                                {{ value.description }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Image Section with Text Overlay  -->
            <section
                class="relative flex h-[600px] items-center justify-center overflow-hidden"
            >
                <div class="absolute inset-0 z-10 bg-[#003366]/70"></div>
                <img
                    src="/assets/images/Hotel-Amenities.webp"
                    alt="Craftsmanship"
                    class="absolute inset-0 h-full w-full object-cover"
                />
                <div
                    class="relative z-20 mx-auto max-w-4xl px-6 text-center text-white"
                >
                    <h2 class="mb-6 text-4xl font-bold md:text-5xl">
                        Combining innovation, durability, and elegance
                    </h2>
                    <p class="text-xl leading-relaxed text-gray-200">
                        AnchorMark serves as a key player in the hospitality
                        supply chain. We provide sustainable solutions that meet
                        the evolving needs of modern hotels.
                    </p>
                </div>
            </section>

            <!-- Stats Section  -->
            <section class="bg-[#003366] px-6 py-24 text-white">
                <div class="mx-auto max-w-7xl">
                    <div class="grid gap-12 md:grid-cols-4">
                        <div
                            v-for="(stat, index) in stats"
                            :key="index"
                            class="group text-center transition-transform duration-300 hover:scale-105"
                        >
                            <div
                                class="mb-4 text-5xl font-bold text-[#C09930] md:text-6xl"
                            >
                                {{ stat.value }}
                            </div>
                            <div class="text-xl text-gray-300">
                                {{ stat.label }}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Approach Section - Three Cards  -->
            <section class="bg-white px-6 py-24">
                <div class="mx-auto max-w-7xl">
                    <h2
                        class="mb-6 text-center text-4xl font-bold text-[#003366] md:text-5xl"
                    >
                        A comprehensive approach for lasting partnerships
                    </h2>
                    <p
                        class="mx-auto mb-16 max-w-3xl text-center text-xl text-[#333333]"
                    >
                        We work closely with hotels to understand their unique
                        needs and deliver tailored solutions.
                    </p>
                    <div class="grid gap-8 md:grid-cols-3">
                        <div
                            v-for="(approach, index) in approaches"
                            :key="index"
                            class="group relative overflow-hidden rounded-lg shadow-lg transition-all duration-500 hover:shadow-2xl"
                        >
                            <div class="relative h-80 overflow-hidden">
                                <img
                                    :src="approach.image"
                                    :alt="approach.title"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                                />
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-[#003366] to-transparent opacity-80"
                                ></div>
                            </div>
                            <div
                                class="absolute right-0 bottom-0 left-0 p-6 text-white"
                            >
                                <h3 class="mb-2 text-2xl font-bold">
                                    {{ approach.title }}
                                </h3>
                                <p class="text-gray-200">
                                    {{ approach.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Timeline Section  -->
            <section class="bg-[#F5F5F0] px-6 py-24">
                <div class="mx-auto max-w-4xl">
                    <h2
                        class="mb-16 text-center text-4xl font-bold text-[#003366] md:text-5xl"
                    >
                        Our Journey
                    </h2>
                    <div class="space-y-12">
                        <div
                            v-for="(milestone, index) in timeline"
                            :key="index"
                            class="group flex gap-8"
                            :class="{ 'animate-slide-in': isVisible }"
                            :style="{ animationDelay: `${index * 150}ms` }"
                        >
                            <div class="w-32 flex-shrink-0 text-right">
                                <div class="text-2xl font-bold text-[#C09930]">
                                    {{ milestone.year }}
                                </div>
                            </div>
                            <div
                                class="relative w-px flex-shrink-0 bg-[#C09930]"
                            >
                                <div
                                    class="absolute top-0 left-1/2 h-4 w-4 -translate-x-1/2 transform rounded-full bg-[#C09930] transition-transform group-hover:scale-150"
                                ></div>
                            </div>
                            <div class="flex-1 pb-12">
                                <h3
                                    class="mb-2 text-xl font-bold text-[#003366]"
                                >
                                    {{ milestone.title }}
                                </h3>
                                <p class="leading-relaxed text-[#333333]">
                                    {{ milestone.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Team Section  -->
            <section class="bg-white px-6 py-24">
                <div class="mx-auto max-w-7xl">
                    <div class="mb-16 text-center">
                        <h2
                            class="mb-6 text-4xl font-bold text-[#003366] md:text-5xl"
                        >
                            Meet Our Leadership
                        </h2>
                        <p class="mx-auto max-w-3xl text-xl text-[#333333]">
                            Our experienced team brings decades of hospitality
                            industry expertise to every partnership.
                        </p>
                    </div>
                    <div class="grid gap-12 md:grid-cols-3">
                        <div
                            v-for="(member, index) in team"
                            :key="index"
                            class="group text-center"
                        >
                            <div
                                class="relative mb-6 overflow-hidden rounded-lg"
                            >
                                <img
                                    :src="member.image"
                                    :alt="member.name"
                                    class="h-80 w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                />
                                <div
                                    class="absolute inset-0 bg-[#003366]/0 transition-all duration-300 group-hover:bg-[#003366]/20"
                                ></div>
                            </div>
                            <h3 class="mb-2 text-2xl font-bold text-[#003366]">
                                {{ member.name }}
                            </h3>
                            <p class="mb-3 font-semibold text-[#C09930]">
                                {{ member.role }}
                            </p>
                            <p class="leading-relaxed text-[#333333]">
                                {{ member.bio }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section  -->
            <section class="bg-[#003366] px-6 py-24 text-center text-white">
                <div class="mx-auto max-w-4xl">
                    <h2 class="mb-6 text-4xl font-bold md:text-5xl">
                        Ready to elevate your hotel experience?
                    </h2>
                    <p class="mb-8 text-xl leading-relaxed text-gray-200">
                        Partner with AnchorMark and discover how our premium
                        products can transform your guest satisfaction.
                    </p>
                    <div class="flex flex-col justify-center gap-4 sm:flex-row">
                        <Link
                            href="/products"
                            class="rounded-full bg-[#C09930] px-8 py-4 text-lg font-semibold text-white transition-all hover:scale-105 hover:bg-[#267347]"
                        >
                            Order Now
                        </Link>

                        <Link
                            href="/contact"
                            class="rounded-full border-2 border-white bg-transparent px-8 py-4 text-lg font-semibold text-white transition-all hover:bg-white hover:text-[#003366]"
                        >
                            Contact Us
                        </Link>
                    </div>
                </div>
            </section>
        </PublicLayout>
    </div>
</template>

<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
import { ChevronDown } from 'lucide-vue-next';
import { onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const isVisible = ref(false);

interface Value {
    title: string;
    description: string;
}

interface Stat {
    value: string;
    label: string;
}

interface Approach {
    title: string;
    description: string;
    image: string;
}

interface Timeline {
    year: string;
    title: string;
    description: string;
}

interface TeamMember {
    name: string;
    role: string;
    bio: string;
    image: string;
}

const values: Value[] = [
    {
        title: 'Quality First',
        description:
            'We partner with hotels to ensure every product meets the highest standards of craftsmanship and durability, creating lasting impressions for guests.',
    },
    {
        title: 'Innovation & Sustainability',
        description:
            'We embrace innovation through sustainable practices, offering eco-friendly solutions that align with modern hospitality values.',
    },
    {
        title: 'Customer Partnership',
        description:
            'We build long-term relationships with our clients, understanding their unique needs and delivering tailored solutions that exceed expectations.',
    },
    {
        title: 'Excellence in Service',
        description:
            'From initial consultation to delivery, we provide exceptional service at every touchpoint, ensuring seamless integration of our products.',
    },
];

const stats: Stat[] = [
    { value: '500+', label: 'Hotels Served' },
    { value: '15+', label: 'Years Experience' },
    { value: '10K+', label: 'Products Delivered' },
    { value: '99%', label: 'Client Satisfaction' },
];

const approaches: Approach[] = [
    {
        title: 'Partnership',
        description:
            'Building lasting relationships through trust and collaboration',
        image: '/assets/images/Partnership.jpg',
    },
    {
        title: 'Customization',
        description:
            'Tailored solutions that meet your unique hospitality needs',
        image: '/assets/images/Customization.jpg',
    },
    {
        title: 'Reliability',
        description: 'Consistent quality and timely delivery you can depend on',
        image: '/assets/images/Reliability.webp',
    },
];

const timeline: Timeline[] = [
    {
        year: '2009',
        title: 'Foundation',
        description:
            'AnchorMark was founded with a vision to revolutionize hospitality product supply, starting with premium beddings for boutique hotels.',
    },
    {
        year: '2013',
        title: 'Expansion',
        description:
            'Expanded our product line to include kitchenware and dining essentials, serving over 100 hotels across the region.',
    },
    {
        year: '2017',
        title: 'Furniture Division',
        description:
            'Launched our furniture division, offering custom-designed pieces that blend functionality with elegant aesthetics.',
    },
    {
        year: '2020',
        title: 'Sustainability Initiative',
        description:
            'Introduced our eco-friendly product line, partnering with sustainable manufacturers to reduce environmental impact.',
    },
    {
        year: '2024',
        title: 'Global Reach',
        description:
            'Now serving 500+ hotels worldwide, maintaining our commitment to quality and innovation in every product we deliver.',
    },
];

const team: TeamMember[] = [
    {
        name: 'Faith Munyiri',
        role: 'Chief Executive Officer',
        bio: 'With 20 years in hospitality management, Faith leads Anchormark with a vision for excellence.',
        image: '/assets/images/profiles/Faith.jpg',
    },
];

onMounted(() => {
    isVisible.value = true;
});
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
