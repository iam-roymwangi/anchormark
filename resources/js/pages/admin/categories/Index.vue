<template>
  <AppLayout>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200">
     <!-- Header  -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Categories</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage product categories</p>
          </div>
          <div class="flex items-center gap-3">
             <!-- Dark Mode Toggle  -->
            <button
              @click="toggleDarkMode"
              class="p-2 rounded-lg border border-gray-300 dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
              :title="isDarkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'"
            >
              <Sun v-if="isDarkMode" class="w-5 h-5 text-gray-600 dark:text-gray-300" />
              <Moon v-else class="w-5 h-5 text-gray-600 dark:text-gray-300" />
            </button>
            
             <!-- Add Category Button  -->
            <button
              @click="openAddModal"
              class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
            >
              <Plus class="w-5 h-5" />
              <span class="hidden sm:inline">Add Category</span>
            </button>
          </div>
        </div>
      </div>
    </div>

     <!-- Main Content  -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
       <!-- Search Bar  -->
      <div class="mb-6">
        <div class="relative">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search categories..."
            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          />
        </div>
      </div>

       <!-- Categories Grid/Table  -->
      <div v-if="loading" class="flex justify-center items-center py-12">
        <Loader2 class="w-8 h-8 animate-spin text-blue-600" />
      </div>

      <div v-else-if="filteredCategories.length === 0" class="text-center py-12">
        <FolderOpen class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" />
        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No categories found</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4">
          {{ searchQuery ? 'Try adjusting your search' : 'Get started by creating your first category' }}
        </p>
        <button
          v-if="!searchQuery"
          @click="openAddModal"
          class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
        >
          <Plus class="w-5 h-5" />
          Add Category
        </button>
      </div>
      <div v-else>
        <div class="hidden md:block bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
          <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Slug
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Description
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Products
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Created
                </th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
              <tr
                v-for="category in filteredCategories"
                :key="category.id"
                class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                      <Tag class="w-5 h-5 text-white" />
                    </div>
                    <div class="font-medium text-gray-900 dark:text-white">{{ category.name }}</div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <code class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded text-sm">
                    {{ category.slug }}
                  </code>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-600 dark:text-gray-400 max-w-xs truncate">
                    {{ category.description || '—' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-xs font-medium">
                    {{ category.product_count }} products
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                  {{ formatDate(category.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <div class="flex items-center justify-end gap-2">
                    <button
                      @click="openEditModal(category)"
                      class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors"
                      title="Edit"
                    >
                      <Edit2 class="w-4 h-4" />
                    </button>
                    <button
                      @click="openDeleteModal(category)"
                      class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                      title="Delete"
                    >
                      <Trash2 class="w-4 h-4" />
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="md:hidden space-y-4">
          <div
            v-for="category in filteredCategories"
            :key="category.id"
            class="bg-white dark:bg-gray-800 rounded-lg shadow p-4"
          >
            <div class="flex items-start justify-between mb-3">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                  <Tag class="w-5 h-5 text-white" />
                </div>
                <div>
                  <h3 class="font-medium text-gray-900 dark:text-white">{{ category.name }}</h3>
                  <code class="text-xs text-gray-600 dark:text-gray-400">{{ category.slug }}</code>
                </div>
              </div>
              <div class="flex items-center gap-1">
                <button
                  @click="openEditModal(category)"
                  class="p-2 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-colors"
                >
                  <Edit2 class="w-4 h-4" />
                </button>
                <button
                  @click="openDeleteModal(category)"
                  class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-colors"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </div>
            </div>
            
            <p v-if="category.description" class="text-sm text-gray-600 dark:text-gray-400 mb-3">
              {{ category.description }}
            </p>
            
            <div class="flex items-center justify-between text-sm">
              <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-xs font-medium">
                {{ category.product_count }} products
              </span>
              <span class="text-gray-500 dark:text-gray-400">{{ formatDate(category.created_at) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

     <!-- Add/Edit Modal  -->
    <Teleport to="body">
      <Transition name="modal">
        <div
          v-if="showModal"
          class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
          @click.self="closeModal"
        >
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto">
             <!-- Modal Header  -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ isEditMode ? 'Edit Category' : 'Add New Category' }}
              </h2>
              <button
                @click="closeModal"
                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
              >
                <X class="w-5 h-5 text-gray-500 dark:text-gray-400" />
              </button>
            </div>

             <!-- Modal Body  -->
            <form @submit.prevent="saveCategory" class="p-6 space-y-4">
               Name 
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Category Name <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.name"
                  @input="generateSlug"
                  type="text"
                  required
                  placeholder="e.g., Electronics"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
              </div>

               <!-- Slug  -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Slug <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.slug"
                  type="text"
                  required
                  placeholder="e.g., electronics"
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                  URL-friendly version (auto-generated from name)
                </p>
              </div>

               <!-- Description  -->
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                  Description
                </label>
                <textarea
                  v-model="form.description"
                  rows="3"
                  placeholder="Brief description of this category..."
                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                ></textarea>
              </div>

               <!-- Modal Footer  -->
              <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button
                  type="button"
                  @click="closeModal"
                  class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                >
                  Cancel
                </button>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                >
                  <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                  <Save v-else class="w-4 h-4" />
                  {{ form.processing ? 'Saving...' : (isEditMode ? 'Update' : 'Create') }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </Transition>
    </Teleport>

     <!-- Delete Confirmation Modal  -->
    <Teleport to="body">
      <Transition name="modal">
        <div
          v-if="showDeleteModal"
          class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
          @click.self="closeDeleteModal"
        >
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
             Modal Header 
            <div class="p-6">
              <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center flex-shrink-0">
                  <AlertCircle class="w-6 h-6 text-red-600 dark:text-red-400" />
                </div>
                <div>
                  <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Delete Category</h2>
                  <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">This action cannot be undone</p>
                </div>
              </div>

              <p class="text-gray-700 dark:text-gray-300 mb-2">
                Are you sure you want to delete the category <strong>{{ categoryToDelete!.name }}</strong>?
              </p>
              
              <div v-if="categoryToDelete && categoryToDelete.product_count > 0" class="p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                  <strong>Warning:</strong> This category has {{ categoryToDelete.product_count }} product(s). 
                  Deleting it will also delete all associated products due to cascade delete.
                </p>
              </div>
            </div>

             <!-- Modal Footer  -->
            <div class="flex items-center justify-end gap-3 px-6 pb-6">
              <button
                type="button"
                @click="closeDeleteModal"
                class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
              >
                Cancel
              </button>
              <button
                @click="deleteCategory"
                :disabled="deleting"
                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
              >
                <Loader2 v-if="deleting" class="w-4 h-4 animate-spin" />
                <Trash2 v-else class="w-4 h-4" />
                {{ deleting ? 'Deleting...' : 'Delete' }}
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import {
  Plus,
  Search,
  Edit2,
  Trash2,
  Tag,
  FolderOpen,
  Loader2,
  X,
  Save,
  AlertCircle,
  Moon,
  Sun
} from 'lucide-vue-next'
import AppLayout from '@/layouts/AppLayout.vue'

// Props
const props = defineProps<{
  categories: Category[]
}>()

// Types
interface Category {
  id: number
  name: string
  slug: string
  description: string | null
  product_count: number
  created_at: string
  updated_at: string
}

interface CategoryFormData {
  name: string
  slug: string
  description: string
}

// State
const searchQuery = ref('')
const loading = ref(false) // Keep for search loading, but not initial fetch
const deleting = ref(false)
const showModal = ref(false)
const showDeleteModal = ref(false)
const isEditMode = ref(false)
const editingId = ref<number | null>(null)
const categoryToDelete = ref<Category | null>(null)
const isDarkMode = ref(false)

// Use Inertia's useForm
const form = useForm<CategoryFormData>({
  name: '',
  slug: '',
  description: ''
})

// Computed
const filteredCategories = computed(() => {
  if (!searchQuery.value) return props.categories // Use props.categories

  const query = searchQuery.value.toLowerCase()
  return props.categories.filter( // Use props.categories
    (cat) =>
      cat.name.toLowerCase().includes(query) ||
      cat.slug.toLowerCase().includes(query) ||
      cat.description?.toLowerCase().includes(query)
  )
})

// Methods
const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value
  if (isDarkMode.value) {
    document.documentElement.classList.add('dark')
    localStorage.setItem('darkMode', 'true')
  } else {
    document.documentElement.classList.remove('dark')
    localStorage.setItem('darkMode', 'false')
  }
}

const generateSlug = () => {
  if (!isEditMode.value) {
    form.slug = form.name // Use form.name
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-+|-+$/g, '')
  }
}

const formatDate = (dateString: string) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const openAddModal = () => {
  isEditMode.value = false
  editingId.value = null
  form.reset() // Reset form using Inertia's reset
  showModal.value = true
}

const openEditModal = (category: Category) => {
  isEditMode.value = true
  editingId.value = category.id
  form.name = category.name
  form.slug = category.slug
  form.description = category.description || ''
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  setTimeout(() => {
    isEditMode.value = false
    editingId.value = null
    form.reset() // Reset form
  }, 300)
}

const openDeleteModal = (category: Category) => {
  categoryToDelete.value = category
  showDeleteModal.value = true
}

const closeDeleteModal = () => {
  showDeleteModal.value = false
  setTimeout(() => {
    categoryToDelete.value = null
  }, 300)
}

const saveCategory = async () => {
  if (isEditMode.value && editingId.value) {
    form.put(`/admin/categories/${editingId.value}`, {
      onSuccess: () => {
        closeModal()
      },
      onError: (errors) => {
        console.error('Error updating category:', errors)
        alert('Failed to update category. Please try again.')
      },
    })
  } else {
    form.post('/admin/categories', {
      onSuccess: () => {
        closeModal()
      },
      onError: (errors) => {
        console.error('Error creating category:', errors)
        alert('Failed to create category. Please try again.')
      },
    })
  }
}

const deleteCategory = async () => {
  if (!categoryToDelete.value) return

  deleting.value = true // Set deleting state

  router.delete(`/admin/categories/${categoryToDelete.value.id}`, {
    onSuccess: () => {
      closeDeleteModal()
    },
    onError: (errors) => {
      console.error('Error deleting category:', errors)
      alert('Failed to delete category. Please try again.')
    },
    onFinish: () => {
      deleting.value = false // Reset deleting state
    }
  })
}

// Initial dark mode check (moved outside onMounted as it doesn't depend on fetched data)
const savedDarkMode = localStorage.getItem('darkMode')
if (savedDarkMode === 'true') {
  isDarkMode.value = true
  document.documentElement.classList.add('dark')
}
</script>
