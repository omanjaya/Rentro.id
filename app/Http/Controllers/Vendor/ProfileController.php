<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the vendor's profile form.
     */
    public function edit(Request $request): View
    {
        $vendor = $request->user();
        
        // Get vendor statistics for profile display
        $profileStats = [
            'total_products' => $vendor->vendorProducts()->count(),
            'approved_products' => $vendor->vendorProducts()->where('listing_status', 'approved')->count(),
            'total_rentals' => $vendor->vendorProducts()->withCount('rentals')->get()->sum('rentals_count'),
            'member_since' => $vendor->created_at->format('F Y'),
            'verification_date' => $vendor->verified_at ? $vendor->verified_at->format('F d, Y') : null,
        ];

        return view('vendor.profile.edit', [
            'user' => $vendor,
            'profileStats' => $profileStats,
        ]);
    }

    /**
     * Update the vendor's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $vendor = $request->user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,' . $vendor->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'business_name' => ['required', 'string', 'max:255'],
            'business_description' => ['nullable', 'string', 'max:1000'],
        ]);

        $updateData = $request->only([
            'name', 'email', 'phone', 'address', 'business_name', 'business_description'
        ]);

        // If email is changed, reset email verification
        if ($vendor->email !== $request->email) {
            $updateData['email_verified_at'] = null;
        }

        $vendor->fill($updateData);

        // If this is a significant profile change and vendor is verified, they may need re-verification
        if ($vendor->isDirty(['business_name', 'business_description']) && $vendor->isVerified()) {
            $hasSignificantChanges = true;
        }

        $vendor->save();

        // Clear vendor-related caches
        Cache::forget("vendor_stats_{$vendor->id}");
        Cache::forget("vendor_product_stats_{$vendor->id}");
        Cache::forget("vendor_rental_stats_{$vendor->id}");

        $message = 'Profile updated successfully!';
        if (isset($hasSignificantChanges)) {
            $message .= ' Please note that significant business changes may require admin review.';
        }

        return Redirect::route('vendor.profile.edit')->with('status', $message);
    }

    /**
     * Delete the vendor's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $vendor = $request->user();

        // Check if vendor has active rentals
        $activeRentals = $vendor->vendorProducts()
                              ->with('rentals')
                              ->get()
                              ->flatMap->rentals
                              ->whereIn('status', ['pending', 'active'])
                              ->count();

        if ($activeRentals > 0) {
            return Redirect::route('vendor.profile.edit')
                          ->with('error', 'Cannot delete account while you have active or pending rentals. Please complete all rentals first.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // Soft delete or anonymize vendor products instead of hard delete
        $vendor->vendorProducts()->update([
            'status' => 'inactive',
            'listing_status' => 'draft',
        ]);

        // Clear all vendor caches
        Cache::forget("vendor_stats_{$vendor->id}");
        Cache::forget("vendor_product_stats_{$vendor->id}");
        Cache::forget("vendor_rental_stats_{$vendor->id}");

        Auth::logout();

        $vendor->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Vendor account has been deleted successfully.');
    }

    /**
     * Request verification or re-verification
     */
    public function requestVerification(Request $request): RedirectResponse
    {
        $vendor = $request->user();

        if ($vendor->isVerified()) {
            return Redirect::route('vendor.profile.edit')
                          ->with('error', 'Your account is already verified.');
        }

        if ($vendor->verification_status === 'pending') {
            return Redirect::route('vendor.profile.edit')
                          ->with('error', 'Your verification request is already pending review.');
        }

        // Validate required fields for verification
        $missingFields = [];
        if (!$vendor->business_name) $missingFields[] = 'Business Name';
        if (!$vendor->business_description) $missingFields[] = 'Business Description';
        if (!$vendor->phone) $missingFields[] = 'Phone Number';
        if (!$vendor->address) $missingFields[] = 'Address';

        if (!empty($missingFields)) {
            return Redirect::route('vendor.profile.edit')
                          ->with('error', 'Please complete your profile before requesting verification. Missing: ' . implode(', ', $missingFields));
        }

        // Update verification status to pending
        $vendor->update([
            'verification_status' => 'pending',
            'verification_notes' => null, // Clear any previous rejection notes
        ]);

        return Redirect::route('vendor.profile.edit')
                      ->with('status', 'Verification request submitted successfully! Admin will review your application.');
    }
}
