<template>
    
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100">
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8 flex align-middle justify-between">
                    <div>
                        <h1
                        class="text-3xl font-bold tracking-tight text-balance text-slate-900 sm:text-4xl"
                    >
                        Product Inventory
                    </h1>
                    <p class="mt-2 text-pretty text-slate-600">
                        Manage and view all products in your catalog
                    </p>
                    </div>
                    <div>
                        <Link class="text-white bg-accent p-2 rounded-xl" href="/admin/products/create">
                        Add New Product
                    </Link>
                    </div>
                </div>

                <!-- Filters and Search -->
                <div
                    class="mb-6 flex flex-col gap-4 rounded-xl bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between"
                >
                    <div class="flex flex-1 gap-3">
                        <!-- Search -->
                        <div class="relative flex-1">
                            <Search
                                class="absolute top-1/2 left-3 h-5 w-5 -translate-y-1/2 text-slate-400"
                            />
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Search products..."
                                class="w-full rounded-lg border border-slate-200 py-2 pr-4 pl-10 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                            />
                        </div>

                        <!-- Category Filter -->
                        <select
                            v-model="selectedCategory"
                            class="rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                        >
                            <option value="">All Categories</option>
                            <option
                                v-for="category in categories"
                                :key="category.id"
                                :value="category.id"
                            >
                                {{ category.name }}
                            </option>
                        </select>

                        <!-- Status Filter -->
                        <select
                            v-model="selectedStatus"
                            class="rounded-lg border border-slate-200 px-4 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                        >
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="discontinued">Discontinued</option>
                        </select>
                    </div>

                    <!-- View Toggle -->
                    <div
                        class="flex gap-2 rounded-lg border border-slate-200 p-1"
                    >
                        <button
                            @click="viewMode = 'grid'"
                            :class="[
                                'rounded px-3 py-1.5 text-sm font-medium transition-colors',
                                viewMode === 'grid'
                                    ? 'bg-blue-500 text-white'
                                    : 'text-slate-600 hover:bg-slate-100',
                            ]"
                        >
                            <Grid3x3 class="h-4 w-4" />
                        </button>
                        <button
                            @click="viewMode = 'list'"
                            :class="[
                                'rounded px-3 py-1.5 text-sm font-medium transition-colors',
                                viewMode === 'list'
                                    ? 'bg-blue-500 text-white'
                                    : 'text-slate-600 hover:bg-slate-100',
]"
                        >
                            <List class="h-4 w-4" />
                        </button>
                    </div>
                </div>

                <!-- Loading State -->
                <div
                    v-if="loading"
                    class="flex items-center justify-center py-12"
                >
                    <div
                        class="h-8 w-8 animate-spin rounded-full border-4 border-slate-200 border-t-blue-500"
                    ></div>
                </div>

                <!-- Products Grid View -->
                <div
                    v-else-if="viewMode === 'grid'"
                    class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3"
                >
                    <div
                        v-for="product in filteredProducts"
                        :key="product.id"
                        class="group overflow-hidden rounded-xl bg-white shadow-sm transition-shadow hover:shadow-md"
                    >
                        <!-- Image Carousel -->
                        <div
                            class="relative aspect-square overflow-hidden bg-slate-100"
                        >
                            <img
                                v-if="product.images.length > 0"
                                :src="
                                    product.images[
                                        currentImageIndex[product.id] || 0
                                    ]?.image_url
                                "
                                :alt="product.name"
                                class="h-full w-full object-cover transition-transform group-hover:scale-105"
                            />
                            <div
                                v-else
                                class="flex h-full items-center justify-center"
                            >
                                <ImageIcon class="h-16 w-16 text-slate-300" />
                            </div>

                            <!-- Image Navigation -->
                            <div
                                v-if="product.images.length > 1"
                                class="absolute bottom-2 left-1/2 flex -translate-x-1/2 gap-1"
                            >
                                <button
                                    v-for="(img, idx) in product.images"
                                    :key="idx"
                                    @click="currentImageIndex[product.id] = idx"
                                    :class="[
                                        'h-2 w-2 rounded-full transition-all',
                                        (currentImageIndex[product.id] || 0) ===
                                        idx
                                            ? 'w-4 bg-white'
                                            : 'bg-white/50 hover:bg-white/75',
                                    ]"
                                />
                            </div>

                            <!-- Status Badge -->
                            <div class="absolute top-2 right-2">
                                <span
                                    :class="[
                                        'rounded-full px-2.5 py-1 text-xs font-medium',
                                        product.status === 'active'
                                            ? 'bg-green-100 text-green-700'
                                            : product.status === 'inactive'
                                              ? 'bg-yellow-100 text-yellow-700'
                                              : 'bg-red-100 text-red-700',
                                    ]"
                                >
                                    {{ product.status }}
                                </span>
                            </div>
                        </div>

                        <!-- Product Details -->
                        <div class="p-4">
                            <h3
                                class="text-lg font-semibold text-balance text-slate-900"
                            >
                                {{ product.name }}
                            </h3>
                            <p
                                class="mt-1 line-clamp-2 text-sm text-pretty text-slate-600"
                            >
                                {{
                                    product.description ||
                                    'No description available'
                                }}
                            </p>

                            <!-- Price and Stock -->
                            <div class="mt-3 flex items-center justify-between">
                                <div>
                                    <p class="text-2xl font-bold text-blue-600">
                                        ${{ product.price }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        SKU: {{ product.sku }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p
                                        :class="[
                                            'text-sm font-medium',
                                            product.stock_quantity >
                                            product.re_order_level
                                                ? 'text-green-600'
                                                : 'text-red-600',
                                        ]"
                                    >
                                        {{ product.stock_quantity }} in stock
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        reorder: {{ product.re_order_level }}
                                    </p>
                                </div>
                            </div>

                            <!-- Attributes -->
                            <div
                                v-if="product.attributes.length > 0"
                                class="mt-3 flex flex-wrap gap-1.5"
                            >
                                <span
                                    v-for="attr in product.attributes.slice(
                                        0,
                                        3,
                                    )"
                                    :key="attr.id"
                                    class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-700"
                                >
                                    <Tag class="h-3 w-3" />
                                    {{ attr.name }}:
                                    {{ formatAttributeValue(attr) }}
                                </span>
                                <span
                                    v-if="product.attributes.length > 3"
                                    class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-700"
                                >
                                    +{{ product.attributes.length - 3 }} more
                                </span>
                            </div>

                            <!-- Actions -->
                            <div class="mt-4 flex gap-2">
                                <button
                                    @click="viewProduct(product)"
                                    class="flex flex-1 items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-600"
                                >
                                    <Eye class="h-4 w-4" />
                                    View
                                </button>
                                <button
                                    @click="editProduct(product)"
                                    class="flex items-center justify-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50"
                                >
                                    <Edit class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products List View -->
                <div v-else class="space-y-4">
                    <div
                        v-for="product in filteredProducts"
                        :key="product.id"
                        class="overflow-hidden rounded-xl bg-white shadow-sm transition-shadow hover:shadow-md"
                    >
                        <div class="flex flex-col gap-4 p-4 sm:flex-row">
                            <!-- Image -->
                            <div
                                class="relative h-32 w-full shrink-0 overflow-hidden rounded-lg bg-slate-100 sm:h-40 sm:w-40"
                            >
                                <img
                                    v-if="product.images.length > 0"
                                    :src="product.images[0]?.image_url"
                                    :alt="product.name"
                                    class="h-full w-full object-cover"
                                />
                                <div
                                    v-else
                                    class="flex h-full items-center justify-center"
                                >
                                    <ImageIcon
                                        class="h-12 w-12 text-slate-300"
                                    />
                                </div>
                                <span
                                    :class="[
                                        'absolute top-2 right-2 rounded-full px-2.5 py-1 text-xs font-medium',
                                        product.status === 'active'
                                            ? 'bg-green-100 text-green-700'
                                            : product.status === 'inactive'
                                              ? 'bg-yellow-100 text-yellow-700'
                                              : 'bg-red-100 text-red-700',
                                    ]"
                                >
                                    {{ product.status }}
                                </span>
                            </div>

                            <!-- Details -->
                            <div class="flex flex-1 flex-col">
                                <div
                                    class="flex flex-1 flex-col gap-3 sm:flex-row sm:justify-between"
                                >
                                    <div class="flex-1">
                                        <h3
                                            class="text-xl font-semibold text-balance text-slate-900"
                                        >
                                            {{ product.name }}
                                        </h3>
                                        <p
                                            class="mt-1 line-clamp-2 text-sm text-pretty text-slate-600"
                                        >
                                            {{
                                                product.description ||
                                                'No description available'
                                            }}
                                        </p>

                                        <!-- Attributes -->
                                        <div
                                            v-if="product.attributes.length > 0"
                                            class="mt-2 flex flex-wrap gap-1.5"
                                        >
                                            <span
                                                v-for="attr in product.attributes"
                                                :key="attr.id"
                                                class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs text-slate-700"
                                            >
                                                <Tag class="h-3 w-3" />
                                                {{ attr.name }}:
                                                {{ formatAttributeValue(attr) }}
                                            </span>
                                        </div>

                                        <!-- Additional Specs -->
                                        <div
                                            v-if="product.specs_json"
                                            class="mt-2"
                                        >
                                            <p
                                                class="text-xs font-medium text-slate-500"
                                            >
                                                Additional Specs:
                                            </p>
                                            <div
                                                class="mt-1 flex flex-wrap gap-1.5"
                                            >
                                                <span
                                                    v-for="(
                                                        value, key
                                                    ) in product.specs_json"
                                                    :key="key"
                                                    class="rounded bg-slate-50 px-2 py-0.5 text-xs text-slate-600"
                                                >
                                                    {{ key }}: {{ value }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Price and Stock Info -->
                                    <div
                                        class="flex shrink-0 flex-row gap-4 sm:flex-col sm:items-end sm:gap-2"
                                    >
                                        <div>
                                            <p
                                                class="text-2xl font-bold text-blue-600"
                                            >
                                                ${{ product.price }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                SKU:  {{ product.sku }}
                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p
                                                :class="[
                                                    'text-sm font-medium',
                                                    product.stock_quantity >
                                                    product.re_order_level
                                                        ? 'text-green-600'
                                                        : 'text-red-600',
                                                ]"
                                            >
                                                {{ product.stock_quantity }} in
                                                stock
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                Reorder:
                                                {{ product.re_order_level }}
                                            </p>
                                            <p
                                                class="mt-1 text-xs text-slate-500"
                                            >
                                                <Package
                                                    class="inline h-3 w-3"
                                                />
                                                Shelf life:
                                                {{ product.shelf_life }} days
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="mt-3 flex gap-2">
                                    <button
                                        @click="viewProduct(product)"
                                        class="flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-blue-600"
                                    >
                                        <Eye class="h-4 w-4" />
                                        View Details
                                    </button>
                                    <button
                                        @click="editProduct(product)"
                                        class="flex items-center justify-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50"
                                    >
                                        <Edit class="h-4 w-4" />
                                        Edit
                                    </button>
                                    <button
                                        @click="deleteProduct(product)"
                                        class="flex items-center justify-center gap-2 rounded-lg border border-red-200 px-4 py-2 text-sm font-medium text-red-600 transition-colors hover:bg-red-50"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="!loading && filteredProducts.length === 0"
                    class="flex flex-col items-center justify-center rounded-xl bg-white py-12 shadow-sm"
                >
                    <Package class="h-16 w-16 text-slate-300" />
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">
                        No products found
                    </h3>
                    <p class="mt-1 text-sm text-slate-600">
                        Try adjusting your filters or add a new product
                    </p>
                </div>
            </div>
        </div>

        <!-- View Modal -->
        <ProductViewModal
            :isOpen="viewModalOpen"
            :productId="selectedProductId"
            @close="closeModals"
            @edit="editProduct"
        />

        <!-- Edit Modal -->
        <ProductEditModal
            :isOpen="editModalOpen"
            :product="selectedProduct"
            :categories="categories"
            :attributes="[]"
            @close="closeModals"
            @save="saveProduct"
        />
   
</template>

<script setup lang="ts">
// import AppLayout from '@/layouts/AppLayout.vue';
import {
    Edit,
    Eye,
    Grid3x3,
    ImageIcon,
    List,
    Package,
    Search,
    Tag,
    Trash2,
} from 'lucide-vue-next';
import { computed, onMounted, reactive, ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import ProductViewModal from '@/components/modals/ProductViewModal.vue';
import ProductEditModal from '@/components/modals/ProductEditModal.vue';

// Types
interface ProductImage {
    id: number;
    product_id: number;
    image_url: string;
}

interface ProductAttribute {
    id: number;
    product_id: number;
    attribute_id: number;
    name: string;
    data_type: 'string' | 'number' | 'boolean' | 'date';
    value_string: string | null;
    value_number: number | null;
    value_boolean: boolean | null;
    value_date: string | null;
}

interface Product {
    id: number;
    category_id: number;
    category_name: string;
    name: string;
    slug: string;
    description: string | null;
    price: number;
    stock_quantity: number;
    re_order_level: number;
    shelf_life: number;
    sku: string;
    specs_json: Record<string, any> | null;
    status: 'active' | 'inactive' | 'discontinued';
    images: ProductImage[];
    attributes: ProductAttribute[];
    created_at: string;
    updated_at: string;
}

interface Category {
    id: number;
    name: string;
}

// Props
const props = defineProps<{
    products: Product[];
    categories: Category[];
}>();

// State
const loading = ref(false);
const products = ref<Product[]>(props.products);
const categories = ref<Category[]>(props.categories);
const searchQuery = ref('');
const selectedCategory = ref('');
const selectedStatus = ref('');
const viewMode = ref<'grid' | 'list'>('grid');
const currentImageIndex = reactive<Record<number, number>>({});

// Modal states
const viewModalOpen = ref(false);
const editModalOpen = ref(false);
const selectedProductId = ref<number | null>(null);
const selectedProduct = ref<Product | null>(null);

// Computed
const filteredProducts = computed(() => {
    return products.value.filter((product) => {
        const matchesSearch =
            searchQuery.value === '' ||
            product.name
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            product.sku
                .toLowerCase()
                .includes(searchQuery.value.toLowerCase()) ||
            product.description
                ?.toLowerCase()
                .includes(searchQuery.value.toLowerCase());

        const matchesCategory =
            selectedCategory.value === '' ||
            product.category_id === parseInt(selectedCategory.value);

        const matchesStatus =
            selectedStatus.value === '' ||
            product.status === selectedStatus.value;

        return matchesSearch && matchesCategory && matchesStatus;
    });
});

// Methods
const formatAttributeValue = (attr: ProductAttribute): string => {
    switch (attr.data_type) {
        case 'string':
            return attr.value_string || '';
        case 'number':
            return attr.value_number?.toString() || '';
        case 'boolean':
            return attr.value_boolean ? 'Yes' : 'No';
        case 'date':
            return attr.value_date
                ? new Date(attr.value_date).toLocaleDateString()
                : '';
        default:
            return '';
    }
};

const viewProduct = (product: Product) => {
    selectedProductId.value = product.id;
    viewModalOpen.value = true;
};

const editProduct = (product?: Product) => {
    if (product) {
        selectedProduct.value = product;
    }
    editModalOpen.value = true;
};

const deleteProduct = (product: Product) => {
    if (confirm(`Are you sure you want to delete "${product.name}"?`)) {
        router.delete(`/admin/products/${product.id}`, {
            onSuccess: () => {
                // Remove product from local list
                const index = products.value.findIndex(p => p.id === product.id);
                if (index > -1) {
                    products.value.splice(index, 1);
                }
            }
        });
    }
};

const closeModals = () => {
    viewModalOpen.value = false;
    editModalOpen.value = false;
    selectedProductId.value = null;
    selectedProduct.value = null;
};

const saveProduct =        (productData: any) => {
    if (selectedProduct.value?.id) {
        // Update existing product
        router.put(`/admin/products/${selectedProduct.value.id}`, productData, {
            onSuccess: () => {
                // Refresh the products list from server
                router.reload({ only: ['products'] });
                closeModals();
            }
        });
    } else {
        // Create new product
        router.post('/admin/products', productData, {
            onSuccess: () => {
                // Refresh the products list from server
                router.reload({ only: ['products'] });
                closeModals();
            }
        });
    }
};

// Watch for prop changes
watch(() => props.products, (newProducts) => {
    products.value = newProducts;
}, { immediate: true });

watch(() => props.categories, (newCategories) => {
    categories.value = newCategories;
}, { immediate: true });

// Data is loaded from server via props, no mock data needed
// Lifecycle
onMounted(() => {
    // Data is already loaded from server via props
});
</script>