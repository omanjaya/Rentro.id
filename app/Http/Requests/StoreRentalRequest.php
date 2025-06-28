<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRentalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'start_date.after_or_equal' => 'The start date must be today or a future date.',
            'end_date.after' => 'The end date must be after the start date.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $startDate = $this->input('start_date');
            $endDate = $this->input('end_date');
            
            if ($startDate && $endDate) {
                $start = \Carbon\Carbon::parse($startDate);
                $end = \Carbon\Carbon::parse($endDate);
                
                // Check if rental period is too long (max 30 days)
                if ($start->diffInDays($end) > 30) {
                    $validator->errors()->add('end_date', 'The rental period cannot exceed 30 days.');
                }
                
                // Check product availability for the selected dates
                $product = $this->route('product');
                if ($product && !$this->isProductAvailable($product, $start, $end)) {
                    $validator->errors()->add('start_date', 'The product is not available for the selected dates.');
                }
            }
        });
    }

    /**
     * Check if product is available for the given date range.
     */
    private function isProductAvailable($product, $startDate, $endDate): bool
    {
        $conflictingRentals = $product->rentals()
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->count();

        return $conflictingRentals < $product->stock;
    }
}
