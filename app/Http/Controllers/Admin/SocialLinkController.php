<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialLinkController extends Controller
{
    /**
     * تحديث روابط التواصل للمطعم
     */
    public function updateForRestaurant(Request $request, Restaurant $restaurant)
    {
        $platforms = ['instagram', 'facebook', 'twitter', 'tiktok', 'snapchat', 'youtube'];
        
        foreach ($platforms as $platform) {
            $url = $request->input("social.{$platform}");
            
            $existing = $restaurant->socialLinks()->where('platform', $platform)->first();
            
            if ($url) {
                if ($existing) {
                    $existing->update(['url' => $url, 'is_active' => true]);
                } else {
                    SocialLink::create([
                        'restaurant_id' => $restaurant->id,
                        'platform' => $platform,
                        'url' => $url,
                        'is_active' => true,
                    ]);
                }
            } else {
                // حذف إذا فارغ
                if ($existing) {
                    $existing->delete();
                }
            }
        }

        return redirect()->route('admin.restaurants.show', ['restaurant' => $restaurant, '#social'])
            ->with('success', 'تم حفظ روابط التواصل بنجاح');
    }

    public function index()
    {
        $restaurant = Auth::user()->restaurant;
        $socialLinks = $restaurant->socialLinks;
        $platforms = ['instagram', 'facebook', 'tiktok', 'whatsapp', 'twitter', 'snapchat', 'youtube'];
        return view('admin.social.index', compact('socialLinks', 'platforms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform' => 'required|in:instagram,facebook,tiktok,whatsapp,twitter,snapchat,youtube',
            'url' => 'required|string|max:500',
        ]);

        $restaurant = Auth::user()->restaurant;

        // Check if platform already exists
        $existing = $restaurant->socialLinks()->where('platform', $validated['platform'])->first();
        if ($existing) {
            $existing->update(['url' => $validated['url']]);
            return redirect()->back()->with('success', __('messages.social_updated'));
        }

        $validated['restaurant_id'] = $restaurant->id;
        SocialLink::create($validated);

        return redirect()->back()->with('success', __('messages.social_created'));
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        $validated = $request->validate([
            'url' => 'required|string|max:500',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $socialLink->update($validated);

        return redirect()->back()->with('success', __('messages.social_updated'));
    }

    public function destroy(SocialLink $socialLink)
    {
        $socialLink->delete();
        return redirect()->back()->with('success', __('messages.social_deleted'));
    }

    public function toggleStatus(SocialLink $socialLink)
    {
        $socialLink->update(['is_active' => !$socialLink->is_active]);
        return redirect()->back()->with('success', __('messages.status_updated'));
    }
}