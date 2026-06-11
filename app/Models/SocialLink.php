<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'platform',
        'url',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function getIconClass()
    {
        return match($this->platform) {
            'instagram' => 'fab fa-instagram',
            'facebook' => 'fab fa-facebook-f',
            'tiktok' => 'fab fa-tiktok',
            'whatsapp' => 'fab fa-whatsapp',
            'twitter' => 'fab fa-x-twitter',
            'snapchat' => 'fab fa-snapchat-ghost',
            'youtube' => 'fab fa-youtube',
            default => 'fas fa-link',
        };
    }

    public function getColor()
    {
        return match($this->platform) {
            'instagram' => '#E4405F',
            'facebook' => '#1877F2',
            'tiktok' => '#000000',
            'whatsapp' => '#25D366',
            'twitter' => '#000000',
            'snapchat' => '#FFFC00',
            'youtube' => '#FF0000',
            default => '#333333',
        };
    }

    public function getFormattedUrl()
    {
        if ($this->platform === 'whatsapp') {
            $phone = preg_replace('/[^0-9]/', '', $this->url);
            return "https://wa.me/{$phone}";
        }
        return $this->url;
    }
}