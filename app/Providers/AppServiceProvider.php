<?php

namespace App\Providers;

use App\Models\Project;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $searchProjects = [];
        if (Schema::hasTable('projects')) {
            $searchProjects = Project::all();
        }
        View::share(['searchProjects' => $searchProjects]);
    }
}
