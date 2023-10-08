<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $parentCategories = Category::where('parent_id',0)->get();
        $children = Category::where('parent_id','!=',0)->get();
        View::share(['parentCategories'=>$parentCategories,'children'=>$children]);
        Paginator::defaultView('home.sections.pagination');
    }
}
