<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beddingsCategory = Category::where('slug', 'beddings')->first();
        $kitchenwareCategory = Category::where('slug', 'kitchenware')->first();
        $furnitureCategory = Category::where('slug', 'furniture')->first();

        $products = [
            // Beddings
            [
                'category_id' => $beddingsCategory->id,
                'name' => 'Luxury Egyptian Cotton Sheets',
                'slug' => 'luxury-egyptian-cotton-sheets',
                'description' => '1000 thread count, hotel-grade bedding made from premium Egyptian cotton for ultimate comfort and durability.',
                'price' => 299.00,
                'stock_quantity' => 50,
                're_order_level' => 10,
                'shelf_life' => 3650,
                'sku' => 'BED-001',
                'status' => 'active',
                'images' => ['/placeholder.svg?height=400&width=400']
            ],
            [
                'category_id' => $beddingsCategory->id,
                'name' => 'Premium Down Pillows',
                'slug' => 'premium-down-pillows',
                'description' => 'Hypoallergenic, cloud-like comfort with premium down filling for the perfect night\'s sleep.',
                'price' => 89.00,
                'stock_quantity' => 75,
                're_order_level' => 15,
                'shelf_life' => 3650,
                'sku' => 'BED-002',
                'status' => 'active',
                'images' => ['/placeholder.svg?height=400&width=400']
            ],
            [
                'category_id' => $beddingsCategory->id,
                'name' => 'Silk Duvet Cover Set',
                'slug' => 'silk-duvet-cover-set',
                'description' => 'Luxurious mulberry silk bedding set with elegant design and exceptional softness.',
                'price' => 449.00,
                'stock_quantity' => 30,
                're_order_level' => 8,
                'shelf_life' => 3650,
                'sku' => 'BED-003',
                'status' => 'active',
                'images' => ['/placeholder.svg?height=400&width=400']
            ],
            // Kitchenware
            [
                'category_id' => $kitchenwareCategory->id,
                'name' => 'Professional Cookware Set',
                'slug' => 'professional-cookware-set',
                'description' => 'Commercial-grade stainless steel cookware set perfect for hotel kitchens.',
                'price' => 599.00,
                'stock_quantity' => 25,
                're_order_level' => 5,
                'shelf_life' => 3650,
                'sku' => 'KIT-001',
                'status' => 'active',
                'images' => ['/placeholder.svg?height=400&width=400']
            ],
            [
                'category_id' => $kitchenwareCategory->id,
                'name' => 'Fine Dining Plate Collection',
                'slug' => 'fine-dining-plate-collection',
                'description' => 'Elegant porcelain dinnerware collection for upscale dining experiences.',
                'price' => 199.00,
                'stock_quantity' => 100,
                're_order_level' => 20,
                'shelf_life' => 3650,
                'sku' => 'KIT-002',
                'status' => 'active',
                'images' => ['/placeholder.svg?height=400&width=400']
            ],
            [
                'category_id' => $kitchenwareCategory->id,
                'name' => 'Crystal Glassware Set',
                'slug' => 'crystal-glassware-set',
                'description' => 'Hand-blown crystal stemware collection for premium beverage service.',
                'price' => 349.00,
                'stock_quantity' => 40,
                're_order_level' => 10,
                'shelf_life' => 3650,
                'sku' => 'KIT-003',
                'status' => 'active',
                'images' => ['/placeholder.svg?height=400&width=400']
            ],
            // Furniture
            [
                'category_id' => $furnitureCategory->id,
                'name' => 'Executive Desk Chair',
                'slug' => 'executive-desk-chair',
                'description' => 'Ergonomic leather office seating designed for comfort and professional appearance.',
                'price' => 799.00,
                'stock_quantity' => 20,
                're_order_level' => 5,
                'shelf_life' => 3650,
                'sku' => 'FUR-001',
                'status' => 'active',
                'images' => ['/placeholder.svg?height=400&width=400']
            ],
            [
                'category_id' => $furnitureCategory->id,
                'name' => 'Modern Lounge Sofa',
                'slug' => 'modern-lounge-sofa',
                'description' => 'Contemporary design with premium fabric upholstery for hotel lobbies and lounges.',
                'price' => 1899.00,
                'stock_quantity' => 15,
                're_order_level' => 3,
                'shelf_life' => 3650,
                'sku' => 'FUR-002',
                'status' => 'active',
                'images' => ['/placeholder.svg?height=400&width=400']
            ],
            [
                'category_id' => $furnitureCategory->id,
                'name' => 'Boutique Nightstand',
                'slug' => 'boutique-nightstand',
                'description' => 'Solid wood nightstand with brass accents, perfect for hotel guest rooms.',
                'price' => 599.00,
                'stock_quantity' => 35,
                're_order_level' => 8,
                'shelf_life' => 3650,
                'sku' => 'FUR-003',
                'status' => 'active',
                'images' => ['/placeholder.svg?height=400&width=400']
            ]
        ];

        foreach ($products as $productData) {
            $images = $productData['images'];
            unset($productData['images']);
            
            $product = Product::create($productData);
            
            // Create product images
            foreach ($images as $imageUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imageUrl
                ]);
            }
        }
    }
}
