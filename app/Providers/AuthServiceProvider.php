<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Category;
use App\Models\MenuItem;
use App\Models\SocialLink;
use App\Policies\CategoryPolicy;
use App\Policies\MenuItemPolicy;
use App\Policies\SocialLinkPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Category::class => CategoryPolicy::class,
        MenuItem::class => MenuItemPolicy::class,
        SocialLink::class => SocialLinkPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
