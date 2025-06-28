<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function index(Request $request)
    {
        $vendor = auth()->user();
        
        $query = $vendor->vendorProducts()->with('category:id,name');
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by listing status
        if ($request->filled('listing_status')) {
            $query->where('listing_status', $request->listing_status);
        }
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }
        
        $products = $query->select(
            'id', 'category_id', 'name', 'slug', 'description', 
            'price_per_day', 'stock', 'image', 'status', 'listing_status', 'created_at'
        )
        ->orderBy('created_at', 'desc')
        ->paginate(12);
        
        // Statistics for dashboard cards
        $stats = Cache::remember("vendor_product_stats_{$vendor->id}", 300, function () use ($vendor) {
            $products = $vendor->vendorProducts();
            return [
                'total_products' => $products->count(),
                'active_products' => $products->where('status', 'active')->count(),
                'pending_approval' => $products->where('listing_status', 'pending')->count(),
                'approved_products' => $products->where('listing_status', 'approved')->count(),
                'draft_products' => $products->where('listing_status', 'draft')->count(),
            ];
        });
        
        return view('vendor.products.index', compact('products', 'stats'));
    }

    public function create()
    {
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        return view('vendor.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $vendor = auth()->user();
        
        $data = $request->validated();
        $data['vendor_id'] = $vendor->id;
        $data['slug'] = Str::slug($data['name']);
        
        // Set listing status based on vendor verification
        $data['listing_status'] = $vendor->isVerified() ? 'pending' : 'draft';
        
        // Handle specifications JSON
        if ($request->filled('specifications')) {
            $data['specifications'] = json_encode($request->specifications);
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $this->fileUploadService->uploadImage(
                $request->file('image'), 
                'products'
            );
        }
        
        // Handle gallery upload
        if ($request->hasFile('gallery')) {
            $galleryImages = [];
            foreach ($request->file('gallery') as $file) {
                $galleryImages[] = $this->fileUploadService->uploadImage($file, 'products');
            }
            $data['gallery'] = json_encode($galleryImages);
        }
        
        $product = Product::create($data);
        
        // Clear vendor cache
        Cache::forget("vendor_product_stats_{$vendor->id}");
        Cache::forget("vendor_stats_{$vendor->id}");
        
        return redirect()->route('vendor.products.index')
                        ->with('success', 'Product created successfully! ' . 
                               ($vendor->isVerified() ? 'Submitted for admin approval.' : 'Saved as draft. Verify your account to submit for approval.'));
    }

    public function show(Product $product)
    {
        $this->authorizeVendorProduct($product);
        
        $product->load('category:id,name');
        
        // Get product rental statistics
        $rentalStats = [
            'total_rentals' => $product->rentals()->count(),
            'active_rentals' => $product->rentals()->where('status', 'active')->count(),
            'total_revenue' => $this->calculateProductRevenue($product),
            'average_rating' => 0, // Placeholder for future rating system
        ];
        
        return view('vendor.products.show', compact('product', 'rentalStats'));
    }

    public function edit(Product $product)
    {
        $this->authorizeVendorProduct($product);
        
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        $product->load('category:id,name');
        
        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorizeVendorProduct($product);
        $vendor = auth()->user();
        
        $data = $request->validated();
        
        // Update slug if name changed
        if ($data['name'] !== $product->name) {
            $data['slug'] = Str::slug($data['name']);
        }
        
        // Handle specifications JSON
        if ($request->filled('specifications')) {
            $data['specifications'] = json_encode($request->specifications);
        }
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($product->image) {
                $this->fileUploadService->deleteImage($product->image);
            }
            $data['image'] = $this->fileUploadService->uploadImage(
                $request->file('image'), 
                'products'
            );
        }
        
        // Handle gallery upload
        if ($request->hasFile('gallery')) {
            // Delete old gallery images
            if ($product->gallery) {
                $oldGallery = json_decode($product->gallery, true);
                foreach ($oldGallery as $image) {
                    $this->fileUploadService->deleteImage($image);
                }
            }
            
            $galleryImages = [];
            foreach ($request->file('gallery') as $file) {
                $galleryImages[] = $this->fileUploadService->uploadImage($file, 'products');
            }
            $data['gallery'] = json_encode($galleryImages);
        }
        
        // Reset to pending if significant changes and vendor is verified
        if ($vendor->isVerified() && $product->listing_status === 'approved') {
            $significantFields = ['name', 'description', 'price_per_day', 'category_id'];
            $hasSignificantChanges = collect($significantFields)->some(function ($field) use ($data, $product) {
                return isset($data[$field]) && $data[$field] != $product->$field;
            });
            
            if ($hasSignificantChanges || $request->hasFile('image')) {
                $data['listing_status'] = 'pending';
            }
        }
        
        $product->update($data);
        
        // Clear vendor cache
        Cache::forget("vendor_product_stats_{$vendor->id}");
        Cache::forget("vendor_stats_{$vendor->id}");
        
        return redirect()->route('vendor.products.index')
                        ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $this->authorizeVendorProduct($product);
        $vendor = auth()->user();
        
        // Check if product has active rentals
        if ($product->rentals()->whereIn('status', ['pending', 'active'])->exists()) {
            return redirect()->back()
                           ->with('error', 'Cannot delete product with active or pending rentals.');
        }
        
        // Delete associated images
        if ($product->image) {
            $this->fileUploadService->deleteImage($product->image);
        }
        
        if ($product->gallery) {
            $gallery = json_decode($product->gallery, true);
            foreach ($gallery as $image) {
                $this->fileUploadService->deleteImage($image);
            }
        }
        
        $product->delete();
        
        // Clear vendor cache
        Cache::forget("vendor_product_stats_{$vendor->id}");
        Cache::forget("vendor_stats_{$vendor->id}");
        
        return redirect()->route('vendor.products.index')
                        ->with('success', 'Product deleted successfully!');
    }

    public function toggleStatus(Product $product)
    {
        $this->authorizeVendorProduct($product);
        $vendor = auth()->user();
        
        $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        $product->update(['status' => $newStatus]);
        
        Cache::forget("vendor_product_stats_{$vendor->id}");
        
        return redirect()->back()
                        ->with('success', "Product {$newStatus} successfully!");
    }

    public function submitForApproval(Product $product)
    {
        $this->authorizeVendorProduct($product);
        $vendor = auth()->user();
        
        if (!$vendor->isVerified()) {
            return redirect()->back()
                           ->with('error', 'Your vendor account must be verified before submitting products for approval.');
        }
        
        if ($product->listing_status !== 'draft') {
            return redirect()->back()
                           ->with('error', 'Only draft products can be submitted for approval.');
        }
        
        $product->update(['listing_status' => 'pending']);
        
        Cache::forget("vendor_product_stats_{$vendor->id}");
        
        return redirect()->back()
                        ->with('success', 'Product submitted for admin approval!');
    }

    private function authorizeVendorProduct(Product $product)
    {
        if ($product->vendor_id !== auth()->id()) {
            abort(403, 'You can only manage your own products.');
        }
    }

    private function calculateProductRevenue(Product $product)
    {
        return $product->rentals()
                      ->whereIn('status', ['active', 'completed'])
                      ->get()
                      ->sum(function ($rental) use ($product) {
                          $commissionAmount = $product->getCommissionAmount($rental->total_price);
                          return $rental->total_price - $commissionAmount;
                      });
    }
}