<template>
    <div class="min-h-screen bg-[#F5F5F0] font-sans">
        <!-- Navigation  -->
        <PublicLayout>
            <!-- Hero Section  -->
            <section class="px-6 py-20">
                <div class="mx-auto max-w-7xl text-center">
                    <h1
                        class="mb-6 font-serif text-6xl font-bold text-[#003366] md:text-7xl"
                    >
                        Get in Touch
                    </h1>
                    <p
                        class="mx-auto max-w-2xl text-xl leading-relaxed text-[#333333]"
                    >
                        Let's discuss how Anchormark Supplies can elevate your
                        hotel's hospitality experience with premium products and
                        exceptional service.
                    </p>
                </div>
            </section>

            <!-- Contact Section  -->
            <section class="px-6 py-16">
                <div class="mx-auto max-w-7xl">
                    <div class="grid gap-12 md:grid-cols-2 lg:gap-20">
                        <!-- Contact Form  -->
                        <div class="order-2 md:order-1">
                            <h2
                                class="mb-8 font-serif text-3xl font-bold text-[#003366]"
                            >
                                Send us a message
                            </h2>
                            <form
                                @submit.prevent="handleSubmit"
                                class="space-y-6"
                            >
                                <div>
                                    <label
                                        for="name"
                                        class="mb-2 block text-sm font-medium text-[#333333]"
                                        >Full Name *</label
                                    >
                                    <input
                                        type="text"
                                        id="name"
                                        v-model="formData.name"
                                        required
                                        class="w-full rounded-lg border border-[#E0E0E0] bg-white px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-[#C09930] focus:outline-none"
                                        placeholder="John Smith"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="email"
                                        class="mb-2 block text-sm font-medium text-[#333333]"
                                        >Email Address *</label
                                    >
                                    <input
                                        type="email"
                                        id="email"
                                        v-model="formData.email"
                                        required
                                        class="w-full rounded-lg border border-[#E0E0E0] bg-white px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-[#C09930] focus:outline-none"
                                        placeholder="john@hotel.com"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="phone"
                                        class="mb-2 block text-sm font-medium text-[#333333]"
                                        >Phone Number</label
                                    >
                                    <input
                                        type="tel"
                                        id="phone"
                                        v-model="formData.phone"
                                        class="w-full rounded-lg border border-[#E0E0E0] bg-white px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-[#C09930] focus:outline-none"
                                        placeholder="+1 (555) 000-0000"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="company"
                                        class="mb-2 block text-sm font-medium text-[#333333]"
                                        >Hotel/Company Name</label
                                    >
                                    <input
                                        type="text"
                                        id="company"
                                        v-model="formData.company"
                                        class="w-full rounded-lg border border-[#E0E0E0] bg-white px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-[#C09930] focus:outline-none"
                                        placeholder="Grand Hotel & Resorts"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="subject"
                                        class="mb-2 block text-sm font-medium text-[#333333]"
                                        >Subject *</label
                                    >
                                    <select
                                        id="subject"
                                        v-model="formData.subject"
                                        required
                                        class="w-full rounded-lg border border-[#E0E0E0] bg-white px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-[#C09930] focus:outline-none"
                                    >
                                        <option value="">
                                            Select a subject
                                        </option>
                                        <option value="general">
                                            General Inquiry
                                        </option>
                                        <option value="quote">
                                            Request a Quote
                                        </option>
                                        <option value="partnership">
                                            Partnership Opportunity
                                        </option>
                                        <option value="support">
                                            Customer Support
                                        </option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>

                                <div>
                                    <label
                                        for="message"
                                        class="mb-2 block text-sm font-medium text-[#333333]"
                                        >Message *</label
                                    >
                                    <textarea
                                        id="message"
                                        v-model="formData.message"
                                        required
                                        rows="6"
                                        class="w-full resize-none rounded-lg border border-[#E0E0E0] bg-white px-4 py-3 transition-all focus:border-transparent focus:ring-2 focus:ring-[#C09930] focus:outline-none"
                                        placeholder="Tell us about your needs..."
                                    ></textarea>
                                </div>

                                <button
                                    type="submit"
                                    class="w-full transform rounded-lg bg-[#C09930] px-8 py-4 font-semibold text-white transition-all hover:scale-[1.02] hover:bg-[#267347] active:scale-[0.98]"
                                >
                                    Send Message
                                </button>
                            </form>
                        </div>

                        <!-- Contact Information  -->
                        <div class="order-1 md:order-2">
                            <h2
                                class="mb-8 font-serif text-3xl font-bold text-[#003366]"
                            >
                                Contact Information
                            </h2>

                            Contact Methods
                            <div class="mb-12 space-y-8">
                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-[#C09930]"
                                    >
                                        <Mail class="h-6 w-6 text-white" />
                                    </div>
                                    <div>
                                        <h3
                                            class="mb-1 font-semibold text-[#003366]"
                                        >
                                            Email
                                        </h3>
                                        <a
                                            href="mailto:info@anchormark.com"
                                            class="text-[#C09930] hover:underline"
                                            >info@anchormark.co.ke</a
                                        >
                                        <br />
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-[#C09930]"
                                    >
                                        <Phone class="h-6 w-6 text-white" />
                                    </div>
                                    <div>
                                        <h3
                                            class="mb-1 font-semibold text-[#003366]"
                                        >
                                            Phone
                                        </h3>
                                        <a
                                            href="tel:+15551234567"
                                            class="text-[#333333] hover:text-[#C09930]"
                                            >+254 723 631 790</a
                                        >
                                        <p class="mt-1 text-sm text-[#666666]">
                                            Mon-Fri: 8:00 AM - 6:00 PM EAT
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-[#C09930]"
                                    >
                                        <MapPin class="h-6 w-6 text-white" />
                                    </div>
                                    <div>
                                        <h3
                                            class="mb-1 font-semibold text-[#003366]"
                                        >
                                            Headquarters
                                        </h3>
                                        <p class="text-[#333333]">
                                            Mombasa Road<br />
                                            Nairobi
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-4">
                                    <div
                                        class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-[#C09930]"
                                    >
                                        <Clock class="h-6 w-6 text-white" />
                                    </div>
                                    <div>
                                        <h3
                                            class="mb-1 font-semibold text-[#003366]"
                                        >
                                            Business Hours
                                        </h3>
                                        <p class="text-[#333333]">
                                            Monday - Friday: 8:00 AM - 6:00
                                            PM<br />
                                            Saturday: 9:00 AM - 5:00 PM<br />
                                            Sunday: Closed
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media  -->
                            <div>
                                <h3 class="mb-4 font-semibold text-[#003366]">
                                    Follow Us
                                </h3>
                                <div class="flex gap-4">
                                    <a
                                        href="#"
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-[#003366] transition-colors hover:bg-[#C09930]"
                                    >
                                        <Linkedin class="h-5 w-5 text-white" />
                                    </a>
                                    <a
                                        href="#"
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-[#003366] transition-colors hover:bg-[#C09930]"
                                    >
                                        <Twitter class="h-5 w-5 text-white" />
                                    </a>
                                    <a
                                        href="#"
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-[#003366] transition-colors hover:bg-[#C09930]"
                                    >
                                        <Instagram class="h-5 w-5 text-white" />
                                    </a>
                                    <a
                                        href="#"
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-[#003366] transition-colors hover:bg-[#C09930]"
                                    >
                                        <Facebook class="h-5 w-5 text-white" />
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Office Locations  -->
            <section class="bg-white px-6 py-16">
                <div class="mx-auto max-w-7xl">
                    <h2
                        class="mb-12 text-center font-serif text-4xl font-bold text-[#003366]"
                    >
                        Our Locations
                    </h2>
                    <div class="grid gap-8 md:grid-cols-3">
                        <div
                            v-for="(location, index) in locations"
                            :key="index"
                            class="transform rounded-lg bg-[#F5F5F0] p-8 transition-all hover:-translate-y-1 hover:shadow-lg"
                        >
                            <div
                                class="mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-[#C09930]"
                            >
                                <Building2 class="h-8 w-8 text-white" />
                            </div>
                            <h3 class="mb-3 text-xl font-bold text-[#003366]">
                                {{ location.city }}
                            </h3>
                            <p class="mb-4 text-[#333333]">
                                {{ location.address }}
                            </p>
                            <p class="text-sm text-[#666666]">
                                {{ location.phone }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Map Section  -->
            <section class="relative h-96 overflow-hidden bg-[#E0E0E0]">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <MapPin class="mx-auto mb-4 h-16 w-16 text-[#C09930]" />
                        <p class="font-semibold text-[#003366]">
                            Interactive Map
                        </p>
                        <p class="text-sm text-[#666666]">
                            Visit us at our headquarters
                        </p>
                    </div>
                </div>
            </section>

            <!-- CTA Section  -->
            <section class="bg-[#003366] px-6 py-20 text-white">
                <div class="mx-auto max-w-4xl text-center">
                    <h2 class="mb-6 font-serif text-4xl font-bold md:text-5xl">
                        Ready to Transform Your Hotel?
                    </h2>
                    <p class="mb-8 text-xl leading-relaxed text-gray-300">
                        Join hundreds of hotels worldwide that trust AnchorMark
                        for their hospitality needs.
                    </p>
                    <Link
                        href="/products"
                        class="rounded-full bg-[#C09930] px-8 py-4 text-lg font-semibold text-white transition-all hover:scale-105 hover:bg-[#267347]"
                    >
                        Order Now
                    </Link>
                </div>
            </section>
        </PublicLayout>
    </div>
</template>

<script setup lang="ts">
import PublicLayout from '@/layouts/PublicLayout.vue';
import {
    Building2,
    Clock,
    Facebook,
    Instagram,
    Linkedin,
    Mail,
    MapPin,
    Phone,
    Twitter,
} from 'lucide-vue-next';
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

interface FormData {
    name: string;
    email: string;
    phone: string;
    company: string;
    subject: string;
    message: string;
}

const formData = ref<FormData>({
    name: '',
    email: '',
    phone: '',
    company: '',
    subject: '',
    message: '',
});

const locations = [
    {
        city: 'Nairobi',
        address: 'Mombasa Road',
        phone: '+254 723 631 790',
    },
];

const handleSubmit = () => {
    console.log('[v0] Form submitted:', formData.value);
    alert('Thank you for your message! We will get back to you soon.');
    // Reset form
    formData.value = {
        name: '',
        email: '',
        phone: '',
        company: '',
        subject: '',
        message: '',
    };
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Inter:wght@400;500;600;700&display=swap');

.font-serif {
    font-family: 'Playfair Display', serif;
}

.font-sans {
    font-family: 'Inter', sans-serif;
}
</style>
