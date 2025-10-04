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
                        {{ productId ? 'Edit Product' : 'Create Product' }}
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
                    <form @submit.prevent="saveProduct" class="space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">Basic Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        Name <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        SKU <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.sku"
                                        type="text"
                                        required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        Slug <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.slug"
                                        type="text"
                                        required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.category_id"
                                        required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    >
                                        <option value="">Select category</option>
                                        <option
                                            v-for="category in categories"
                                            :key="category.id"
                                            :value="category.id"
                                        >
    {{ category.name }}
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        Price <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.price"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        v-model="form.status"
                                        required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    >
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="discontinued">Discontinued</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                                <textarea
                                    v-model="form.description"
                                    rows="3"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                ></textarea>
                            </div>
                        </div>

                        <!-- Inventory Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">Inventory Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        Stock Quantity <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.stock_quantity"
                                        type="number"
                                        min="0"
                                        required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        Reorder Level <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.re_order_level"
                                        type="number"
                                        min="0"
                                        required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">
                                        Shelf Life (days) <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        v-model="form.shelf_life"
                                        type="number"
                                        min="1"
                                        required
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">Images</h3>
                            <div class="space-y-4">
                                <div
                                    v-for="(image, index) in form.images"
                                    :key="index"
                                    class="flex items-center gap-3"
                                >
                                    <input
                                        v-model="form.images[index]"
                                        type="url"
                                        placeholder="Image URL"
                                        class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                    />
                                    <button
                                        type="button"
                                        @click="removeImage(index)"
                                        class="rounded-lg p-2 text-red-600 hover:bg-red-50"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>
                                <button
                                    type="button"
                                    @click="addImage"
                                    class="rounded-lg border-2 border-dashed border-slate-300 px-4 py-2 text-sm text-slate-600 hover:border-blue-500 hover:text-blue-600"
                                >
                                    + Add Image URL
                                </button>
                            </div>
                        </div>

                        <!-- Attributes -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">Attributes</h3>
                            <div class="space-y-4">
                                <div
                                    v-for="(attribute, index) in form.attributes"
                                    :key="index"
                                    class="flex items-start gap-3 rounded-lg border border-slate-200 p-4"
                                >
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Attribute</label>
                                            <select
                                                v-model="attribute.attribute_id"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                            >
                                                <option value="">Select attribute</option>
                                                <option
                                                    v-for="attrib in attributes"
                                                    :key="attrib.id"
                                                    :value="attrib.id"
                                                >
                                                    {{ attrib.name }} ({{ attrib.data_type }})
                                                </option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-slate-700 mb-1">Value</label>
                                            <input
                                                v-if="getAttributeDataType(attribute.attribute_id) === 'string'"
                                                v-model="attribute.value_string"
                                                type="text"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                            />
                                            <input
                                                v-else-if="getAttributeDataType(attribute.attribute_id) === 'number'"
                                                v-model="attribute.value_number"
                                                type="number"
                                                step="0.01"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                            />
                                            <select
                                                v-else-if="getAttributeDataType(attribute.attribute_id) === 'boolean'"
                                                v-model="attribute.value_boolean"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                            >
                                                <option :value="null">Select</option>
                                                <option :value="true">Yes</option>
                                                <option :value="false">No</option>
                                            </select>
                                            <input
                                                v-else-if="getAttributeDataType(attribute.attribute_id) === 'date'"
                                                v-model="attribute.value_date"
                                                type="date"
                                                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                            />
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeAttribute(index)"
                                        class="rounded-lg p-2 text-red-600 hover:bg-red-50"
                                    >
                                        <Trash2 class="h-4 w-4" />
                                    </button>
                                </div>
                                <button
                                    type="button"
                                    @click="addAttribute"
                                    class="rounded-lg border-2 border-dashed border-slate-300 px-4 py-2 text-sm text-slate-600 hover:border-blue-500 hover:text-blue-600"
                                >
                                    + Add Attribute
                                </button>
                            </div>
                        </div>

                        <!-- Specifications JSON -->
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900 mb-4">Additional Specifications</h3>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">JSON Specifications</label>
                                <textarea
                                    v-model="specsJsonString"
                                    rows="6"
                                    placeholder='{"Battery Life": "30 hours", "Color": "Black"}'
                                    class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm font-mono focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 focus:outline-none"
                                ></textarea>
                                <p class="mt-1 text-xs text-slate-500">
                                    Enter valid JSON format for additional product specifications
                                </p>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer -->
                <div class="flex items-center justify-end gap-3 p-6 border-t bg-slate-50">
                    <button
                        @click="closeModal"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 hover:bg-slate-50"
                    >
                        Cancel
                    </button>
                    <button
                        @click="saveProduct"
                        :disabled="loading"
                        class="rounded-lg px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ loading ? 'Saving...' : 'Save Product' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { X, Trash2 } from 'lucide-vue-next';
