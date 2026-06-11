<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Tag;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    /**
     * إنشاء صنف جديد
     */
    public function create(Restaurant $restaurant)
    {
        $categories = $restaurant->categories()->where('is_active', true)->orderBy('sort_order')->get();
        $tags = Tag::active()->ordered()->get();
        
        if ($categories->isEmpty()) {
            return redirect()->route('admin.restaurants.categories.create', $restaurant)
                ->with('error', 'يجب إنشاء قسم أولاً');
        }
        
        return view('admin.items.create', compact('restaurant', 'categories', 'tags'));
    }

    /**
     * حفظ صنف جديد
     */
    public function store(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $validated['restaurant_id'] = $restaurant->id;
        $category = Category::findOrFail($validated['category_id']);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $validated['image'] = $image->storeAs('menu-items', $filename, 'public');
        }

        $validated['is_available'] = $request->boolean('is_available', true);
        $validated['is_featured'] = $request->boolean('is_featured', false);
        $validated['sort_order'] = $validated['sort_order'] ?? ($category->menuItems()->max('sort_order') + 1);

        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $item = MenuItem::create($validated);
        
        if (!empty($tagIds)) {
            $item->tags()->sync($tagIds);
        }

        return redirect()->route('admin.restaurants.show', ['restaurant' => $restaurant, '#items'])
            ->with('success', 'تم إضافة الصنف بنجاح');
    }

    /**
     * تعديل صنف
     */
    public function edit(Restaurant $restaurant, MenuItem $item)
    {
        $categories = $restaurant->categories()->where('is_active', true)->orderBy('sort_order')->get();
        $tags = Tag::active()->ordered()->get();
        $selectedTags = $item->tags->pluck('id')->toArray();
        
        return view('admin.items.edit', compact('restaurant', 'item', 'categories', 'tags', 'selectedTags'));
    }

    /**
     * تحديث صنف
     */
    public function update(Request $request, Restaurant $restaurant, MenuItem $item)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        if ($request->boolean('remove_image') && $item->image) {
            Storage::disk('public')->delete($item->image);
            $validated['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $validated['image'] = $image->storeAs('menu-items', $filename, 'public');
        }

        $validated['is_available'] = $request->boolean('is_available');
        $validated['is_featured'] = $request->boolean('is_featured');

        $tagIds = $validated['tags'] ?? [];
        unset($validated['tags']);

        $item->update($validated);
        $item->tags()->sync($tagIds);

        return redirect()->route('admin.restaurants.show', ['restaurant' => $restaurant, '#items'])
            ->with('success', 'تم تحديث الصنف بنجاح');
    }

    /**
     * حذف صنف
     */
    public function destroy(Restaurant $restaurant, MenuItem $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('admin.restaurants.show', ['restaurant' => $restaurant, '#items'])
            ->with('success', 'تم حذف الصنف بنجاح');
    }

    /**
     * تبديل حالة التوفر
     */
    public function toggleAvailability(Restaurant $restaurant, MenuItem $item)
    {
        $item->update(['is_available' => !$item->is_available]);
        return redirect()->back()->with('success', 'تم تحديث الحالة');
    }

    /**
     * تبديل حالة المميز
     */
    public function toggleFeatured(Restaurant $restaurant, MenuItem $item)
    {
        $item->update(['is_featured' => !$item->is_featured]);
        return redirect()->back()->with('success', 'تم تحديث الحالة');
    }

    /**
     * إعادة ترتيب الأصناف
     */
    public function reorder(Request $request, Restaurant $restaurant)
    {
        $items = $request->input('items', []);
        
        if (empty($items) || !is_array($items)) {
            return response()->json(['success' => false, 'message' => 'Invalid data'], 400);
        }

        foreach ($items as $index => $itemId) {
            MenuItem::where('id', (int)$itemId)
                ->where('restaurant_id', $restaurant->id)
                ->update(['sort_order' => $index + 1]);
        }

        return response()->json(['success' => true]);
    }
}