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
        $query = SubscriptionPlan::query();

        // Search filter
        if (request('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        // Status filter
        if (request('status') === 'active') {
            $query->where('is_active', true);
        } elseif (request('status') === 'inactive') {
            $query->where('is_active', false);
        }

        // Visibility filter
        if (request('visibility') === 'visible') {
            $query->where('is_visible', true);
        } elseif (request('visibility') === 'hidden') {
            $query->where('is_visible', false);
        }

        // Price range filter
        if (request('price_min')) {
            $query->where('price', '>=', request('price_min'));
        }
        if (request('price_max')) {
            $query->where('price', '<=', request('price_max'));
        }

        $plans = $query->orderBy('sort_order')->orderByDesc('is_active')->paginate(20);
        
        return view('admin.subscription_plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.payments.subscriptions.create');
    }

    public function show(SubscriptionPlan $subscription_plan)
    {
        return view('admin.subscription_plans.show', [
            'plan' => $subscription_plan
        ]);
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

    public function reorder(Request $request)
    {
        $orders = $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:subscription_plans,id',
            'orders.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($orders['orders'] as $order) {
            SubscriptionPlan::where('id', $order['id'])->update([
                'sort_order' => $order['sort_order']
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Plans reordered successfully']);
    }

    public function comparison()
    {
        $plans = SubscriptionPlan::orderBy('sort_order')->get();
        return view('admin.subscription_plans.comparison', compact('plans'));
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
