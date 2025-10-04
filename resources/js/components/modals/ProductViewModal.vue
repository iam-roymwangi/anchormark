<template>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 overflow-y-auto"
        @click.self="closeModal"
    >
        <div class="flex min-h-full items-center justify-center p-4">
            <div
                class="relative w-full max-w-4xl bg-white rounded-xl shadow-lg"
                @click.stop
            >
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b">
                    <h2 class="text-2xl font-bold text-slate-900">
                        {{ product?.name }}
                    </h2>
                    <button
                        @click="closeModal"
                        class="rounded-lg p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600"
                    >
                        <X class="h-6 w-6" />
                    </button>
                </div>

                <!-- Content -->
                <div class="p-6 max-h-96 overflow-y-auto">
                    <div v-if="loading" class="flex items-center justify-center py-12">
                        <div class="h-8 w-8 animate-spin rounded-full border-4 border-slate-200 border-t-blue-500"></div>
                    </div>

                    <div v-else-if="product" class="space-y-6">
                        <!-- Images -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-3">Images</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <div
                                    v-for="image in product.images"
                                    :key="image.id"
                                    class="relative aspect-square overflow-hidden rounded-lg bg-slate-100"
                                >
                                    <img
                                        :src="image.image_url"
                                        :alt="product.name"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                                <div
                                    v-if="product.images.length === 0"
                                    class="col-span-full flex items-center justify-center aspect-square bg-slate-100 rounded-lg"
                                >
                                    <ImageIcon class="h-16 w-16 text-slate-300" />
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-3">Basic Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-slate-700">Name</label>
                                    <p class="mt-1 text-slate-900">{{ product.name }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-700">SKU</label>
                                    <p class="mt-1 text-slate-900">{{ product.sku }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-700">Price</label>
                                    <p class="mt-1 text-slate-900">${{ product.price }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-700">Category</label>
                                    <p class="mt-1 text-slate-900">{{ product.category?.name ?? 'Unknown' }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-700">Status</label>
                                    <span
                                        :class="[
                                            'mt-1 inline-flex rounded-full px-2.5 py-1 text-xs font-medium',
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
                                <div>
                                    <label class="text-sm font-medium text-slate-700">Slug</label>
                                    <p class="mt-1 text-slate-900 font-mono text-sm">{{ product.slug }}</p>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="text-sm font-medium text-slate-700">Description</label>
                                <p class="mt-1 text-slate-900">{{ product.description || 'No description available' }}</p>
                            </div>
                        </div>

                        <!-- Inventory Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-3">Inventory Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-slate-700">Stock Quantity</label>
                                    <p class="mt-1 text-slate-900">{{ product.stock_quantity }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-700">Reorder Level</label>
                                    <p class="mt-1 text-slate-900">{{ product.re_order_level }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-700">Shelf Life</label>
                                    <p class="mt-1 text-slate-900">{{ product.shelf_life }} days</p>
                                </div>
                            </div>
                        </div>

                        <!-- Attributes -->
                        <div v-if="product.productAttributes.length > 0">
                            <h3 class="text-lg font-semibold text-slate-900 mb-3">Attributes</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div
                                    v-for="attr in product.productAttributes"
                                    :key="attr.id"
                                    class="flex flex-col"
                                >
                                    <label class="text-sm font-medium text-slate-700">{{ attr.name }}</label>
                                    <p class="mt-1 text-slate-900">{{ formatAttributeValue(attr) }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Specifications -->
                        <div v-if="product.specs_json && Object.keys(product.specs_json).length > 0">
                            <h3 class="text-lg font-semibold text-slate-900 mb-3">Specifications</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div
                                    v-for="(value, key) in product.specs_json"
                                    :key="key"
                                    class="flex flex-col"
                                >
                                    <label class="text-sm font-medium text-slate-700">{{ key }}</label>
                                    <p class="mt-1 text-slate-900">{{ value }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-3">Dates</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-sm font-medium text-slate-700">Created At</label>
                                    <p class="mt-1 text-slate-900">{{ formatDate(product.created_at) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-slate-700">Updated At</label>
                                    <p class="mt-1 text-slate-900">{{ formatDate(product.updated_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 p-6 border-t bg-slate-50">
                    <button
                        @click="closeModal"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50"
                    >
                        Close
                    </button>
                    <button
                        @click="editProduct"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700"
                    >
                        Edit Product
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { X, ImageIcon } from 'lucide-vue-next';
import { computed, ref, watchEffect } from 'vue';

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
    category: { id: number; name: string };
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
    productAttributes: ProductAttribute[];
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    isOpen: boolean;
    productId: number | null;
}>();

const emit = defineEmits<{
    close: [];
    edit: [product: Product];
}>();

const loading = ref(false);
const product = ref<Product | null>(null);

const formatDate = (dateString: string): string => {
    return new Date(dateString).toLocaleString();
};

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

const closeModal = () => {
    emit('close');
};

const editProduct = () => {
    if (product.value) {
        emit('edit', product.value);
        closeModal();
    }
};

// Fetch product data when modal opens
watchEffect(async () => {
    if (props.isOpen && props.productId) {
        loading.value = true;
        try {
            const response = await fetch(`/admin/products/${props.productId}`);
            if (response.ok) {
                product.value = await response.json();
            }
        } catch (error) {
            console.error('Error fetching product:', error);
        } finally {
            loading.value = false;
        }
    }
});

// Reset product data when modal closes
watchEffect(() => {
    if (!props.isOpen) {
        product.value = null;
    }
});
</script>
