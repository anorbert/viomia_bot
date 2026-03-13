<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('register');
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'country_code' => ['required', 'string', 'max:5'],
            'phone_number' => ['required', 'regex:/^[0-9]{9,10}$/', 'unique:users,phone_number'],
            'pin' => ['required', 'numeric', 'min:4', 'max:999999', 'confirmed'],
            'terms' => ['required', 'accepted'],
            'subscription_plan_id' => ['nullable', 'exists:subscription_plans,id'],
        ]);

        $user = User::create([
            'uuid' => Str::uuid(),
            'name' => $request->name,
            'country_code' => $request->country_code,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->pin),
            'role_id' => 3, // Regular user
            'is_active' => true,
            'is_default_pin' => true,
        ]);

        // Initialize subscription plan for new users
        // If plan is chosen, use that plan; otherwise use Free Demo plan
        $planId = $request->subscription_plan_id;
        
        if (!$planId) {
            // No plan chosen - use Free Demo plan
            $plan = SubscriptionPlan::where('name', 'Free Demo')->orWhere('price', 0)->first();
        } else {
            // Use the chosen plan
            $plan = SubscriptionPlan::find($planId);
        }
        
        if ($plan) {
            UserSubscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'reference' => strtoupper($plan->name) . '-' . strtoupper(uniqid()),
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addYear(),
            ]);
        }

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('user.change-pin.create')
            ->with('warning', 'Please change your default PIN.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
