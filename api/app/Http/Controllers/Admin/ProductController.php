<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        
        try {
            $product = new Product();
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->description = $request->description;
            
            // Process specifications - filter out empty entries
            $specifications = [];
            if ($request->specifications) {
                foreach ($request->specifications as $spec) {
                    if (!empty($spec['key']) && !empty($spec['value'])) {
                        $specifications[] = [
                            'key' => trim($spec['key']),
                            'value' => trim($spec['value'])
                        ];
                    }
                }
            }
            $product->specifications = $specifications;
            
            $product->price_per_day = $request->price_per_day;
            $product->stock = $request->stock;
            $product->status = $request->status;
            $product->listing_status = 'approved'; // Platform products are auto-approved

            // Handle main image upload
            if ($request->hasFile('image')) {
                $product->image = $this->fileUploadService->uploadImage($request->file('image'), 'products');
            }

            // Handle gallery images upload
            if ($request->hasFile('gallery')) {
                $galleryPaths = $this->fileUploadService->uploadMultipleImages($request->file('gallery'), 'products/gallery');
                $product->gallery = $galleryPaths;
            }

            $product->save();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'rentals.user']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'specifications' => 'nullable|array',
            'price_per_day' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        DB::beginTransaction();
        
        try {
            $product->category_id = $request->category_id;
            $product->name = $request->name;
            $product->slug = Str::slug($request->name);
            $product->description = $request->description;
            
            // Process specifications - filter out empty entries
            $specifications = [];
            if ($request->specifications) {
                foreach ($request->specifications as $spec) {
                    if (!empty($spec['key']) && !empty($spec['value'])) {
                        $specifications[] = [
                            'key' => trim($spec['key']),
                            'value' => trim($spec['value'])
                        ];
                    }
                }
            }
            $product->specifications = $specifications;
            
            $product->price_per_day = $request->price_per_day;
            $product->stock = $request->stock;
            $product->status = $request->status;
            
            // Ensure platform products remain approved
            if (is_null($product->vendor_id)) {
                $product->listing_status = 'approved';
            }

            // Handle main image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($product->image) {
                    $this->fileUploadService->deleteImage($product->image);
                }
                $product->image = $this->fileUploadService->uploadImage($request->file('image'), 'products');
            }

            // Handle gallery images upload
            if ($request->hasFile('gallery')) {
                // Delete old gallery images
                if ($product->gallery) {
                    $this->fileUploadService->deleteMultipleImages($product->gallery);
                }
                $galleryPaths = $this->fileUploadService->uploadMultipleImages($request->file('gallery'), 'products/gallery');
                $product->gallery = $galleryPaths;
            }

            $product->save();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        
        try {
            // Check if product has active rentals
            $activeRentals = $product->rentals()->whereIn('status', ['pending', 'active'])->count();
            
            if ($activeRentals > 0) {
                return back()->with('error', 'Cannot delete product with active rentals.');
            }

            // Delete associated images
            if ($product->image) {
                $this->fileUploadService->deleteImage($product->image);
            }

            if ($product->gallery) {
                $this->fileUploadService->deleteMultipleImages($product->gallery);
            }

            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
}
