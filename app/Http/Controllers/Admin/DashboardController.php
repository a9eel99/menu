<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin()) {
            // المدير يرى كل المطاعم
            $restaurants = Restaurant::whereNull('parent_id')
                ->with(['branches'])
                ->withCount(['categories', 'menuItems', 'branches'])
                ->get();
                
            $stats = [
                'restaurants_count' => Restaurant::whereNull('parent_id')->count(),
                'branches_count' => Restaurant::whereNotNull('parent_id')->count(),
                'categories_count' => Category::count(),
                'items_count' => MenuItem::count(),
            ];
        } elseif ($user->restaurant_id) {
            // الموظف يرى المطعم المخصص له
            $restaurant = Restaurant::with('branches')
                ->withCount(['categories', 'menuItems', 'branches'])
                ->find($user->restaurant_id);
                
            if ($restaurant) {
                if ($restaurant->parent_id) {
                    $mainRestaurant = Restaurant::with('branches')
                        ->withCount(['categories', 'menuItems', 'branches'])
                        ->find($restaurant->parent_id);
                    $restaurants = $mainRestaurant ? collect([$mainRestaurant]) : collect();
                } else {
                    $restaurants = collect([$restaurant]);
                }
                
                $restaurantIds = $restaurants->pluck('id')->merge(
                    $restaurants->flatMap->branches->pluck('id')
                );
                
                $stats = [
                    'restaurants_count' => $restaurants->where('parent_id', null)->count(),
                    'branches_count' => $restaurants->flatMap->branches->count(),
                    'categories_count' => Category::whereIn('restaurant_id', $restaurantIds)->count(),
                    'items_count' => MenuItem::whereIn('restaurant_id', $restaurantIds)->count(),
                ];
            } else {
                $restaurants = collect();
                $stats = ['restaurants_count' => 0, 'branches_count' => 0, 'categories_count' => 0, 'items_count' => 0];
            }
        } else {
            $restaurants = collect();
            $stats = ['restaurants_count' => 0, 'branches_count' => 0, 'categories_count' => 0, 'items_count' => 0];
        }
        
        return view('admin.dashboard', compact('restaurants', 'stats'));
    }
}