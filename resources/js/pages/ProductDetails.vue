<template>
  <div class="min-h-screen bg-[#F5F5F0]">
    <PublicLayout>

    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 pt-24">
      <div class="flex items-center space-x-2 text-sm text-[#333333]">
        <a href="/" class="hover:text-[#AE8625] transition-colors">Home</a>
        <template v-if="product && product.category">
          <ChevronRight :size="16" />
          <a :href="`/products?category=${product.category.slug}`" class="hover:text-[#AE8625] transition-colors">{{ product.category.name }}</a>
        </template>
        <ChevronRight :size="16" />
        <span class="text-[#AE8625]">{{ product.name }}</span>
      </div>
    </div>

    <!-- Product Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Image Gallery -->
        <div class="space-y-4">
          <!-- Main Image with Zoom -->
          <div 
            class="relative bg-white rounded-lg overflow-hidden aspect-square cursor-zoom-in"
            @mouseenter="showZoom = true"
            @mouseleave="showZoom = false"
            @mousemove="handleMouseMove"
          >
            <img 
              :src="selectedImage" 
              :alt="product.name"
              class="w-full h-full object-cover transition-transform duration-300"
              :class="{ 'scale-150': showZoom }"
              :style="zoomStyle"
            />
            <div 
              v-if="product.badge"
              class="absolute top-4 left-4 bg-[#AE8625] text-white px-3 py-1 rounded-full text-sm font-medium"
            >
              {{ product.badge }}
            </div>
          </div>

          <!-- Thumbnails -->
          <div class="grid grid-cols-4 gap-4">
            <button
              v-for="(image, index) in product.images"
              :key="index"
              @click="selectImage(image)"
              class="aspect-square bg-white rounded-lg overflow-hidden border-2 transition-all duration-300 hover:border-[#AE8625]"
              :class="selectedImage === image ? 'border-[#AE8625]' : 'border-transparent'"
            >
              <img :src="image" :alt="`Product view ${index + 1}`" class="w-full h-full object-cover" />
            </button>
          </div>
        </div>

        <!-- Product Details -->
        <div class="space-y-6">
          <div>
            <h1 class="text-4xl md:text-5xl font-serif text-[#333333] mb-2">
              {{ product.name }}
            </h1>
            
          </div>

          <!-- Rating -->
          <div class="flex items-center space-x-2">
            <div class="flex">
              <Star 
                v-for="i in 5" 
                :key="i"
                :size="20"
                :class="i <= product.rating ? 'fill-[#AE8625] text-[#AE8625]' : 'text-gray-300'"
              />
            </div>
            <span class="text-[#333333]">{{ product.rating }} ({{ product.reviewCount }} reviews)</span>
          </div>

          <!-- Price -->
          <div class="flex items-baseline space-x-3">
            <span class="text-4xl font-bold text-[#AE8625]">${{ product.price }}</span>
            <span v-if="product.originalPrice" class="text-xl text-gray-400 line-through">
              ${{ product.originalPrice }}
            </span>
          </div>

          <!-- Short Description -->
          <p class="text-[#666666] leading-relaxed">
            {{ product.shortDescription }}
          </p>

          <!-- Options -->
          <div class="space-y-4">
            <!-- Size Selection -->
            <div>
              <label class="block text-sm font-medium text-[#333333] mb-2">Size</label>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="size in product.sizes"
                  :key="size"
                  @click="selectedSize = size"
                  class="px-4 py-2 border-2 rounded-lg transition-all duration-300"
                  :class="selectedSize === size 
                    ? 'border-[#AE8625] bg-[#AE8625] text-white' 
                    : 'border-gray-300 hover:border-[#AE8625] text-black'"
                >
                  {{ size }}
                </button>
              </div>
            </div>

            <!-- Color Selection -->
            <div>
              <label class="block text-sm font-medium text-[#333333] mb-2">Color</label>
              <div class="flex flex-wrap gap-2">
                <button
                  v-for="color in product.colors"
                  :key="color.name"
                  @click="selectedColor = color.name"
                  class="w-10 h-10 rounded-full border-2 transition-all duration-300"
                  :style="{ backgroundColor: color.hex }"
                  :class="selectedColor === color.name ? 'border-[#AE8625] ring-2 ring-[#AE8625] ring-offset-2' : 'border-gray-300'"
                  :title="color.name"
                />
              </div>
            </div>

            <!-- Quantity -->
            <div>
              <label class="block text-sm font-medium text-[#333333] mb-2">Quantity</label>
              <div class="flex items-center space-x-3">
                <button
                  @click="decreaseQuantity"
                  class="w-10 h-10 border-2 border-gray-300 rounded-lg hover:border-[#AE8625] transition-colors text-black"
                >
                  <Minus :size="16" class="mx-auto" />
                </button>
                <input
                  v-model.number="quantity"
                  type="number"
                  min="1"
                  class="w-20 text-center border-2 border-gray-300 rounded-lg py-2 focus:border-[#AE8625] focus:outline-none text-black"
                />
                <button
                  @click="increaseQuantity"
                  class="w-10 h-10 border-2 border-gray-300 rounded-lg hover:border-[#AE8625] transition-colors text-black"
                >
                  <Plus :size="16" class="mx-auto" />
                </button>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="space-y-3">
            <button 
              @click="addToCart"
              :disabled="isAddingToCart"
              class="w-full bg-[#AE8625] text-white py-4 rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2"
            >
              <Loader2 v-if="isAddingToCart" :size="20" class="animate-spin" />
              <span>{{ isAddingToCart ? 'Adding...' : 'Add to Cart' }}</span>
            </button>
            <button 
              @click="openQuoteModal"
              class="w-full border-2 border-[#AE8625] text-[#AE8625] py-4 rounded-lg font-medium hover:bg-[#AE8625] hover:text-white transition-all duration-300"
            >
              Request Quote
            </button>
          </div>

          <!-- Features -->
          <div class="grid grid-cols-2 gap-4 pt-6 border-t border-gray-300">
            <div class="flex items-center space-x-2">
              <Truck :size="20" class="text-[#AE8625]" />
              <span class="text-sm text-[#666666]">Free Shipping</span>
            </div>
            <div class="flex items-center space-x-2">
              <Shield :size="20" class="text-[#AE8625]" />
              <span class="text-sm text-[#666666]">2 Year Warranty</span>
            </div>
            <div class="flex items-center space-x-2">
              <Package :size="20" class="text-[#AE8625]" />
              <span class="text-sm text-[#666666]">Easy Returns</span>
            </div>
            <div class="flex items-center space-x-2">
              <Award :size="20" class="text-[#AE8625]" />
              <span class="text-sm text-[#666666]">Premium Quality</span>
            </div>
          </div>

          <!-- Payment Options -->
          <div class="pt-6 border-t border-gray-300">
            <p class="text-sm text-[#666666] mb-3">We accept:</p>
            <div class="flex items-center space-x-4">
              <CreditCard :size="32" class="text-[#333333]" />
              <span class="text-sm text-[#666666]">Visa, Mastercard, Amex, PayPal</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs Section -->
      <div class="mt-16">
        <div class="border-b border-gray-300">
          <div class="flex space-x-8">
            <button
              v-for="tab in tabs"
              :key="tab"
              @click="activeTab = tab"
              class="pb-4 font-medium transition-all duration-300 relative"
              :class="activeTab === tab ? 'text-[#AE8625]' : 'text-[#666666] hover:text-[#AE8625]'"
            >
              {{ tab }}
              <div
                v-if="activeTab === tab"
                class="absolute bottom-0 left-0 right-0 h-0.5 bg-[#AE8625]"
              />
            </button>
          </div>
        </div>

        <div class="py-8">
          <!-- Description Tab -->
          <div v-if="activeTab === 'Description'" class="prose max-w-none">
            <p class="text-[#666666] leading-relaxed mb-4">
              {{ product.fullDescription }}
            </p>
            <h3 class="text-2xl font-serif text-[#333333] mt-6 mb-4">Key Features</h3>
            <ul class="space-y-2">
              <li v-for="feature in product.features" :key="feature" class="flex items-start space-x-2">
                <Check :size="20" class="text-[#AE8625] mt-1 flex-shrink-0" />
                <span class="text-[#666666]">{{ feature }}</span>
              </li>
            </ul>
          </div>

          <!-- Specifications Tab -->
          <div v-if="activeTab === 'Specifications'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div
              v-for="(value, key) in product.specifications"
              :key="key"
              class="flex justify-between py-3 border-b border-gray-200"
            >
              <span class="font-medium text-[#333333]">{{ key }}</span>
              <span class="text-[#666666]">{{ value }}</span>
            </div>
          </div>

          <!-- Reviews Tab -->
          <div v-if="activeTab === 'Reviews'" class="space-y-8">
            <!-- Review Summary -->
            <div class="bg-white rounded-lg p-6">
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                  <div class="text-5xl font-bold text-[#AE8625] mb-2">{{ product.rating }}</div>
                  <div class="flex justify-center mb-2">
                    <Star 
                      v-for="i in 5" 
                      :key="i"
                      :size="20"
                      :class="i <= product.rating ? 'fill-[#AE8625] text-[#AE8625]' : 'text-gray-300'"
                    />
                  </div>
                  <p class="text-sm text-[#666666]">Based on {{ product.reviewCount }} reviews</p>
                </div>
                <div class="md:col-span-2 space-y-2">
                  <div v-for="star in [5, 4, 3, 2, 1]" :key="star" class="flex items-center space-x-2">
                    <span class="text-sm text-[#666666] w-12">{{ star }} star</span>
                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                      <div 
                        class="bg-[#AE8625] h-2 rounded-full transition-all duration-500"
                        :style="{ width: `${getStarPercentage(star)}%` }"
                      />
                    </div>
                    <span class="text-sm text-[#666666] w-12">{{ getStarCount(star) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Individual Reviews -->
            <div class="space-y-6">
              <div
                v-for="review in product.reviews"
                :key="review.id"
                class="bg-white rounded-lg p-6 transition-all duration-300 hover:shadow-lg"
              >
                <div class="flex items-start justify-between mb-4">
                  <div>
                    <h4 class="font-medium text-[#333333]">{{ review.author }}</h4>
                    <p class="text-sm text-[#666666]">{{ review.date }}</p>
                  </div>
                  <div class="flex">
                    <Star 
                      v-for="i in 5" 
                      :key="i"
                      :size="16"
                      :class="i <= review.rating ? 'fill-[#AE8625] text-[#AE8625]' : 'text-gray-300'"
                    />
                  </div>
                </div>
                <p class="text-[#666666] leading-relaxed">{{ review.comment }}</p>
                <div v-if="review.verified" class="mt-3 flex items-center space-x-1 text-sm text-[#AE8625]">
                  <Check :size="16" />
                  <span>Verified Purchase</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Shipping Tab -->
          <div v-if="activeTab === 'Shipping'" class="space-y-6">
            <div class="bg-white rounded-lg p-6">
              <h3 class="text-xl font-serif text-[#333333] mb-4">Shipping Information</h3>
              <div class="space-y-4 text-[#666666]">
                <p>We offer free standard shipping on all orders over $500.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <h4 class="font-medium text-[#333333] mb-2">Standard Shipping</h4>
                    <p class="text-sm">5-7 business days - Free over $500</p>
                  </div>
                  <div>
                    <h4 class="font-medium text-[#333333] mb-2">Express Shipping</h4>
                    <p class="text-sm">2-3 business days - $49.99</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Similar Products -->
      <div v-if="similarProducts.length > 0" class="mt-16">
        <h2 class="text-3xl md:text-4xl font-serif text-[#333333] mb-8 text-center">
          Similar Products
        </h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div
            v-for="similar in similarProducts"
            :key="similar.id"
            class="group bg-white rounded-lg overflow-hidden transition-all duration-300 hover:shadow-xl"
          >
            <div class="aspect-square overflow-hidden bg-[#F5F5F0] cursor-pointer" @click="goToProductDetails(similar.slug)">
              <img
                :src="similar.image"
                :alt="similar.name"
                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
              />
            </div>
            <div class="p-4">
              <h3 class="font-medium text-[#333333] mb-2 group-hover:text-[#AE8625] transition-colors cursor-pointer" @click="goToProductDetails(similar.slug)">
                {{ similar.name }}
              </h3>
              <div class="flex items-center justify-between mb-3">
                <span class="text-lg font-bold text-[#AE8625]">${{ similar.price }}</span>
                <div class="flex">
                  <Star 
                    v-for="i in 5" 
                    :key="i"
                    :size="14"
                    :class="i <= similar.rating ? 'fill-[#AE8625] text-[#AE8625]' : 'text-gray-300'"
                  />
                </div>
              </div>
              <button
                @click="goToProductDetails(similar.slug)"
                class="w-full px-4 py-2 bg-[#AE8625] text-white rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2"
              >
                <span>View Details</span>
                <ChevronRight :size="16" />
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quote Request Modal - Multi-Step Form -->
    <div
      v-if="showQuoteModal"
      class="fixed inset-0 z-50 overflow-y-auto"
      @click.self="closeQuoteModal"
    >
      <!-- Backdrop -->
      <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

      <!-- Modal Container -->
      <div class="flex min-h-full items-center justify-center p-4">
        <div
          class="relative bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto"
          @click.stop
        >
          <!-- Modal Header -->
          <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between z-10">
            <h2 class="text-2xl md:text-3xl font-serif text-[#333333]">Request a Quote</h2>
            <button
              @click="closeQuoteModal"
              class="text-gray-400 hover:text-[#333333] transition-colors p-2 hover:bg-gray-100 rounded-full"
            >
              <X :size="24" />
            </button>
          </div>

          <!-- Step Progress Indicator -->
          <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
              <!-- Step 1 -->
              <div class="flex items-center flex-1">
                <div class="flex flex-col items-center flex-1">
                  <div
                    class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300"
                    :class="quoteStep >= 1 ? 'bg-[#AE8625] text-white' : 'bg-gray-300 text-gray-600'"
                  >
                    <User v-if="quoteStep === 1" :size="20" />
                    <Check v-else-if="quoteStep > 1" :size="20" />
                    <span v-else class="text-sm font-bold">1</span>
                  </div>
                  <span class="mt-2 text-xs font-medium" :class="quoteStep >= 1 ? 'text-[#AE8625]' : 'text-gray-500'">
                    Customer Details
                  </span>
                </div>
                <div
                  class="h-1 flex-1 mx-2 transition-all duration-300"
                  :class="quoteStep >= 2 ? 'bg-[#AE8625]' : 'bg-gray-300'"
                ></div>
              </div>

              <!-- Step 2 -->
              <div class="flex items-center flex-1">
                <div class="flex flex-col items-center flex-1">
                  <div
                    class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300"
                    :class="quoteStep >= 2 ? 'bg-[#AE8625] text-white' : 'bg-gray-300 text-gray-600'"
                  >
                    <FileText v-if="quoteStep === 2" :size="20" />
                    <Check v-else-if="quoteStep > 2" :size="20" />
                    <span v-else class="text-sm font-bold">2</span>
                  </div>
                  <span class="mt-2 text-xs font-medium" :class="quoteStep >= 2 ? 'text-[#AE8625]' : 'text-gray-500'">
                    Review Details
                  </span>
                </div>
                <div
                  class="h-1 flex-1 mx-2 transition-all duration-300"
                  :class="quoteStep >= 3 ? 'bg-[#AE8625]' : 'bg-gray-300'"
                ></div>
              </div>

              <!-- Step 3 -->
              <div class="flex flex-col items-center flex-1">
                <div
                  class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300"
                  :class="quoteStep >= 3 ? 'bg-[#AE8625] text-white' : 'bg-gray-300 text-gray-600'"
                >
                  <Send v-if="quoteStep === 3" :size="20" />
                  <Check v-else-if="quoteStep > 3" :size="20" />
                  <span v-else class="text-sm font-bold">3</span>
                </div>
                <span class="mt-2 text-xs font-medium" :class="quoteStep >= 3 ? 'text-[#AE8625]' : 'text-gray-500'">
                  Submit Request
                </span>
              </div>
            </div>
          </div>

          <!-- Modal Body -->
          <div class="px-6 py-6">
            <!-- Step 1: Customer Details -->
            <div v-if="quoteStep === 1" class="space-y-5">
              <!-- Product Summary -->
              <div class="bg-[#F5F5F0] rounded-lg p-4 mb-6">
                <div class="flex items-start space-x-4">
                  <img
                    :src="product.images[0]"
                    :alt="product.name"
                    class="w-20 h-20 object-cover rounded-lg"
                  />
                  <div class="flex-1">
                    <h3 class="font-medium text-[#333333] mb-1">{{ product.name }}</h3>
                    <p class="text-sm text-[#666666] mb-2">SKU: {{ product.sku }}</p>
                    <div class="flex items-center space-x-4 text-sm text-[#666666]">
                      <span>Size: <strong class="text-[#333333]">{{ selectedSize }}</strong></span>
                      <span>Color: <strong class="text-[#333333]">{{ selectedColor }}</strong></span>
                      <span>Quantity: <strong class="text-[#333333]">{{ quantity }}</strong></span>
                    </div>
                  </div>
                </div>
              </div>

              <h3 class="text-xl font-serif text-[#333333] mb-4">Customer Information</h3>

              <!-- Name -->
              <div>
                <label for="name" class="block text-sm font-medium text-[#333333] mb-2">
                  Name <span class="text-red-500">*</span>
                </label>
                <input
                  id="name"
                  v-model="quoteForm.name"
                  type="text"
                  required
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333]"
                  placeholder="Enter your full name"
                />
              </div>

              <!-- Email -->
              <div>
                <label for="email" class="block text-sm font-medium text-[#333333] mb-2">
                  Email <span class="text-red-500">*</span>
                </label>
                <input
                  id="email"
                  v-model="quoteForm.email"
                  type="email"
                  required
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333]"
                  placeholder="Enter your email address"
                />
              </div>

              <!-- Telephone -->
              <div>
                <label for="telephone" class="block text-sm font-medium text-[#333333] mb-2">
                  Telephone <span class="text-red-500">*</span>
                </label>
                <input
                  id="telephone"
                  v-model="quoteForm.telephone"
                  type="tel"
                  required
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333]"
                  placeholder="Enter your phone number"
                />
              </div>

              <!-- Company -->
              <div>
                <label for="company" class="block text-sm font-medium text-[#333333] mb-2">
                  Company
                </label>
                <input
                  id="company"
                  v-model="quoteForm.company"
                  type="text"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333]"
                  placeholder="Enter your company name (optional)"
                />
              </div>

              <!-- Item Description -->
              <div>
                <label for="itemDescription" class="block text-sm font-medium text-[#333333] mb-2">
                  Item Description <span class="text-red-500">*</span>
                </label>
                <textarea
                  id="itemDescription"
                  v-model="quoteForm.itemDescription"
                  rows="4"
                  required
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333] resize-none"
                  placeholder="Describe the items you need..."
                ></textarea>
                <p class="mt-1 text-xs text-[#666666]">
                  Pre-filled with your selected product details. You can modify as needed.
                </p>
              </div>

              <!-- Expected Delivery Date -->
              <div>
                <label for="deliveryDate" class="block text-sm font-medium text-[#333333] mb-2">
                  Expected Delivery Date <span class="text-red-500">*</span>
                </label>
                <input
                  id="deliveryDate"
                  v-model="quoteForm.expectedDeliveryDate"
                  type="date"
                  required
                  :min="minDeliveryDate"
                  class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-[#AE8625] focus:outline-none transition-colors text-[#333333]"
                />
              </div>

              <!-- Step 1 Actions -->
              <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button
                  type="button"
                  @click="closeQuoteModal"
                  class="flex-1 px-6 py-3 border-2 border-gray-300 text-[#333333] rounded-lg font-medium hover:bg-gray-50 transition-all duration-300"
                >
                  Cancel
                </button>
                <button
                  type="button"
                  @click="nextStep"
                  class="flex-1 px-6 py-3 bg-[#AE8625] text-white rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2"
                >
                  Continue
                  <ArrowRight :size="20" />
                </button>
              </div>
            </div>

            <!-- Step 2: Review Details -->
            <div v-if="quoteStep === 2" class="space-y-6">
              <h3 class="text-xl font-serif text-[#333333] mb-4">Review Your Order</h3>

              <!-- Product Details Section -->
              <div class="bg-[#F5F5F0] rounded-lg p-6">
                <h4 class="text-lg font-medium text-[#333333] mb-4 flex items-center gap-2">
                  <Package :size="20" class="text-[#AE8625]" />
                  Product Details
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="flex items-start space-x-4">
                    <img
                      :src="product.images[0]"
                      :alt="product.name"
                      class="w-24 h-24 object-cover rounded-lg"
                    />
                    <div>
                      <h5 class="font-medium text-[#333333]">{{ product.name }}</h5>
                      <p class="text-sm text-[#666666]">SKU: {{ product.sku }}</p>
                      <p class="text-lg font-bold text-[#AE8625] mt-2">${{ product.price }}</p>
                    </div>
                  </div>
                  <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                      <span class="text-[#666666]">Size:</span>
                      <span class="font-medium text-[#333333]">{{ selectedSize }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-[#666666]">Color:</span>
                      <span class="font-medium text-[#333333]">{{ selectedColor }}</span>
                    </div>
                    <div class="flex justify-between">
                      <span class="text-[#666666]">Quantity:</span>
                      <span class="font-medium text-[#333333]">{{ quantity }}</span>
                    </div>
                    <div class="flex justify-between pt-2 border-t border-gray-300">
                      <span class="text-[#666666]">Subtotal:</span>
                      <span class="font-bold text-[#333333]">${{ (product.price * quantity).toFixed(2) }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Customer Details Section -->
              <div class="bg-white border-2 border-gray-200 rounded-lg p-6">
                <h4 class="text-lg font-medium text-[#333333] mb-4 flex items-center gap-2">
                  <User :size="20" class="text-[#AE8625]" />
                  Customer Information
                </h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div>
                    <p class="text-sm text-[#666666] mb-1">Name</p>
                    <p class="font-medium text-[#333333]">{{ quoteForm.name }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-[#666666] mb-1">Email</p>
                    <p class="font-medium text-[#333333]">{{ quoteForm.email }}</p>
                  </div>
                  <div>
                    <p class="text-sm text-[#666666] mb-1">Telephone</p>
                    <p class="font-medium text-[#333333]">{{ quoteForm.telephone }}</p>
                  </div>
                  <div v-if="quoteForm.company">
                    <p class="text-sm text-[#666666] mb-1">Company</p>
                    <p class="font-medium text-[#333333]">{{ quoteForm.company }}</p>
                  </div>
                  <div class="md:col-span-2">
                    <p class="text-sm text-[#666666] mb-1">Expected Delivery Date</p>
                    <p class="font-medium text-[#333333]">{{ formatDate(quoteForm.expectedDeliveryDate) }}</p>
                  </div>
                  <div class="md:col-span-2">
                    <p class="text-sm text-[#666666] mb-1">Item Description</p>
                    <p class="font-medium text-[#333333] whitespace-pre-line">{{ quoteForm.itemDescription }}</p>
                  </div>
                </div>
              </div>

              <!-- Step 2 Actions -->
              <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button
                  type="button"
                  @click="prevStep"
                  class="flex-1 px-6 py-3 border-2 border-gray-300 text-[#333333] rounded-lg font-medium hover:bg-gray-50 transition-all duration-300 flex items-center justify-center gap-2"
                >
                  <ArrowLeft :size="20" />
                  Back
                </button>
                <button
                  type="button"
                  @click="nextStep"
                  class="flex-1 px-6 py-3 bg-[#AE8625] text-white rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02] flex items-center justify-center gap-2"
                >
                  Continue to Submit
                  <ArrowRight :size="20" />
                </button>
              </div>
            </div>

            <!-- Step 3: Submit Request -->
            <div v-if="quoteStep === 3" class="space-y-6">
              <div v-if="!quoteSubmitted" class="text-center py-8">
                <div class="w-20 h-20 bg-[#F5F5F0] rounded-full flex items-center justify-center mx-auto mb-4">
                  <Send :size="40" class="text-[#AE8625]" />
                </div>
                <h3 class="text-2xl font-serif text-[#333333] mb-2">Ready to Submit?</h3>
                <p class="text-[#666666] mb-6">
                  Your order has been sent to our team. We'll contact you shortly with a detailed quote.
                </p>
                <div class="bg-[#F5F5F0] rounded-lg p-4 mb-6 text-left">
                  <p class="text-sm text-[#666666] mb-2">
                    <strong class="text-[#333333]">What happens next?</strong>
                  </p>
                  <ul class="text-sm text-[#666666] space-y-1 list-disc list-inside">
                    <li>You'll receive a confirmation email</li>
                    <li>Our team will review your request</li>
                    <li>We'll send you a detailed quote within 24-48 hours</li>
                    <li>You can download your quote invoice as PDF</li>
                  </ul>
                </div>
              </div>

              <!-- Success State -->
              <div v-else class="text-center py-8">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                  <Check :size="40" class="text-green-600" />
                </div>
                <h3 class="text-2xl font-serif text-[#333333] mb-2">Your order has been placed!</h3>
                <p class="text-[#666666] mb-6">
                  Thank you for your request. We've sent a confirmation email to <strong>{{ quoteForm.email }}</strong>
                </p>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                  <p class="text-sm text-green-800">
                    ✓ Confirmation email sent to your inbox<br>
                    ✓ Our team has been notified<br>
                    ✓ You'll receive a detailed quote within 24-48 hours
                  </p>
                </div>
                <div v-if="quoteInvoiceUrl" class="mb-6">
                  <a
                    :href="quoteInvoiceUrl"
                    target="_blank"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-[#AE8625] text-white rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02]"
                  >
                    <Download :size="20" />
                    Download Quote Invoice (PDF)
                  </a>
                </div>
              </div>

              <!-- Step 3 Actions -->
              <div class="flex flex-col sm:flex-row gap-3 pt-4">
                <button
                  v-if="!quoteSubmitted"
                  type="button"
                  @click="prevStep"
                  class="flex-1 px-6 py-3 border-2 border-gray-300 text-[#333333] rounded-lg font-medium hover:bg-gray-50 transition-all duration-300 flex items-center justify-center gap-2"
                >
                  <ArrowLeft :size="20" />
                  Back
                </button>
                <button
                  v-if="!quoteSubmitted"
                  type="button"
                  @click="submitQuote"
                  :disabled="isSubmittingQuote"
                  class="flex-1 px-6 py-3 bg-[#AE8625] text-white rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center justify-center gap-2"
                >
                  <Loader2 v-if="isSubmittingQuote" :size="20" class="animate-spin" />
                  <Send v-else :size="20" />
                  {{ isSubmittingQuote ? 'Submitting...' : 'Place Order' }}
                </button>
                <button
                  v-else
                  type="button"
                  @click="closeQuoteModal"
                  class="flex-1 px-6 py-3 bg-[#AE8625] text-white rounded-lg font-medium hover:bg-[#267347] transition-all duration-300 transform hover:scale-[1.02]"
                >
                  Close
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
    <Transition name="toast">
      <div
        v-if="showToast"
        class="fixed top-4 right-4 z-[100] max-w-sm w-full"
      >
        <div
          class="bg-white rounded-lg shadow-xl border-l-4 p-4 flex items-start gap-3"
          :class="toastType === 'success' ? 'border-green-500' : 'border-red-500'"
        >
          <div
            class="flex-shrink-0 mt-0.5"
            :class="toastType === 'success' ? 'text-green-500' : 'text-red-500'"
          >
            <Check v-if="toastType === 'success'" :size="24" class="fill-current" />
            <X v-else :size="24" class="fill-current" />
          </div>
          <div class="flex-1">
            <p
              class="font-medium text-[#333333]"
              :class="toastType === 'success' ? 'text-green-800' : 'text-red-800'"
            >
              {{ toastMessage }}
            </p>
            <p v-if="toastSubMessage" class="text-sm text-[#666666] mt-1">
              {{ toastSubMessage }}
            </p>
          </div>
          <button
            @click="showToast = false"
            class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors"
          >
            <X :size="18" />
          </button>
        </div>
      </div>
    </Transition>

    </PublicLayout>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { 
   ChevronRight, Star, Minus, Plus, 
  Truck, Shield, Package, Award, CreditCard, Check, X, Loader2, 
  User, FileText, Send, Download, ArrowLeft, ArrowRight
} from 'lucide-vue-next'
import PublicLayout from '@/layouts/PublicLayout.vue'
import { usePage, router } from '@inertiajs/vue3'

interface User {
  id: number;
  name: string;
  email: string;
}

interface Category {
  id: number
  name: string
  slug: string
}

interface ProductProp {
  id: number
  name: string
  sku: string
  price: number
  originalPrice?: number
  rating: number
  reviewCount: number
  shortDescription: string
  fullDescription: string
  badge?: string
  images: string[]
  sizes: string[]
  colors: { name: string; hex: string }[]
  features: string[]
  specifications: Record<string, string>
  reviews: Review[]
  category: Category // Added category
}

interface Review {
  id: number
  author: string
  date: string
  rating: number
  comment: string
  verified: boolean
}

interface SimilarProduct {
  id: number
  name: string
  slug: string
  price: number
  rating: number
  image: string
}

const page = usePage()
const user = computed<User | null>(() => page.props.auth?.user as User | null)

const props = defineProps<{
  product: ProductProp
  similarProducts?: SimilarProduct[]
}>()

const product = ref<ProductProp>(props.product)

const similarProducts = ref<SimilarProduct[]>(props.similarProducts || [])

const selectedImage = ref(props.product.images[0] || '')
const selectedSize = ref(props.product.sizes[0] || '')
const selectedColor = ref(props.product.colors[0]?.name || '')
const quantity = ref(1)
const activeTab = ref('Description')
const tabs = ['Description', 'Specifications', 'Reviews', 'Shipping']
const showZoom = ref(false)
const zoomStyle = ref({})
const showQuoteModal = ref(false)
const isSubmittingQuote = ref(false)
const quoteStep = ref(1) // 1: Customer Details, 2: Review, 3: Submit
const quoteSubmitted = ref(false)
const quoteInvoiceUrl = ref('')
const isAddingToCart = ref(false)
const showToast = ref(false)
const toastMessage = ref('')
const toastSubMessage = ref('')
const toastType = ref<'success' | 'error'>('success')

interface QuoteForm {
  name: string
  email: string
  telephone: string
  company: string
  itemDescription: string
  expectedDeliveryDate: string
}

const quoteForm = ref<QuoteForm>({
  name: '',
  email: '',
  telephone: '',
  company: '',
  itemDescription: '',
  expectedDeliveryDate: ''
})

// Calculate minimum delivery date (today)
const minDeliveryDate = computed(() => {
  const today = new Date()
  today.setDate(today.getDate() + 1) // Minimum 1 day from today
  return today.toISOString().split('T')[0]
})

// Watch for changes in the product prop and update reactive data
watch(() => props.product, (newProduct) => {
  product.value = newProduct
  selectedImage.value = newProduct.images[0] || ''
  selectedSize.value = newProduct.sizes[0] || ''
  selectedColor.value = newProduct.colors[0]?.name || ''
  quantity.value = 1 // Reset quantity when product changes
  activeTab.value = 'Description' // Reset active tab
}, { deep: true, immediate: true })

// Watch for similar products prop changes
watch(() => props.similarProducts, (newSimilarProducts) => {
  if (newSimilarProducts) {
    similarProducts.value = newSimilarProducts
  }
}, { immediate: true })

const goToProductDetails = (slug: string) => {
  router.get('/product-details', { slug: slug })
}

const selectImage = (image: string) => {
  selectedImage.value = image
}

const increaseQuantity = () => {
  quantity.value++
}

const decreaseQuantity = () => {
  if (quantity.value > 1) {
    quantity.value--
  }
}

const handleMouseMove = (event: MouseEvent) => {
  if (!showZoom.value) return
  
  const target = event.currentTarget as HTMLElement
  const rect = target.getBoundingClientRect()
  const x = ((event.clientX - rect.left) / rect.width) * 100
  const y = ((event.clientY - rect.top) / rect.height) * 100
  
  zoomStyle.value = {
    transformOrigin: `${x}% ${y}%`
  }
}

const getStarPercentage = (star: number): number => {
  const distribution: Record<number, number> = {
    5: 75,
    4: 15,
    3: 7,
    2: 2,
    1: 1
  }
  return distribution[star] || 0
}

const getStarCount = (star: number): number => {
  const percentage = getStarPercentage(star)
  return Math.round((percentage / 100) * product.value.reviewCount)
}

const showNotification = (message: string, subMessage: string = '', type: 'success' | 'error' = 'success') => {
  toastMessage.value = message
  toastSubMessage.value = subMessage
  toastType.value = type
  showToast.value = true
  
  // Auto-hide after 4 seconds
  setTimeout(() => {
    showToast.value = false
  }, 4000)
}

const addToCart = async () => {
  // Prevent multiple clicks
  if (isAddingToCart.value) return

  // Validate selections
  if (!selectedSize.value) {
    showNotification('Please select a size', '', 'error')
    return
  }

  if (!selectedColor.value) {
    showNotification('Please select a color', '', 'error')
    return
  }

  if (quantity.value < 1) {
    showNotification('Please select a valid quantity', '', 'error')
    return
  }

  isAddingToCart.value = true

  const item = {
    product_id: product.value.id,
    name: product.value.name,
    image: product.value.images[0],
    price: product.value.price,
    size: selectedSize.value,
    color: selectedColor.value,
    quantity: quantity.value,
  }

  if (user.value) {
    // User is logged in, send to backend
    try {
      await router.post('/cart/add', item, {
        onSuccess: () => {
          showNotification(
            'Product added to cart!',
            `${product.value.name} (${selectedSize.value}, ${selectedColor.value}) - Qty: ${quantity.value}`,
            'success'
          )
          // Dispatch event to refresh cart popup
          window.dispatchEvent(new CustomEvent('cartUpdated'))
        },
        onError: (errors) => {
          console.error('Error adding product to cart (logged in):', errors)
          const errorMessage = errors?.message || 'Failed to add product to cart. Please try again.'
          showNotification('Error adding to cart', errorMessage, 'error')
        },
        onFinish: () => {
          isAddingToCart.value = false
        }
      })
    } catch (error: any) {
      console.error('Network error adding product to cart:', error)
      showNotification('Network error', 'Please check your connection and try again.', 'error')
      isAddingToCart.value = false
    }
  } else {
    // User is not logged in, add to localStorage
    try {
      const guestCart = JSON.parse(localStorage.getItem('guestCart') || '[]')
      const existingItemIndex = guestCart.findIndex(
        (cartItem: any) => 
          cartItem.product_id === item.product_id &&
          cartItem.size === item.size &&
          cartItem.color === item.color
      )

      if (existingItemIndex > -1) {
        guestCart[existingItemIndex].quantity += item.quantity
        showNotification(
          'Cart updated!',
          `Quantity updated for ${product.value.name}`,
          'success'
        )
      } else {
        // Generate unique ID for guest cart items
        const newItem = {
          ...item,
          id: Date.now() + Math.random() // Generate unique ID
        }
        guestCart.push(newItem)
        showNotification(
          'Product added to cart!',
          `${product.value.name} (${selectedSize.value}, ${selectedColor.value}) - Qty: ${quantity.value}`,
          'success'
        )
      }
      
      localStorage.setItem('guestCart', JSON.stringify(guestCart))
      
      // Dispatch custom event to update cart count if needed
      window.dispatchEvent(new CustomEvent('cartUpdated', { detail: guestCart }))
      
    } catch (error) {
      console.error('Error saving to localStorage:', error)
      showNotification('Error', 'Failed to add product to cart. Please try again.', 'error')
    } finally {
      isAddingToCart.value = false
    }
  }
}

const openQuoteModal = () => {
  // Pre-fill item description with product details
  quoteForm.value.itemDescription = `Product: ${product.value.name}\nSKU: ${product.value.sku}\nSize: ${selectedSize.value}\nColor: ${selectedColor.value}\nQuantity: ${quantity.value}\n\n${product.value.shortDescription}`
  
  // Pre-fill user info if logged in
  if (user.value) {
    quoteForm.value.name = user.value.name
    quoteForm.value.email = user.value.email
  }
  
  // Reset step and submission state
  quoteStep.value = 1
  quoteSubmitted.value = false
  quoteInvoiceUrl.value = ''
  
  showQuoteModal.value = true
  // Prevent body scroll when modal is open
  document.body.style.overflow = 'hidden'
}

const closeQuoteModal = () => {
  showQuoteModal.value = false
  // Restore body scroll
  document.body.style.overflow = ''
  // Reset form and step
  quoteStep.value = 1
  quoteSubmitted.value = false
  quoteInvoiceUrl.value = ''
  quoteForm.value = {
    name: user.value?.name || '',
    email: user.value?.email || '',
    telephone: '',
    company: '',
    itemDescription: '',
    expectedDeliveryDate: ''
  }
}

const nextStep = () => {
  // Validate current step before proceeding
  if (quoteStep.value === 1) {
    // Validate step 1 fields
    if (!quoteForm.value.name || !quoteForm.value.email || !quoteForm.value.telephone || !quoteForm.value.itemDescription || !quoteForm.value.expectedDeliveryDate) {
      showNotification('Please fill in all required fields', '', 'error')
      return
    }
  }
  
  if (quoteStep.value < 3) {
    quoteStep.value++
  }
}

const prevStep = () => {
  if (quoteStep.value > 1) {
    quoteStep.value--
  }
}

const formatDate = (dateString: string): string => {
  if (!dateString) return ''
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  })
}

const submitQuote = async () => {
  isSubmittingQuote.value = true
  
  const quoteData = {
    ...quoteForm.value,
    product_id: product.value.id,
    product_name: product.value.name,
    sku: product.value.sku,
    size: selectedSize.value,
    color: selectedColor.value,
    quantity: quantity.value,
    price: product.value.price,
    subtotal: (product.value.price * quantity.value).toFixed(2)
  }

  try {
    await router.post('/quotes/request', quoteData, {
      onSuccess: (page: any) => {
        console.log('Order placed successfully', page)
        
        // Mark as submitted
        quoteSubmitted.value = true
        
        // Get invoice URL from response if available
        if (page?.props?.invoice_url) {
          quoteInvoiceUrl.value = page.props.invoice_url as string
        } else if (page?.props?.quote_id) {
          // Generate invoice URL if quote ID is provided
          quoteInvoiceUrl.value = `/quotes/${page.props.quote_id}/invoice`
        }
        
        // Show success notification
        showNotification(
          'Order Placed successfully!',
          'Confirmation email has been sent to your inbox.',
          'success'
        )
        
        // Move to success state (already on step 3)
        isSubmittingQuote.value = false
      },
      onError: (errors) => {
        console.error('Error submitting quote request:', errors)
        const errorMessage = errors?.message || 'There was an error placing your order. Please try again.'
        showNotification('Error submitting quote', errorMessage, 'error')
        isSubmittingQuote.value = false
      },
      onFinish: () => {
        isSubmittingQuote.value = false
      }
    })
  } catch (error) {
    console.error('Network error placing order:', error)
    showNotification('Network error', 'Please check your connection and try again.', 'error')
    isSubmittingQuote.value = false
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Cormorant+Garamond:wght@300;400;500;600;700&display=swap');

.font-serif {
  font-family: 'Playfair Display', serif;
}

* {
  font-family: 'Cormorant Garamond', serif;
  font-weight: 400;
  letter-spacing: 0.01em;
}

h1, h2, h3, h4, h5, h6 {
  font-family: 'Playfair Display', serif;
  font-weight: 600;
  letter-spacing: 0.02em;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
  appearance: textfield;
}

/* Toast Notification Animations */
.toast-enter-active {
  transition: all 0.3s ease-out;
}

.toast-leave-active {
  transition: all 0.3s ease-in;
}

.toast-enter-from {
  opacity: 0;
  transform: translateX(100%);
}

.toast-leave-to {
  opacity: 0;
  transform: translateX(100%);
}
</style>