import { computed, reactive, ref, watch, watchEffect } from 'vue';

interface ProductAttribute {
    attribute_id: number;
    value_string: string | null;
    value_number: number | null;
    value_boolean: boolean | null;
    value_date: string | null;
}

interface Product {
    id?: number;
    category_id: number;
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
    images: string[];
    attributes: ProductAttribute[];
}

const props = defineProps<{
    isOpen: boolean;
    product?: Product | null;
    categories: Array<{ id: number; name: string }>;
    attributes: Array<{ id: number; name: string; data_type: string }>;
}>();

const emit = defineEmits<{
    close: [];
    save: [product: Product];
}>();

const loading = ref(false);

const form = reactive({
    category_id: 0,
    name: '',
    slug: '',
    description: '',
    price: 0,
    stock_quantity: 0,
    re_order_level: 0,
    shelf_life: 365,
    sku: '',
    specs_json: null as Record<string, any> | null,
    status: 'active' as const,
    images: [] as string[],
    attributes: [] as ProductAttribute[],
});

const specsJsonString = ref('');

const closeModal = () => {
    emit('close');
};

const addImage = () => {
    form.images.push('');
};

const removeImage = (index: number) => {
    form.images.splice(index, 1);
};

const addAttribute = () => {
    form.attributes.push({
        attribute_id: 0,
        value_string: null,
        value_number: null,
        value_boolean: null,
        value_date: null,
    });
};

const removeAttribute = (index: number) => {
    form.attributes.splice(index, 1);
};

const getAttributeDataType = (attributeId: number): string => {
    const attr = props.attributes.find(a => a.id === attributeId);
    return attr?.data_type || 'string';
};

const saveProduct = async () => {
    loading.value = true;
    
    try {
        // Parse specs JSON
        form.specs_json = null;
        if (specsJsonString.value.trim()) {
            try {
                form.specs_json = JSON.parse(specsJsonString.value);
            } catch (error) {
                alert('Invalid JSON format in specifications');
                loading.value = false;
                return;
            }
        }

        // Filter out empty images and attributes
        form.images = form.images.filter(img => img.trim());
        form.attributes = form.attributes.filter(attr => attr.attribute_id > 0);

        emit('save', { ...form });
    } catch (error) {
        console.error('Error saving product:', error);
    } finally {
        loading.value = false;
    }
};

// Load product data when modal opens
watchEffect(() => {
    if (props.isOpen && props.product) {
        Object.assign(form, {
            category_id: props.product.category_id,
            name: props.product.name,
            slug: props.product.slug,
            description: props.product.description || '',
            price: props.product.price,
            stock_quantity: props.product.stock_quantity,
            re_order_level: props.product.re_order_level || 0,
            shelf_life: props.product.shelf_life || 365,
            sku: props.product.sku,
            status: props.product.status,
            images: props.product.images || [],
            attributes: props.product.attributes || [],
        });
        
        specsJsonString.value = props.product.specs_json 
            ? JSON.stringify(props.product.specs_json, null, 2)
            : '';
    } else if (props.isOpen && !props.product) {
        // Reset form for new product
        Object.assign(form, {
            category_id: 0,
            name: '',
            slug: '',
            description: '',
            price: 0,
            stock_quantity: 0,
            re_order_level: 0,
            shelf_life: 365,
            sku: '',
            specs_json: null,
            status: 'active' as const,
            images: [],
            attributes: [],
        });
        specsJsonString.value = '';
    }
});

// Reset form when modal closes
watchEffect(() => {
    if (!props.isOpen) {
        Object.assign(form, {
            category_id: 0,
            name: '',
            slug: '',
            description: '',
            price: 0,
            stock_quantity: 0,
            re_order_level: 0,
            shelf_life: 365,
            sku: '',
            specs_json: null,
            status: 'active' as const,
            images: [],
            attributes: [],
        });
        specsJsonString.value = '';
    }
});
</script>
