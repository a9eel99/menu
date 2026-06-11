<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Restaurant;

class CheckRestaurantAccess
{
    /**
     * التحقق من صلاحية الوصول للمطعم
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // المدير لديه صلاحية كاملة على كل المطاعم
        if ($user->isAdmin()) {
            return $next($request);
        }
        
        // جلب المطعم من الـ route
        $restaurant = $request->route('restaurant');
        
        if ($restaurant) {
            if (is_numeric($restaurant)) {
                $restaurant = Restaurant::find($restaurant);
            }
            
            if (!$restaurant) {
                abort(404);
            }
            
            // الموظف: تحقق من restaurant_id
            $userRestaurantId = $user->restaurant_id;
            
            if (!$userRestaurantId) {
                abort(403, 'لم يتم تخصيص مطعم لحسابك');
            }
            
            // بناء قائمة المطاعم المسموح بها
            $allowedIds = [$userRestaurantId];
            $userRestaurant = Restaurant::find($userRestaurantId);
            
            if ($userRestaurant) {
                if (!$userRestaurant->parent_id) {
                    // مخصص لمطعم رئيسي - يمكنه الوصول للفروع
                    $allowedIds = array_merge($allowedIds, $userRestaurant->branches()->pluck('id')->toArray());
                } else {
                    // مخصص لفرع - يمكنه الوصول للرئيسي والفروع الأخرى
                    $parent = $userRestaurant->parent;
                    if ($parent) {
                        $allowedIds = [$parent->id, $userRestaurantId];
                        $allowedIds = array_merge($allowedIds, $parent->branches()->pluck('id')->toArray());
                    }
                }
            }
            
            if (!in_array($restaurant->id, $allowedIds)) {
                abort(403, 'غير مصرح لك بالوصول لهذا المطعم');
            }
        }
        
        return $next($request);
    }
}