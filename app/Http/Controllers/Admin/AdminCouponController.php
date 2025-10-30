<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminCouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'code' => 'required|string|max:255|unique:coupons,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|integer|min:1',
            'expired_at' => 'nullable|date|after_or_equal:today',
            'limit' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'min_purchase_amount' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        
        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon successfully created!');
    }

    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'code' => [
                'required',
                'string',
                'max:255',
                Rule::unique('coupons', 'code')->ignore($coupon->id),
            ],
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|integer|min:1',
            'expired_at' => 'nullable|date|after_or_equal:today',
            'limit' => 'nullable|integer|min:1',
            'max_uses_per_user' => 'nullable|integer|min:1',
            'min_purchase_amount' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon successfully updated!');
    }

    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}