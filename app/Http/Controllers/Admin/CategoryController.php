<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * إنشاء قسم جديد
     */
    public function create(Restaurant $restaurant)
    {
        return view('admin.categories.create', compact('restaurant'));
    }

    /**
     * حفظ قسم جديد
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['restaurant_id'] = $restaurant->id;

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? ($restaurant->categories()->max('sort_order') + 1);

        Category::create($validated);

        return redirect()->route('admin.restaurants.show', $restaurant)
            ->with('success', 'تم إضافة القسم بنجاح');
    }

    /**
     * تعديل قسم
     */
    public function edit(Restaurant $restaurant, Category $category)
    {
        return view('admin.categories.edit', compact('restaurant', 'category'));
    }

    /**
     * تحديث قسم
     */
    public function update(Request $request, Restaurant $restaurant, Category $category)
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'icon' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // حذف الصورة إذا طُلب
        if ($request->has('remove_image') && $category->image) {
            Storage::disk('public')->delete($category->image);
            $validated['image'] = null;
        }

        $validated['is_active'] = $request->boolean('is_active');

        $category->update($validated);

        return redirect()->route('admin.restaurants.show', $restaurant)
            ->with('success', 'تم تحديث القسم بنجاح');
    }

    /**
     * حذف قسم
     */
    public function destroy(Restaurant $restaurant, Category $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.restaurants.show', $restaurant)
            ->with('success', 'تم حذف القسم بنجاح');
    }

    /**
     * إعادة ترتيب الأقسام
     */
    public function reorder(Request $request, Restaurant $restaurant)
    {
        $categories = $request->input('categories', []);
        
        if (empty($categories) || !is_array($categories)) {
            return response()->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        foreach ($categories as $index => $categoryId) {
            Category::where('id', (int)$categoryId)
                ->where('restaurant_id', $restaurant->id)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}