<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str; // Import the Str facade
use Inertia\Inertia;

class HomeController extends Controller
{
    public function products(Request $request)
    {
        $perPage = $request->get('per_page', 9);
        $category = $request->get('category', 'all');
        $search = $request->get('search', '');
        
        $query = Product::with(['category', 'images'])
            ->where('status', 'active')
            ->select([
                'id',
                'category_id',
                'name',
                'slug',
                'description',
                'price',
                'status',
                'created_at',
                'updated_at'
            ]);

        // Filter by category
        if ($category !== 'all') {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // Search functionality
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->paginate($perPage);

        // Transform the products data
        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => (float) $product->price,
                'category' => [
                    'id' => $product->category->id,
                    'name' => $product->category->name,
                    'slug' => $product->category->slug
                ],
                'images' => $product->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'product_id' => $image->product_id,
                        'image_url' => $image->image_url,
                    ];
                }),
                'slug' => $product->slug,
                'status' => $product->status,
                'created_at' => $product->created_at->toISOString(),
                'updated_at' => $product->updated_at->toISOString(),
            ];
        });

        // Get categories for sidebar
        $categories = Category::select('id', 'name', 'slug')
            ->whereHas('products', function ($q) {
                $q->where('status', 'active');
            })
            ->get();

        return Inertia::render('Products', [
            'products' => $products,
            'categories' => $categories,
            'filters' => [
                'category' => $category,
                'search' => $search,
                'per_page' => $perPage
            ]
        ]);
    }

    public function about()
    {
        return Inertia::render('About');
    }

    public function showProductDetails(Request $request)
    {
        $slug = $request->query('slug');

        if (!$slug) {
            // Handle case where no slug is provided, e.g., redirect or show error
            return redirect()->route('products')->with('error', 'Product not found.');
        }

        $product = Product::with(['category', 'images', 'productAttributes.attribute'])
            ->where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Transform product data for the frontend
        $transformedProduct = [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => 'PROD-' . str_pad($product->id, 4, '0', STR_PAD_LEFT), // Example SKU generation
            'price' => (float) $product->price,
            'originalPrice' => (float) $product->price + 100, // Example original price
            'rating' => 4.5, // Example rating
            'reviewCount' => 150, // Example review count
            'badge' => 'New Arrival', // Example badge
            'shortDescription' => Str::limit($product->description, 150),
            'fullDescription' => $product->description,
            'images' => $product->images->map(fn($image) => $image->image_url)->toArray(),
            'sizes' => ['Small', 'Medium', 'Large'], // Example sizes
            'colors' => [ // Example colors
                ['name' => 'Red', 'hex' => '#FF0000'],
                ['name' => 'Blue', 'hex' => '#0000FF'],
                ['name' => 'Green', 'hex' => '#00FF00'],
            ],
            'features' => [ // Example features
                'High-quality material',
                'Durable design',
                'Easy to clean',
                'Eco-friendly production',
            ],
            'specifications' => $product->productAttributes->pluck('value', 'attribute.name')->toArray(),
            'reviews' => [ // Example reviews
                [
                    'id' => 1,
                    'author' => 'John Doe',
                    'date' => '2023-01-01',
                    'rating' => 5,
                    'comment' => 'Excellent product!',
                    'verified' => true,
                ],
            ],
        ];

        return Inertia::render('ProductDetails', [
            'product' => $transformedProduct,
        ]);
    }

     public function contact()
    {
        return Inertia::render('Contact');
    }

     public function home()
    {
        // Fetch categories with active products
        $categories = Category::select('id', 'name', 'slug', 'description', 'image')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'description' => $category->description ?? '',
                    'image' => $category->image,
                ];
            });

        // Fetch featured products for the bento grid (latest 5 products)
        $featuredProducts = Product::with(['category', 'images'])
            ->where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'price' => (float) $product->price,
                    'category' => [
                        'id' => $product->category->id,
                        'name' => $product->category->name,
                        'slug' => $product->category->slug,
                    ],
                    'image' => $product->images->first()?->image_url ?? '/assets/images/Hotel-design.webp',
                ];
            });

        return Inertia::render('Welcome', [
            'categories' => $categories,
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
