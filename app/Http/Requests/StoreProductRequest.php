<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string|max:2000',
            'specifications' => 'nullable|array',
            'specifications.*.key' => 'nullable|string|max:100',
            'specifications.*.value' => 'nullable|string|max:500',
            'price_per_day' => 'required|numeric|min:1000|max:10000000',
            'stock' => 'required|integer|min:0|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            'status' => 'required|in:active,inactive',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'category_id' => 'category',
            'price_per_day' => 'price per day',
            'gallery.*' => 'gallery image',
            'specifications.*.key' => 'specification name',
            'specifications.*.value' => 'specification value',
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'price_per_day.min' => 'The price per day must be at least Rp 1,000.',
            'price_per_day.max' => 'The price per day must not exceed Rp 10,000,000.',
            'image.max' => 'The image must not exceed 5MB in size.',
            'gallery.*.max' => 'Each gallery image must not exceed 5MB in size.',
            'specifications.*.key.max' => 'Specification name must not exceed 100 characters.',
            'specifications.*.value.max' => 'Specification value must not exceed 500 characters.',
        ];
    }
}
