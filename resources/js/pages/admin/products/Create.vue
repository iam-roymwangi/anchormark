<template>
    <AppLayout>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
       Header 
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 mb-2">Add New Product</h1>
        <p class="text-slate-600">Fill in the details below to add a new product to your inventory</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-6">
         Basic Information Card 
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
          <h2 class="text-xl font-semibold text-slate-900 mb-6 flex items-center gap-2">
            <Package class="w-5 h-5 text-blue-600" />
            Basic Information
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
             Product Name 
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Product Name <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                placeholder="Enter product name"
                @input="generateSlug"
              />
            </div>

             Slug 
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Slug <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.slug"
                type="text"
                required
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                placeholder="product-slug"
              />
            </div>

             Category 
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Category <span class="text-red-500">*</span>
              </label>
              <select
                v-model="form.category_id"
                required
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                @change="loadCategoryAttributes"
              >
                <option value="">Select a category</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>

             SKU 
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">
                SKU <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.sku"
                type="text"
                required
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                placeholder="PROD-001"
              />
            </div>

             Price 
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Price <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <DollarSign class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" />
                <input
                  v-model.number="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full pl-10 pr-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  placeholder="0.00"
                />
              </div>
            </div>

             Stock Quantity 
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Stock Quantity
              </label>
              <input
                v-model.number="form.stock_quantity"
                type="number"
                min="0"
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                placeholder="0"
              />
            </div>

             Re-order Level 
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Re-order Level
              </label>
              <input
                v-model.number="form.re_order_level"
                type="number"
                min="0"
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                placeholder="0"
              />
            </div>

             Shelf Life 
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Shelf Life (days)
              </label>
              <input
                v-model.number="form.shelf_life"
                type="number"
                min="0"
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                placeholder="3650"
              />
            </div>

             Status 
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Status
              </label>
              <select
                v-model="form.status"
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="discontinued">Discontinued</option>
              </select>
            </div>

             Description 
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-700 mb-2">
                Description
              </label>
              <textarea
                v-model="form.description"
                rows="4"
                class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                placeholder="Enter product description..."
              ></textarea>
            </div>
          </div>
        </div>

         Product Images Card 
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
          <h2 class="text-xl font-semibold text-slate-900 mb-6 flex items-center gap-2">
            <ImageIcon class="w-5 h-5 text-blue-600" />
            Product Images
          </h2>

          <div class="space-y-4">
            <div class="flex flex-wrap gap-4">
              <div
                v-for="(image, index) in form.images"
                :key="index"
                class="relative group w-32 h-32 border-2 border-slate-200 rounded-lg overflow-hidden"
              >
                <img :src="image" alt="Product" class="w-full h-full object-cover" />
                <button
                  type="button"
                  @click="removeImage(index)"
                  class="absolute top-2 right-2 bg-red-500 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <X class="w-4 h-4" />
                </button>
              </div>

               Add Image Button 
              <label class="w-32 h-32 border-2 border-dashed border-slate-300 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition-all">
                <Plus class="w-8 h-8 text-slate-400" />
                <span class="text-xs text-slate-500 mt-2">Add Image</span>
                <input
                  type="file"
                  accept="image/*"
                  multiple
                  class="hidden"
                  @change="handleImageUpload"
                />
              </label>
            </div>
          </div>
        </div>

         Attributes Card 
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
          <h2 class="text-xl font-semibold text-slate-900 mb-6 flex items-center gap-2">
            <Tag class="w-5 h-5 text-blue-600" />
            Product Attributes
          </h2>

          <div class="space-y-4">
            <div
              v-for="attribute in availableAttributes"
              :key="attribute.id"
              class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end p-4 bg-slate-50 rounded-lg"
            >
              <div class="md:col-span-1">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                  {{ attribute.name }}
                </label>
              </div>

              <div class="md:col-span-2">
                 String Input 
                <input
                  v-if="attribute.data_type === 'string'"
                  v-model="form.attributes[attribute.id]"
                  type="text"
                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  :placeholder="`Enter ${attribute.name.toLowerCase()}`"
                />
                <input
                  v-else-if="attribute.data_type === 'number'"
                  v-model.number="form.attributes[attribute.id]"
                  type="number"
                  step="0.01"
                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  :placeholder="`Enter ${attribute.name.toLowerCase()}`"
                />
                <label
                  v-else-if="attribute.data_type === 'boolean'"
                  class="flex items-center gap-3 cursor-pointer"
                >
                  <input
                    v-model="form.attributes[attribute.id]"
                    type="checkbox"
                    class="w-5 h-5 text-blue-600 border-slate-300 rounded focus:ring-2 focus:ring-blue-500"
                  />
                  <span class="text-sm text-slate-700">Yes</span>
                </label>
                <input
                  v-else-if="attribute.data_type === 'date'"
                  v-model="form.attributes[attribute.id]"
                  type="date"
                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                />
              </div>
            </div>

            <p v-if="availableAttributes.length === 0" class="text-slate-500 text-center py-8">
              Select a category to see available attributes
            </p>
          </div>
        </div>

         Additional Specs (JSON) Card 
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
          <h2 class="text-xl font-semibold text-slate-900 mb-6 flex items-center gap-2">
            <FileText class="w-5 h-5 text-blue-600" />
            Additional Specifications
          </h2>

          <div class="space-y-4">
            <div
              v-for="(spec, index) in form.specs"
              :key="index"
              class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end"
            >
              <div class="md:col-span-2">
                <input
                  v-model="spec.key"
                  type="text"
                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  placeholder="Spec name (e.g., Material)"
                />
              </div>
              <div class="md:col-span-2">
                <input
                  v-model="spec.value"
                  type="text"
                  class="w-full px-4 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                  placeholder="Spec value (e.g., Stainless Steel)"
                />
              </div>
              <div>
                <button
                  type="button"
                  @click="removeSpec(index)"
                  class="w-full px-4 py-2.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors flex items-center justify-center gap-2"
                >
                  <Trash2 class="w-4 h-4" />
                  Remove
                </button>
              </div>
            </div>

            <button
              type="button"
              @click="addSpec"
              class="w-full px-4 py-2.5 border-2 border-dashed border-slate-300 rounded-lg text-slate-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 transition-all flex items-center justify-center gap-2"
            >
              <Plus class="w-5 h-5" />
              Add Specification
            </button>
          </div>
        </div>

         Action Buttons 
        <div class="flex flex-col sm:flex-row gap-4 justify-end">
          <button
            type="button"
            class="px-6 py-3 border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition-colors font-medium"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="isSubmitting"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <Loader2 v-if="isSubmitting" class="w-5 h-5 animate-spin" />
            <Save v-else class="w-5 h-5" />
            {{ isSubmitting ? 'Saving...' : 'Save Product' }}
          </button>
        </div>
      </form>
    </div>
  </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue'
