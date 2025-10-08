<template>
    <div>
        <!-- Mobile Toggle Button  -->
        <button
            @click="$emit('toggle')"
            class="fixed top-24 left-4 z-40 rounded-full bg-[#003366] p-3 text-white shadow-lg transition-colors hover:bg-[#2E8B57] lg:hidden"
        >
            <Menu :size="24" />
        </button>

        <!-- Sidebar Overlay (Mobile)  -->
        <Transition name="overlay">
            <div
                v-if="isOpen"
                @click="$emit('toggle')"
                class="bg-opacity-50 fixed inset-0 z-40 bg-transparent lg:hidden"
            />
        </Transition>

        <!-- Sidebar  -->
        <Transition name="slide">
            <aside
                v-if="isOpen || !isMobile"
                class="fixed top-16 left-0 z-50 h-[calc(100vh-4rem)] w-80 overflow-y-auto bg-[#003366] text-white shadow-2xl lg:sticky lg:z-0 lg:shadow-none"
            >
                <!-- Search Bar  -->
                <div class="border-b border-white/10 p-4">
                    <div class="relative">
                        <Search
                            class="absolute top-1/2 left-3 -translate-y-1/2 transform text-white/50"
                            :size="18"
                        />
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search products..."
                            class="w-full rounded-lg bg-white/10 py-2 pr-4 pl-10 text-white placeholder-white/50 transition-all focus:ring-2 focus:ring-[#2E8B57] focus:outline-none"
                        />
                    </div>
                </div>

                <!-- Categories  -->
                <nav class="p-4">
                    <div class="space-y-2">
                        <!-- All Products  -->
                        <button
                            @click="$emit('select-category', 'all')"
                            :class="[
                                'group flex w-full items-center justify-between rounded-lg px-4 py-3 text-left transition-all duration-200',
                                selectedCategory === 'all'
                                    ? 'bg-[#2E8B57] text-white'
                                    : 'text-white/90 hover:bg-white/10',
                            ]"
                        >
                            <span class="font-medium">All Products</span>
                            <span
                                class="rounded-full bg-white/20 px-2 py-1 text-xs"
                            >
                                {{ getTotalProductCount() }}
                            </span>
                        </button>

                        <!-- Category Sections  -->
                        <div
                            v-for="category in categories"
                            :key="category.id"
                            class="space-y-1"
                        >
                            <!-- Category Header  -->
                            <button
                                @click="toggleCategory(category.id)"
                                :class="[
                                    'group flex w-full items-center justify-between rounded-lg px-4 py-3 text-left transition-all duration-200',
                                    selectedCategory === category.id
                                        ? 'bg-[#2E8B57] text-white'
                                        : 'text-white/90 hover:bg-white/10',
                                ]"
                            >
                                <div class="flex items-center gap-3">
                                    <component :is="category.icon" :size="18" />
                                    <span class="font-medium">{{
                                        category.name
                                    }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="rounded-full bg-white/20 px-2 py-1 text-xs"
                                    >
                                        {{ category.products.length }}
                                    </span>
                                    <ChevronDown
                                        :size="16"
                                        :class="[
                                            'transition-transform duration-200',
                                            expandedCategories.includes(
                                                category.id,
                                            )
                                                ? 'rotate-180'
                                                : '',
                                        ]"
                                    />
                                </div>
                            </button>

                            <!-- Product List (Expandable)  -->
                            <Transition name="expand">
                                <div
                                    v-if="
                                        expandedCategories.includes(category.id)
                                    "
                                    class="ml-4 space-y-1 overflow-hidden"
                                >
                                    <button
                                        v-for="product in category.products"
                                        :key="product.id"
                                        @click="
                                            $emit('select-product', product)
                                        "
                                        class="group flex w-full items-center justify-between rounded-lg px-4 py-2 text-left text-sm text-white/80 transition-all duration-200 hover:bg-white/10 hover:text-white"
                                    >
                                        <span class="truncate">{{
                                            product.name
                                        }}</span>
                                        <ArrowRight
                                            :size="14"
                                            class="opacity-0 transition-opacity group-hover:opacity-100"
                                        />
                                    </button>
                                </div>
                            </Transition>
                        </div>
                    </div>
                </nav>

                <!-- Filters Section  -->
                <div class="border-t border-white/10 p-4">
                    <h3 class="mb-3 text-sm font-semibold text-white/70">
                        FILTERS
                    </h3>
                    <div class="space-y-3">
                        <!-- Price Range  -->
                        <div>
                            <label class="mb-2 block text-sm text-white/80"
                                >Price Range</label
                            >
                            <select
                                class="w-full rounded-lg bg-white/10 px-3 py-2 text-sm text-white focus:ring-2 focus:ring-[#2E8B57] focus:outline-none"
                            >
                                <option>All Prices</option>
                                <option>Under $100</option>
                                <option>$100 - $500</option>
                                <option>$500 - $1000</option>
                                <option>Over $1000</option>
                            </select>
                        </div>

                        <!-- Availability  -->
                        <div>
                            <label
                                class="flex cursor-pointer items-center gap-2 text-sm text-white/80"
                            >
                                <input
                                    type="checkbox"
                                    class="rounded border-white/20 bg-white/10 text-[#2E8B57] focus:ring-[#2E8B57]"
                                />
                                <span>In Stock Only</span>
                            </label>
                        </div>

                        <!-- New Arrivals  -->
                        <div>
                            <label
                                class="flex cursor-pointer items-center gap-2 text-sm text-white/80"
                            >
                                <input
                                    type="checkbox"
                                    class="rounded border-white/20 bg-white/10 text-[#2E8B57] focus:ring-[#2E8B57]"
                                />
                                <span>New Arrivals</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Help Section  -->
                <div class="border-t border-white/10 p-4">
                    <div class="rounded-lg bg-white/5 p-4">
                        <h4 class="mb-2 font-semibold text-white">
                            Need Help?
                        </h4>
                        <p class="mb-3 text-sm text-white/70">
                            Contact our team for bulk orders and custom
                            solutions.
                        </p>
                        <button
                            class="flex items-center gap-1 text-sm font-medium text-[#2E8B57] hover:text-[#267347]"
                        >
                            Contact Sales
                            <ArrowRight :size="14" />
                        </button>
                    </div>
                </div>
            </aside>
        </Transition>
    </div>
