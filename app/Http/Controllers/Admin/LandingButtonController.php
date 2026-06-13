<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\LandingButton;
use Illuminate\Http\Request;

class LandingButtonController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        $buttons = $restaurant->getOrCreateLandingButtons();
        return view('admin.landing-buttons.index', compact('restaurant', 'buttons'));
    }

    public function update(Request $request, Restaurant $restaurant, LandingButton $button)
    {
        $validated = $request->validate([
            'title_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'subtitle_ar' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $button->update($validated);

        return back()->with('success', __('messages.updated_successfully'));
    }

    public function reorder(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'buttons' => 'required|array',
            'buttons.*' => 'exists:landing_buttons,id',
        ]);

        foreach ($request->buttons as $index => $buttonId) {
            LandingButton::where('id', $buttonId)
                ->where('restaurant_id', $restaurant->id)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }

    public function toggle(Request $request, Restaurant $restaurant, LandingButton $button)
    {
        $button->update(['is_active' => !$button->is_active]);
        return back()->with('success', __('messages.updated_successfully'));
    }
}
