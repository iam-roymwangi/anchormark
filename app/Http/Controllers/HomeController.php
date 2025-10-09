<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
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

    public function showProductDetails()
    {
        return Inertia::render('ProductDetails');
    }

     public function contact()
    {
        return Inertia::render('Contact');
    }
}