</template>

<script setup lang="ts">
import { ArrowRight, ChevronDown, Menu, Search } from 'lucide-vue-next';
import { ref } from 'vue';

interface Product {
    id: number;
    name: string;
    description: string;
    price: string;
    category: string;
    image: string;
}

interface Category {
    id: string;
    name: string;
    icon: any;
    products: Product[];
}

interface Props {
    isOpen: boolean;
    isMobile: boolean;
    selectedCategory: string;
    categories: Category[];
}

const props = defineProps<Props>();
defineEmits(['toggle', 'select-category', 'select-product']);

const searchQuery = ref('');
const expandedCategories = ref<string[]>([
    'beddings',
    'kitchenware',
    'furniture',
]);

const toggleCategory = (categoryId: string) => {
    const index = expandedCategories.value.indexOf(categoryId);
    if (index > -1) {
        expandedCategories.value.splice(index, 1);
    } else {
        expandedCategories.value.push(categoryId);
    }
};

const getTotalProductCount = () => {
    return props.categories.reduce(
        (total, cat) => total + cat.products.length,
        0,
    );
};
</script>

<style scoped>
/* Scrollbar Styling */
aside::-webkit-scrollbar {
    width: 6px;
}

aside::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
}

aside::-webkit-scrollbar-thumb {
    background: rgba(46, 139, 87, 0.5);
    border-radius: 3px;
}

aside::-webkit-scrollbar-thumb:hover {
    background: rgba(46, 139, 87, 0.7);
}

/* Transitions */
.slide-enter-active,
.slide-leave-active {
    transition: transform 0.3s ease;
}

.slide-enter-from {
    transform: translateX(-100%);
}

.slide-leave-to {
    transform: translateX(-100%);
}

.overlay-enter-active,
.overlay-leave-active {
    transition: opacity 0.3s ease;
}

.overlay-enter-from,
.overlay-leave-to {
    opacity: 0;
}

.expand-enter-active,
.expand-leave-active {
    transition: all 0.3s ease;
}

.expand-enter-from,
.expand-leave-to {
    opacity: 0;
    max-height: 0;
}

.expand-enter-to,
.expand-leave-from {
    opacity: 1;
    max-height: 500px;
}
</style>
