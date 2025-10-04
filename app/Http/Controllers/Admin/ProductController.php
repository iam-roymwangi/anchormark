<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'images', 'productAttributes.attribute'])
            ->select([
                'id', 'category_id', 'name', 'slug', 'description',
                'price', 'stock_quantity', 're-order_level', 'shelf_life',
                'sku', 'specs_json', 'status', 'created_at', 'updated_at'
            ])
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'category' => [
                        'id' => $product->category_id,
                        'name' => $product->category->name ?? 'Uncategorized'
                    ],
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'description' => $product->description,
                    'price' => (float) $product->price,
                    'stock_quantity' => $product->stock_quantity,
                    're_order_level' => $product->{'re-order_level'},
                    'shelf_life' => $product->shelf_life,
                    'sku' => $product->sku,
                    'specs_json' => $product->specs_json,
                    'status' => $product->status,
                    'images' => $product->images->map(function ($image) {
                        return [
                            'id' => $image->id,
                            'product_id' => $image->product_id,
                            'image_url' => $image->image_url,
                        ];
                    }),
                    'attributes' => $product->productAttributes->map(function ($attr) {
                        return [
                            'id' => $attr->id,
                            'product_id' => $attr->product_id,
                            'attribute_id' => $attr->attribute_id,
                            'name' => $attr->attribute->name,
                            'data_type' => $attr->attribute->data_type,
                            'value_string' => $attr->value_string,
                            'value_number' => $attr->value_number,
                            'value_boolean' => $attr->value_boolean,
                            'value_date' => $attr->value_date,
                        ];
                    }),
                    'created_at' => $product->created_at->toISOString(),
                    'updated_at' => $product->updated_at->toISOString(),
                ];
            });

        $categories = Category::select('id', 'name')->get();

        return Inertia::render('admin/products/Index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function show($id)
    {
        $product = Product::with(['category', 'images', 'productAttributes.attribute'])
            ->findOrFail($id);

        return response()->json([
            'id' => $product->id,
            'category' => [
                'id' => $product->category_id,
                'name' => $product->category->name ?? 'Uncategorized'
            ],
            'name' => $product->name,
            'slug' => $product->slug,
            'description' => $product->description,
            'price' => $product->price,
            'stock_quantity' => $product->stock_quantity,
            're_order_level' => $product->re_order_level,
            'shelf_life' => $product->shelf_life,
            'sku' => $product->sku,
            'specs_json' => $product->specs_json,
            'status' => $product->status,
            'images' => $product->images,
            'attributes' => $product->productAttributes->map(function ($attr) {
                return [
                    'id' => $attr->id,
                    'product_id' => $attr->product_id,
                    'attribute_id' => $attr->attribute_id,
                    'name' => $attr->attribute->name,
                    'data_type' => $attr->attribute->data_type,
                    'value_string' => $attr->value_string,
                    "value_number" => $attr->value_number,
                    'value_boolean' => $attr->value_boolean,
                    'value_date' => $attr->value_date,
                ];
            }),
            'created_at' => $product->created_at->toISOString(),
            'updated_at' => $product->updated_at->toISOString(),
        ]);
    }

    public function create()
    {
        $categories = Category::select('id', 'name')->get();
        $attributes = Attribute::select('id', 'name', 'data_type')->get();
        
        return Inertia::render('admin/products/Create', [
            'categories' => $categories,
            'attributes' => $attributes,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            're-order_level' => 'required|integer|min:0',
            'shelf_life' => 'required|integer|min:1',
            'sku' => 'required|string|max:50|unique:products',
            'status' => 'required|in:active,inactive,discontinued',
            'specs_json' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'attributes' => 'nullable|array',
        ]);

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            're-order_level' => $request->re_order_level,
            'shelf_life' => $request->shelf_life,
            'sku' => $request->sku,
            'specs_json' => $request->specs_json,
            'status' => $request->status,
        ]);

        // Save images
        if ($request->images) {
            foreach ($request->images as $imageUrl) {
                $product->images()->create(['image_url' => $imageUrl]);
            }
        }

        // Save attributes
        if ($request->attributes) {
            foreach ($request->attributes as $attribute) {
                $product->productAttributes()->create([
                    'attribute_id' => $attribute['attribute_id'],
                    'value_string' => $attribute['value_string'] ?? null,
                    'value_number' => $attribute['value_number'] ?? null,
                    'value_boolean' => $attribute['value_boolean'] ?? null,
                    'value_date' => $attribute['value_date'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with(['category', 'images', 'productAttributes.attribute'])
            ->findOrFail($id);
        
        $categories = Category::select('id', 'name')->get();
        $attributes = Attribute::select('id', 'name', 'data_type')->get();
        
        return Inertia::render('admin/products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'attributes' => $attributes,
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $id,
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            're-order_level' => 'required|integer|min:0',
            'shelf_life' => 'required|integer|min:1',
            'sku' => 'required|string|max:50|unique:products,sku,' . $id,
            'status' => 'required|in:active,inactive,discontinued',
            'specs_json' => 'nullable|array',
            'images' => 'nullable|array',
            'images.*' => 'url',
            'attributes' => 'nullable|array',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'stock_quantity' => $request->stock_quantity,
            're-order_level' => $request->re_order_level,
            'shelf_life' => $request->shelf_life,
            'sku' => $request->sku,
            'specs_json' => $request->specs_json,
            'status' => $request->status,
        ]);

        // Update images
        $product->images()->delete();
        if ($request->images) {
            foreach ($request->images as $imageUrl) {
                $product->images()->create(['image_url' => $imageUrl]);
            }
        }

        // Update attributes
        $product->productAttributes()->delete();
        if ($request->attributes) {
            foreach ($request->attributes as $attribute) {
                $product->productAttributes()->create([
                    'attribute_id' => $attribute['attribute_id'],
                    'value_string' => $attribute['value_string'] ?? null,
                    'value_number' => $attribute['value_number'] ?? null,
                    'value_boolean' => $attribute['value_boolean'] ?? null,
                    'value_date' => $attribute['value_date'] ?? null,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