import { Package, ImageIcon, Tag, FileText, DollarSign, Plus, X, Trash2, Save, Loader2 } from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'

// Types
interface Category {
  id: number
  name: string
}

interface Attribute {
  id: number
  name: string
  data_type: 'string' | 'number' | 'boolean' | 'date'
}

interface Spec {
  key: string
  value: string
}

interface ProductForm {
  name: string
  slug: string
  category_id: string
  description: string
  price: number | null
  stock_quantity: number
  re_order_level: number
  shelf_life: number
  sku: string
  status: 'active' | 'inactive' | 'discontinued'
  images: string[]
  attributes: Record<number, any>
  specs: Spec[]
}

// Mock data - Replace with actual API calls
const categories = ref<Category[]>([
  { id: 1, name: 'Electronics' },
  { id: 2, name: 'Clothing' },
  { id: 3, name: 'Home & Garden' },
  { id: 4, name: 'Sports & Outdoors' }
])

const availableAttributes = ref<Attribute[]>([])

// Form state
const form = reactive<ProductForm>({
  name: '',
  slug: '',
  category_id: '',
  description: '',
  price: null,
  stock_quantity: 0,
  re_order_level: 0,
  shelf_life: 3650,
  sku: '',
  status: 'active',
  images: [],
  attributes: {},
  specs: []
})

const isSubmitting = ref(false)

// Methods
const generateSlug = () => {
  form.slug = form.name
    .toLowerCase()
    .replace(/[^a-z0-9]+/g, '-')
    .replace(/^-+|-+$/g, '')
}

const loadCategoryAttributes = () => {
  // Mock attributes - Replace with actual API call based on category
  availableAttributes.value = [
    { id: 1, name: 'Color', data_type: 'string' },
    { id: 2, name: 'Size', data_type: 'string' },
    { id: 3, name: 'Weight (kg)', data_type: 'number' },
    { id: 4, name: 'Brand', data_type: 'string' },
    { id: 5, name: 'Warranty (months)', data_type: 'number' },
    { id: 6, name: 'In Stock', data_type: 'boolean' },
    { id: 7, name: 'Release Date', data_type: 'date' }
  ]
}

const handleImageUpload = (event: Event) => {
  const target = event.target as HTMLInputElement
  const files = target.files
  
  if (files) {
    Array.from(files).forEach(file => {
      const reader = new FileReader()
      reader.onload = (e) => {
        if (e.target?.result) {
          form.images.push(e.target.result as string)
        }
      }
      reader.readAsDataURL(file)
    })
  }
}

const removeImage = (index: number) => {
  form.images.splice(index, 1)
}

const addSpec = () => {
  form.specs.push({ key: '', value: '' })
}

const removeSpec = (index: number) => {
  form.specs.splice(index, 1)
}

const handleSubmit = async () => {
  isSubmitting.value = true
  
  try {
    // Prepare specs_json
    const specsJson = form.specs.reduce((acc, spec) => {
      if (spec.key && spec.value) {
        acc[spec.key] = spec.value
      }
      return acc
    }, {} as Record<string, string>)

    // Prepare product_attributes
    const productAttributes = Object.entries(form.attributes)
      .filter(([, value]) => value !== null && value !== undefined && value !== '')
      .map(([attributeId, value]) => {
        const attribute = availableAttributes.value.find(a => a.id === Number(attributeId))
        return {
          attribute_id: Number(attributeId),
          [`value_${attribute?.data_type}`]: value
        }
      })

    const payload = {
      name: form.name,
      slug: form.slug,
      category_id: Number(form.category_id),
      description: form.description,
      price: form.price,
      stock_quantity: form.stock_quantity,
      re_order_level: form.re_order_level,
      shelf_life: form.shelf_life,
      sku: form.sku,
      status: form.status,
      specs_json: specsJson,
      images: form.images,
      attributes: productAttributes
    }

    console.log('Submitting product:', payload)
    
    // Replace with actual API call
    // await fetch('/api/products', {
    //   method: 'POST',
    //   headers: { 'Content-Type': 'application/json' },
    //   body: JSON.stringify(payload)
    // })

    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 1500))
    
    alert('Product added successfully!')
    
    // Reset form or redirect
  } catch (error) {
    console.error('Error submitting product:', error)
    alert('Failed to add product. Please try again.')
  } finally {
    isSubmitting.value = false
  }
}
</script>

<style scoped>
/* Custom scrollbar for better UX */
textarea::-webkit-scrollbar {
  width: 8px;
}

textarea::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

textarea::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
