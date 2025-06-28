<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $validationRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'user_type' => ['required', 'in:individual,business,vendor'],
        ];

        // Add business field validation for business and vendor accounts
        if (in_array($request->user_type, ['business', 'vendor'])) {
            $validationRules['business_name'] = ['required', 'string', 'max:255'];
            $validationRules['business_description'] = ['required', 'string', 'max:1000'];
        }

        $request->validate($validationRules);

        // Determine verification status based on user type
        $verificationStatus = $request->user_type === 'individual' ? 'verified' : 'pending';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'business_name' => $request->business_name,
            'business_description' => $request->business_description,
            'verification_status' => $verificationStatus,
            'verified_at' => $verificationStatus === 'verified' ? now() : null,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect based on user type
        return $this->redirectBasedOnUserType($user);
    }

    private function redirectBasedOnUserType(User $user): RedirectResponse
    {
        switch ($user->user_type) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'vendor':
                return redirect()->route('vendor.dashboard')->with('success', 
                    'Welcome! Your vendor account is pending verification. You can start adding products once approved.');
            case 'business':
                return redirect()->route('dashboard')->with('success', 
                    'Welcome! Your business account is pending verification.');
            default:
                return redirect()->route('dashboard')->with('success', 
                    'Welcome to Rentro.id! Start exploring our equipment rentals.');
        }
    }
}
