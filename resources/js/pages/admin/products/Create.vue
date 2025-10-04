<template>
    <AppLayout>
  <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-900 dark:to-slate-800 py-4 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
       Header 
      <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white mb-2">Add New Product</h1>
        <p class="text-slate-600 dark:text-slate-400">Fill in the details below to add a new product to your inventory</p>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4 sm:space-y-6">
         Basic Information Card 
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-6">
          <h2 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-white mb-4 sm:mb-6 flex items-center gap-2">
            <Package class="w-5 h-5 text-blue-600 dark:text-blue-400" />
            Basic Information
          </h2>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
             Product Name 
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Product Name <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                placeholder="Enter product name"
                @input="generateSlug"
              />
            </div>

             Slug 
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Slug <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.slug"
                type="text"
                required
                class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                placeholder="product-slug"
              />
            </div>

             Category 
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Category <span class="text-red-500">*</span>
              </label>
              <select
                v-model="form.category_id"
                required
                class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white"
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
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                SKU <span class="text-red-500">*</span>
              </label>
              <input
                v-model="form.sku"
                type="text"
                required
                class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                placeholder="PROD-001"
              />
            </div>

             Price 
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Price <span class="text-red-500">*</span>
              </label>
              <div class="relative">
                <DollarSign class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 sm:w-5 sm:h-5 text-slate-400 dark:text-slate-500" />
                <input
                  v-model.number="form.price"
                  type="number"
                  step="0.01"
                  min="0"
                  required
                  class="w-full pl-8 sm:pl-10 pr-3 py-2 sm:pr-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                  placeholder="0.00"
                />
              </div>
            </div>

             Stock Quantity 
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Stock Quantity
              </label>
              <input
                v-model.number="form.stock_quantity"
                type="number"
                min="0"
                class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                placeholder="0"
              />
            </div>

             Re-order Level 
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Re-order Level
              </label>
              <input
                v-model.number="form.re_order_level"
                type="number"
                min="0"
                class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                placeholder="0"
              />
            </div>

             Shelf Life 
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Shelf Life (days)
              </label>
              <input
                v-model.number="form.shelf_life"
                type="number"
                min="0"
                class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                placeholder="3650"
              />
            </div>

             Status 
            <div>
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Status
              </label>
              <select
                v-model="form.status"
                class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white"
              >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="discontinued">Discontinued</option>
              </select>
            </div>

             Description 
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                Description
              </label>
              <textarea
                v-model="form.description"
                rows="4"
                class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                placeholder="Enter product description..."
              ></textarea>
            </div>
          </div>
        </div>

         Product Images Card 
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-6">
          <h2 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-white mb-4 sm:mb-6 flex items-center gap-2">
            <ImageIcon class="w-5 h-5 text-blue-600 dark:text-blue-400" />
            Product Images
          </h2>

          <div class="space-y-4">
            <div class="flex flex-wrap gap-2 sm:gap-4">
              <div
                v-for="(image, index) in form.compressedImages"
                :key="index"
                class="relative group w-24 h-24 sm:w-32 sm:h-32 border-2 border-slate-200 dark:border-slate-600 rounded-lg overflow-hidden"
              >
                <img :src="image" alt="Product" class="w-full h-full object-cover" />
                <button
                  type="button"
                  @click="removeImage(index)"
                  class="absolute top-1 right-1 sm:top-2 sm:right-2 bg-red-500 text-white p-1 sm:p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                >
                  <X class="w-3 h-3 sm:w-4 sm:h-4" />
                </button>
              </div>

               Add Image Button 
              <label class="w-24 h-24 sm:w-32 sm:h-32 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg flex flex-col items-center justify-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all">
                <Plus class="w-6 h-6 sm:w-8 sm:h-8 text-slate-400 dark:text-slate-500" />
                <span class="text-xs text-slate-500 dark:text-slate-400 mt-1 sm:mt-2 hidden sm:block">Add Image</span>
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
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-6">
          <h2 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-white mb-4 sm:mb-6 flex items-center gap-2">
            <Tag class="w-5 h-5 text-blue-600 dark:text-blue-400" />
            Product Attributes
          </h2>

          <div class="space-y-4">
            <div
              v-for="attribute in availableAttributes"
              :key="attribute.id"
              class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end p-3 sm:p-4 bg-slate-50 dark:bg-slate-700 rounded-lg"
            >
              <div class="md:col-span-1">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                  {{ attribute.name }}
                </label>
              </div>

              <div class="md:col-span-2">
                 String Input 
                <input
                  v-if="attribute.data_type === 'string'"
                  v-model="form.attributes[attribute.id]"
                  type="text"
                  class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                  :placeholder="`Enter ${attribute.name.toLowerCase()}`"
                />
                <input
                  v-else-if="attribute.data_type === 'number'"
                  v-model.number="form.attributes[attribute.id]"
                  type="number"
                  step="0.01"
                  class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                  :placeholder="`Enter ${attribute.name.toLowerCase()}`"
                />
                <label
                  v-else-if="attribute.data_type === 'boolean'"
                  class="flex items-center gap-3 cursor-pointer"
                >
                  <input
                    v-model="form.attributes[attribute.id]"
                    type="checkbox"
                    class="w-5 h-5 text-blue-600 dark:text-blue-400 border-slate-300 dark:border-slate-600 rounded focus:ring-2 focus:ring-blue-500 dark:bg-slate-700"
                  />
                  <span class="text-sm text-slate-700 dark:text-slate-300">Yes</span>
                </label>
                <input
                  v-else-if="attribute.data_type === 'date'"
                  v-model="form.attributes[attribute.id]"
                  type="date"
                  class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-800 text-slate-900 dark:text-white"
                />
              </div>
            </div>

            <p v-if="availableAttributes.length === 0" class="text-slate-500 dark:text-slate-400 text-center py-8">
              Select a category to see available attributes
            </p>
          </div>
        </div>

         Additional Specs (JSON) Card 
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-slate-200 dark:border-slate-700 p-4 sm:p-6">
          <h2 class="text-lg sm:text-xl font-semibold text-slate-900 dark:text-white mb-4 sm:mb-6 flex items-center gap-2">
            <FileText class="w-5 h-5 text-blue-600 dark:text-blue-400" />
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
                  class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                  placeholder="Spec name (e.g., Material)"
                />
              </div>
              <div class="md:col-span-2">
                <input
                  v-model="spec.value"
                  type="text"
                  class="w-full px-3 py-2 sm:px-4 sm:py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white dark:bg-slate-700 text-slate-900 dark:text-white placeholder:text-slate-500 dark:placeholder:text-slate-400"
                  placeholder="Spec value (e.g., Stainless Steel)"
                />
              </div>
              <div>
                <button
                  type="button"
                  @click="removeSpec(index)"
                  class="w-full px-2 py-2 sm:px-4 sm:py-2.5 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors flex items-center justify-center gap-1 sm:gap-2"
                >
                  <Trash2 class="w-3 h-3 sm:w-4 sm:h-4" />
                  <span class="hidden sm:inline">Remove</span>
                </button>
              </div>
            </div>

            <button
              type="button"
              @click="addSpec"
              class="w-full px-4 py-2.5 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-lg text-slate-600 dark:text-slate-400 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all flex items-center justify-center gap-2"
            >
              <Plus class="w-5 h-5" />
              Add Specification
            </button>
          </div>
        </div>

         Action Buttons 
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end">
          <button
            type="button"
            class="px-4 sm:px-6 py-2.5 sm:py-3 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors font-medium"
          >
            Cancel
          </button>
          <button
            type="submit"
            :disabled="isSubmitting"
            class="px-4 sm:px-6 py-2.5 sm:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
          >
            <Loader2 v-if="isSubmitting" class="w-4 h-4 sm:w-5 sm:h-5 animate-spin" />
            <Save v-else class="w-4 h-4 sm:w-5 sm:h-5" />
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
import { router } from '@inertiajs/vue3'
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
  images: File[]
  compressedImages: string[]
  attributes: Record<number, any>
  specs: Spec[]
}

