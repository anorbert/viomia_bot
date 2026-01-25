<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\SubscriptionPlan;
use Illuminate\Validation\Rule;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::orderBy('sort_order')->orderByDesc('is_active')->paginate(20);
        return view('admin.payments.subscriptions.plans', compact('plans'));
    }

    public function create()
    {
        return view('admin.payments.subscriptions.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['required','string','max:255','alpha_dash','unique:subscription_plans,slug'],
            'currency' => ['required','string','max:10'],
            'price' => ['required','numeric','min:0'],
            'billing_interval' => ['required', Rule::in(['daily','weekly','monthly','yearly'])],
            'duration_days' => ['nullable','integer','min:1'],
            'description' => ['nullable','string'],
            'features' => ['nullable'], // we’ll normalize below
            'is_active' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0'],
        ]);

        $data['is_active'] = (bool)($request->is_active ?? false);
        $data['features'] = $this->normalizeFeatures($request->features);

        SubscriptionPlan::create($data);

        return redirect()->route('admin.subscription-plans.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    public function edit(SubscriptionPlan $subscription_plan)
    {
        return view('admin.payments.subscriptions.edit', [
            'plan' => $subscription_plan
        ]);
    }

    public function update(Request $request, SubscriptionPlan $subscription_plan)
    {
        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'slug' => ['required','string','max:255','alpha_dash', Rule::unique('subscription_plans','slug')->ignore($subscription_plan->id)],
            'currency' => ['required','string','max:10'],
            'price' => ['required','numeric','min:0'],
            'billing_interval' => ['required', Rule::in(['daily','weekly','monthly','yearly'])],
            'duration_days' => ['nullable','integer','min:1'],
            'description' => ['nullable','string'],
            'features' => ['nullable'],
            'is_active' => ['nullable','boolean'],
            'sort_order' => ['nullable','integer','min:0'],
        ]);

        $data['is_active'] = (bool)($request->is_active ?? false);
        $data['features'] = $this->normalizeFeatures($request->features);

        $subscription_plan->update($data);

        return redirect()->route('admin.payments.subscription_plans.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $subscription_plan)
    {
        $subscription_plan->delete();

        return redirect()->route('admin.subscription_plans.index')
            ->with('success', 'Subscription plan deleted.');
    }

    private function normalizeFeatures($features)
    {
        // Accept:
        // 1) textarea JSON string
        // 2) array from dynamic inputs
        if (is_string($features)) {
            $features = trim($features);
            if ($features === '') return null;

            $decoded = json_decode($features, true);
            return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }

        if (is_array($features)) return $features;

        return null;
    }
}
