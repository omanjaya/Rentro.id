<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UpdateRentalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['sometimes', 'exists:users,id'],
            'product_id' => ['sometimes', 'exists:products,id'],
            'start_date' => ['sometimes', 'date'],
            'end_date' => ['sometimes', 'date', 'after:start_date'],
            'status' => ['sometimes', Rule::in(['pending', 'active', 'completed', 'cancelled'])],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $rental = $this->route('rental');
            
            if ($this->start_date && $this->end_date) {
                $startDate = Carbon::parse($this->start_date);
                $endDate = Carbon::parse($this->end_date);
                
                // Check if rental period is reasonable (max 365 days)
                if ($startDate->diffInDays($endDate) > 365) {
                    $validator->errors()->add('end_date', 'Rental period cannot exceed 365 days.');
                }
                
                // Check product availability (exclude current rental)
                if ($this->product_id || $this->start_date || $this->end_date) {
                    $productId = $this->product_id ?? $rental->product_id;
                    
                    $existingRentals = \App\Models\Rental::where('product_id', $productId)
                        ->where('id', '!=', $rental->id)
                        ->where('status', '!=', 'cancelled')
                        ->where(function ($query) use ($startDate, $endDate) {
                            $query->whereBetween('start_date', [$startDate, $endDate])
                                  ->orWhereBetween('end_date', [$startDate, $endDate])
                                  ->orWhere(function ($q) use ($startDate, $endDate) {
                                      $q->where('start_date', '<=', $startDate)
                                        ->where('end_date', '>=', $endDate);
                                  });
                        })->exists();
                    
                    if ($existingRentals) {
                        $validator->errors()->add('start_date', 'Product is not available for the selected dates.');
                    }
                }
            }
            
            // Prevent changing dates for completed rentals
            if ($rental && $rental->status === 'completed' && ($this->start_date || $this->end_date)) {
                $validator->errors()->add('start_date', 'Cannot modify dates for completed rentals.');
            }
        });
    }

    /**
     * Get custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'user_id.exists' => 'Selected user does not exist.',
            'product_id.exists' => 'Selected product does not exist.',
            'start_date.date' => 'Start date must be a valid date.',
            'end_date.date' => 'End date must be a valid date.',
            'end_date.after' => 'End date must be after start date.',
            'status.in' => 'Invalid status selected.',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }
}