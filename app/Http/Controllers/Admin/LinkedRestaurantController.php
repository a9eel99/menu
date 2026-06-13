<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LinkedRestaurantController extends Controller
{
    public function index(Restaurant $restaurant)
    {
        $linkedRestaurants = $restaurant->allLinkedRestaurants();

        $availableRestaurants = Restaurant::where('user_id', auth()->id())
            ->where('id', '!=', $restaurant->id)
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->where(function($q) use ($restaurant) {
                $q->whereNull('linked_group_id')
                  ->orWhere('linked_group_id', $restaurant->linked_group_id);
            })
            ->get();

        return view('admin.linked-restaurants.index', compact('restaurant', 'linkedRestaurants', 'availableRestaurants'));
    }

    public function link(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'restaurant_ids' => 'required|array',
            'restaurant_ids.*' => 'exists:restaurants,id',
        ]);

        $groupId = $restaurant->linked_group_id ?: Str::uuid()->toString();

        $restaurant->update([
            'linked_group_id' => $groupId,
            'show_linked_selector' => true,
        ]);

        foreach ($request->restaurant_ids as $id) {
            Restaurant::where('id', $id)->update([
                'linked_group_id' => $groupId,
                'show_linked_selector' => true,
            ]);
        }

        return back()->with('success', __('messages.restaurants_linked'));
    }

    public function unlink(Restaurant $restaurant, Restaurant $linked)
    {
        if ($restaurant->linked_group_id !== $linked->linked_group_id) {
            return back()->with('error', __('messages.invalid_operation'));
        }

        $linked->update([
            'linked_group_id' => null,
            'show_linked_selector' => false,
        ]);

        $remainingCount = Restaurant::where('linked_group_id', $restaurant->linked_group_id)->count();
        if ($remainingCount <= 1) {
            $restaurant->update([
                'linked_group_id' => null,
                'show_linked_selector' => false,
            ]);
        }

        return back()->with('success', __('messages.restaurant_unlinked'));
    }

    public function toggleSelector(Request $request, Restaurant $restaurant)
    {
        $restaurant->update([
            'show_linked_selector' => !$restaurant->show_linked_selector,
        ]);

        return back()->with('success', __('messages.updated_successfully'));
    }
}