interface CompressedImage {
  file: File
  compressedDataUrl: string
  name: string
}

// Props and reactive data
const props = defineProps<{
  categories: Category[]
  attributes: Attribute[]
}>()

const categories = ref<Category[]>(props.categories || [])
const availableAttributes = ref<Attribute[]>(props.attributes || [])

// Image compression utility
const compressImage = (file: File, maxWidth: number = 800, quality: number = 0.8): Promise<string> => {
  return new Promise((resolve, reject) => {
    const canvas = document.createElement('canvas')
    const ctx = canvas.getContext('2d')
    const img = new Image()

    img.onload = () => {
      // Calculate new dimensions
      const { width, height } = img
      const aspectRatio = width / height
      
      let newWidth = width
      let newHeight = height
      
      if (width > maxWidth) {
        newWidth = maxWidth
        newHeight = newWidth / aspectRatio
      }
      
      // Set canvas dimensions
      canvas.width = newWidth
      canvas.height = newHeight
      
      // Draw and compress
      ctx!.drawImage(img, 0, 0, newWidth, newHeight)
      const compressedDataUrl = canvas.toDataURL('image/jpeg', quality)
      resolve(compressedDataUrl)
    }

    img.onerror = reject
    img.src = URL.createObjectURL(file)
  })
}

const compressMultipleImages = async (files: File[]): Promise<CompressedImage[]> => {
  const compressedImages: CompressedImage[] = []
  
  for (const file of files) {
    try {
      const compressedDataUrl = await compressImage(file)
      compressedImages.push({
        file,
        compressedDataUrl,
        name: file.name
      })
    } catch (error) {
      console.error(`Failed to compress ${file.name}:`, error)
      // Add uncompressed image as fallback
      const reader = new FileReader()
      reader.onload = (e) => {
        compressedImages.push({
          file,
          compressedDataUrl: e.target?.result as string,
          name: file.name
        })
      }
      reader.readAsDataURL(file)
    }
  }
  
  return compressedImages
}

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
  compressedImages: [],
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
  // Load attributes from props - they should be filtered by category on the backend
  availableAttributes.value = props.attributes || []
}

const handleImageUpload = async (event: Event) => {
  const target = event.target as HTMLInputElement
  const files = target.files
  
  if (files && files.length > 0) {
    try {
      const compressedImages = await compressMultipleImages(Array.from(files))
      
      // Store original files and compressed data URLs
      compressedImages.forEach(compressed => {
        form.images.push(compressed.file)
        form.compressedImages.push(compressed.compressedDataUrl)
      })
      
    } catch (error) {
      console.error('Error processing images:', error)
      alert('Error processing images. Please try again.')
    }
    
    // Clear the file input
    target.value = ''
  }
}

const removeImage = (index: number) => {
  form.images.splice(index, 1)
  form.compressedImages.splice(index, 1)
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
    router.visit('/admin/products') // Redirect to product index page
    
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

.dark textarea::-webkit-scrollbar-track {
  background: #334155;
}

textarea::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.dark textarea::-webkit-scrollbar-thumb {
  background: #64748b;
}

textarea::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.dark textarea::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
