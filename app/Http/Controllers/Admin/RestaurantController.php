<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RestaurantController extends Controller
{
    /**
     * عرض قائمة المطاعم/الفروع
     */
    public function index()
    {
$restaurants = Restaurant::whereNull('parent_id')
        ->select([
            'id', 'name_ar', 'name_en', 'slug', 'logo', 
            'primary_color', 'is_active', 'created_at'
        ])
        ->with(['branches' => function($q) {
            $q->select(['id', 'parent_id', 'name_ar', 'name_en', 'slug', 'logo', 'is_active'])
              ->withCount(['categories', 'menuItems']);
        }])
        ->withCount(['branches', 'categories', 'menuItems'])
        ->orderBy('created_at', 'desc')
        ->get();

    return view('admin.restaurants.index', compact('restaurants'));
    }

    /**
     * صفحة إنشاء مطعم جديد
     */
    public function create()
    {
        // الموظف لا يقدر يضيف مطعم
        if (Auth::user()->isStaff()) {
            return redirect()->route('admin.restaurants.index')
                ->with('error', 'ليس لديك صلاحية إضافة مطعم جديد');
        }
        
        $mainRestaurants = Restaurant::whereNull('parent_id')->get();

        return view('admin.restaurants.create', compact('mainRestaurants'));
    }

    /**
     * حفظ مطعم جديد
     */
    public function store(Request $request)
    {
        // الموظف لا يقدر يضيف مطعم
        if (Auth::user()->isStaff()) {
            return redirect()->route('admin.restaurants.index')
                ->with('error', 'ليس لديك صلاحية إضافة مطعم جديد');
        }
        
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:restaurants,id',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'address_ar' => 'nullable|string',
            'address_en' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'google_maps_url' => 'nullable|url|max:500',
            'google_reviews_url' => 'nullable|url|max:500',
            'working_hours_ar' => 'nullable|string|max:255',
            'working_hours_en' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'menu_type' => 'nullable|in:digital,pdf',
            'menu_pdf' => 'nullable|file|mimes:pdf|max:30720',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');
        $validated['menu_type'] = $request->input('menu_type', 'digital');

        // رفع الصور
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('restaurants/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('restaurants/covers', 'public');
        }

        // رفع ملف PDF
        if ($request->hasFile('menu_pdf')) {
            $validated['menu_pdf'] = $request->file('menu_pdf')->store('restaurants/menus', 'public');
        }

        $restaurant = Restaurant::create($validated);

        $type = $restaurant->isBranch() ? 'الفرع' : 'المطعم';
        return redirect()->route('admin.restaurants.show', $restaurant)
            ->with('success', "تم إنشاء {$type} بنجاح");
    }

    /**
     * عرض تفاصيل المطعم (الداشبورد)
     */
    public function show(Restaurant $restaurant)
    {
        $this->checkOwnership($restaurant);

        $restaurant->loadCount(['branches', 'categories', 'menuItems']);
        $restaurant->load([
            'branches' => function($q) {
                $q->withCount(['categories', 'menuItems']);
            },
            'categories' => function($q) {
                $q->orderBy('sort_order')->with(['menuItems' => function($q) {
                    $q->orderBy('sort_order');
                }]);
            },
            'menuItems',
            'socialLinks'
        ]);

        return view('admin.restaurants.show', compact('restaurant'));
    }

    /**
     * صفحة تعديل المطعم
     */
    public function edit(Restaurant $restaurant)
    {
        $this->checkOwnership($restaurant);

        $mainRestaurants = Restaurant::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->where('id', '!=', $restaurant->id)
            ->get();

        return view('admin.restaurants.edit', compact('restaurant', 'mainRestaurants'));
    }

    /**
     * تحديث المطعم
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $this->checkOwnership($restaurant);

        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:restaurants,id',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'address_ar' => 'nullable|string',
            'address_en' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'google_maps_url' => 'nullable|url|max:500',
            'google_reviews_url' => 'nullable|url|max:500',
            'working_hours_ar' => 'nullable|string|max:255',
            'working_hours_en' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:10',
            'currency_symbol' => 'nullable|string|max:10',
            'primary_color' => 'nullable|string|max:20',
            'secondary_color' => 'nullable|string|max:20',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'menu_type' => 'nullable|in:digital,pdf',
            'menu_pdf' => 'nullable|file|mimes:pdf|max:30720',
        ]);

        // التحقق من أن الأب تابع للمستخدم ومش نفس المطعم
        if (!empty($validated['parent_id'])) {
            if ($validated['parent_id'] == $restaurant->id) {
                return back()->withErrors(['parent_id' => 'لا يمكن أن يكون المطعم فرع لنفسه']);
            }
            $parent = Restaurant::where('id', $validated['parent_id'])
                ->where('user_id', Auth::id())
                ->firstOrFail();
        }

        $validated['is_active'] = $request->has('is_active');

        // رفع الصور
        if ($request->hasFile('logo')) {
            if ($restaurant->logo) {
                Storage::disk('public')->delete($restaurant->logo);
            }
            $validated['logo'] = $request->file('logo')->store('restaurants/logos', 'public');
        }

        if ($request->hasFile('cover_image')) {
            if ($restaurant->cover_image) {
                Storage::disk('public')->delete($restaurant->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('restaurants/covers', 'public');
        }

        // حذف الصور إذا طلب
        if ($request->input('remove_logo') == '1' && $restaurant->logo) {
            Storage::disk('public')->delete($restaurant->logo);
            $validated['logo'] = null;
        }

        if ($request->input('remove_cover') == '1' && $restaurant->cover_image) {
            Storage::disk('public')->delete($restaurant->cover_image);
            $validated['cover_image'] = null;
        }

        // رفع ملف PDF
        if ($request->hasFile('menu_pdf')) {
            if ($restaurant->menu_pdf) {
                Storage::disk('public')->delete($restaurant->menu_pdf);
            }
            $validated['menu_pdf'] = $request->file('menu_pdf')->store('restaurants/menus', 'public');
        }

        // حذف PDF إذا طلب
        if ($request->has('remove_pdf') && $restaurant->menu_pdf) {
            Storage::disk('public')->delete($restaurant->menu_pdf);
            $validated['menu_pdf'] = null;
            $validated['menu_type'] = 'digital';
        }

        // تحديث نوع المنيو
        if ($request->has('menu_type')) {
            $validated['menu_type'] = $request->input('menu_type');
        }

        $restaurant->update($validated);

        return redirect()->route('admin.restaurants.show', $restaurant)
            ->with('success', 'تم تحديث البيانات بنجاح');
    }

    /**
     * حذف المطعم
     */
    public function destroy(Restaurant $restaurant)
    {
        $this->checkOwnership($restaurant);

        // حذف الصور
        if ($restaurant->logo) {
            Storage::disk('public')->delete($restaurant->logo);
        }
        if ($restaurant->cover_image) {
            Storage::disk('public')->delete($restaurant->cover_image);
        }

        $restaurant->delete();

        return redirect()->route('admin.restaurants.index')
            ->with('success', 'تم الحذف بنجاح');
    }

    /**
     * رفع صورة (AJAX)
     */
    public function uploadImage(Request $request, Restaurant $restaurant)
    {
        $this->checkOwnership($restaurant);

        $type = $request->input('type'); // logo or cover

        if ($type === 'logo' && $request->hasFile('image')) {
            $request->validate(['image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048']);

            if ($restaurant->logo) {
                Storage::disk('public')->delete($restaurant->logo);
            }
            $path = $request->file('image')->store('restaurants/logos', 'public');
            $restaurant->update(['logo' => $path]);

            return response()->json([
                'success' => true,
                'path' => asset('storage/' . $path)
            ]);
        }

        if ($type === 'cover' && $request->hasFile('image')) {
            $request->validate(['image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048']);

            if ($restaurant->cover_image) {
                Storage::disk('public')->delete($restaurant->cover_image);
            }
            $path = $request->file('image')->store('restaurants/covers', 'public');
            $restaurant->update(['cover_image' => $path]);

            return response()->json([
                'success' => true,
                'path' => asset('storage/' . $path)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Invalid request'], 400);
    }

    /**
     * حذف صورة (AJAX)
     */
    public function deleteImage(Restaurant $restaurant, $type)
    {
        $this->checkOwnership($restaurant);

        if ($type === 'logo' && $restaurant->logo) {
            Storage::disk('public')->delete($restaurant->logo);
            $restaurant->update(['logo' => null]);
            return response()->json(['success' => true]);
        }

        if ($type === 'cover' && $restaurant->cover_image) {
            Storage::disk('public')->delete($restaurant->cover_image);
            $restaurant->update(['cover_image' => null]);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Image not found'], 404);
    }

    /**
     * صفحة QR Code
     */
    public function qrCode(Restaurant $restaurant)
    {
        $this->checkOwnership($restaurant);

        return view('admin.restaurants.qrcode', compact('restaurant'));
    }

    /**
     * نسخ القائمة من مطعم آخر
     */
    public function copyMenu(Restaurant $restaurant)
    {
        $this->checkOwnership($restaurant);
        
        $user = Auth::user();

        if ($user->isAdmin()) {
            // المدير يرى كل المطاعم
            $otherRestaurants = Restaurant::where('id', '!=', $restaurant->id)
                ->withCount('categories')
                ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
                ->orderBy('name_ar')
                ->get();
        } else {
            // الموظف يرى فقط المطاعم المخصصة له
            $allowedIds = [];
            
            if ($user->restaurant_id) {
                $userRestaurant = Restaurant::find($user->restaurant_id);
                if ($userRestaurant) {
                    if (!$userRestaurant->parent_id) {
                        // مخصص لمطعم رئيسي
                        $allowedIds = [$userRestaurant->id];
                        $allowedIds = array_merge($allowedIds, $userRestaurant->branches()->pluck('id')->toArray());
                    } else {
                        // مخصص لفرع
                        $parent = $userRestaurant->parent;
                        if ($parent) {
                            $allowedIds = [$parent->id, $userRestaurant->id];
                            $allowedIds = array_merge($allowedIds, $parent->branches()->pluck('id')->toArray());
                        }
                    }
                }
            }
            
            $otherRestaurants = Restaurant::whereIn('id', $allowedIds)
                ->where('id', '!=', $restaurant->id)
                ->withCount('categories')
                ->orderByRaw('CASE WHEN parent_id IS NULL THEN 0 ELSE 1 END')
                ->orderBy('name_ar')
                ->get();
        }
        
        // إضافة علامة للتمييز بين الرئيسي والفرع
        $otherRestaurants = $otherRestaurants->map(function($r) {
            $r->display_name = $r->name_ar;
            if ($r->parent_id) {
                $r->display_name = '↳ ' . $r->name_ar . ' (فرع)';
            }
            return $r;
        });

        return view('admin.restaurants.copy', compact('restaurant', 'otherRestaurants'));
    }

    /**
     * تنفيذ نسخ القائمة (بدون تكرار)
     */
    public function doCopyMenu(Request $request, Restaurant $restaurant)
    {
        $this->checkOwnership($restaurant);

        $request->validate([
            'source_id' => 'required|exists:restaurants,id'
        ]);

        $user = Auth::user();
        $source = Restaurant::find($request->source_id);
        
        if (!$source) {
            return back()->with('error', 'المطعم المصدر غير موجود');
        }
        
        // التحقق من صلاحية الوصول للمصدر
        $hasAccess = false;
        
        if ($user->isAdmin()) {
            // المدير لديه صلاحية على كل المطاعم
            $hasAccess = true;
        } else {
            // الموظف: تحقق من أن المصدر ضمن المطاعم المسموح له بها
            if ($user->restaurant_id) {
                $userRestaurant = Restaurant::find($user->restaurant_id);
                if ($userRestaurant) {
                    // إذا كان مخصص لمطعم رئيسي
                    if (!$userRestaurant->parent_id) {
                        // يمكنه النسخ من نفس المطعم أو فروعه
                        $allowedIds = [$userRestaurant->id];
                        $allowedIds = array_merge($allowedIds, $userRestaurant->branches()->pluck('id')->toArray());
                        $hasAccess = in_array($source->id, $allowedIds);
                    } else {
                        // إذا كان مخصص لفرع، يمكنه النسخ من المطعم الرئيسي وفروعه
                        $parent = $userRestaurant->parent;
                        if ($parent) {
                            $allowedIds = [$parent->id, $userRestaurant->id];
                            $allowedIds = array_merge($allowedIds, $parent->branches()->pluck('id')->toArray());
                            $hasAccess = in_array($source->id, $allowedIds);
                        }
                    }
                }
            }
        }
        
        if (!$hasAccess) {
            return back()->with('error', 'ليس لديك صلاحية النسخ من هذا المطعم');
        }

        $addedCategories = 0;
        $addedItems = 0;
        $skippedCategories = 0;
        $skippedItems = 0;

        foreach ($source->categories as $sourceCategory) {
            // البحث عن قسم بنفس الاسم
            $existingCategory = Category::where('restaurant_id', $restaurant->id)
                ->where(function($q) use ($sourceCategory) {
                    $q->where('name_ar', $sourceCategory->name_ar)
                      ->orWhere('name_en', $sourceCategory->name_en);
                })
                ->first();

            if ($existingCategory) {
                // القسم موجود - نسخ الأصناف الجديدة فقط
                $skippedCategories++;
                $targetCategory = $existingCategory;
            } else {
                // إنشاء قسم جديد
                $targetCategory = $sourceCategory->replicate();
                $targetCategory->restaurant_id = $restaurant->id;
                $targetCategory->save();
                $addedCategories++;
            }

            // نسخ الأصناف
            foreach ($sourceCategory->menuItems as $sourceItem) {
                // البحث عن صنف بنفس الاسم في نفس القسم
                $existingItem = MenuItem::where('restaurant_id', $restaurant->id)
                    ->where('category_id', $targetCategory->id)
                    ->where(function($q) use ($sourceItem) {
                        $q->where('name_ar', $sourceItem->name_ar)
                          ->orWhere('name_en', $sourceItem->name_en);
                    })
                    ->exists();

                if ($existingItem) {
                    $skippedItems++;
                    continue;
                }

                // إنشاء صنف جديد
                $newItem = $sourceItem->replicate();
                $newItem->restaurant_id = $restaurant->id;
                $newItem->category_id = $targetCategory->id;
                $newItem->save();
                
                // نسخ الـ Tags
                if ($sourceItem->tags->count() > 0) {
                    $newItem->tags()->sync($sourceItem->tags->pluck('id'));
                }
                
                $addedItems++;
            }
        }

        $message = "تم نسخ القائمة: ";
        $message .= "أقسام جديدة ($addedCategories)، ";
        $message .= "أصناف جديدة ($addedItems)";
        
        if ($skippedCategories > 0 || $skippedItems > 0) {
            $message .= " | تم تجاهل: أقسام مكررة ($skippedCategories)، أصناف مكررة ($skippedItems)";
        }

        return redirect()->route('admin.restaurants.show', $restaurant)
            ->with('success', $message);
    }

    /**
     * التحقق من الصلاحية - الكل يقدر يعدل
     */
    private function checkOwnership(Restaurant $restaurant)
    {
        // الكل عنده صلاحية - بس الموظف ما يقدر يضيف مطعم جديد
        return;
    }
}