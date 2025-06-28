<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required', Rule::in(['individual', 'business', 'vendor', 'admin'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'business_name' => ['nullable', 'required_if:user_type,business,vendor', 'string', 'max:255'],
            'business_description' => ['nullable', 'string', 'max:1000'],
            'verification_status' => ['sometimes', Rule::in(['pending', 'verified', 'rejected'])],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:50'],
        ];
    }

    /**
     * Get custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'user_type.required' => 'User type is required.',
            'user_type.in' => 'Invalid user type selected.',
            'business_name.required_if' => 'Business name is required for business and vendor accounts.',
            'commission_rate.max' => 'Commission rate cannot exceed 50%.',
        ];
    }
}