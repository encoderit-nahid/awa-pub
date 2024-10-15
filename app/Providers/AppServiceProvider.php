<?php

namespace App\Providers;

use App\Project;
use Carbon\Carbon;
use App\OperateMenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $current_user = Auth::user();
                View::share('current_user', $current_user);
                View::share('user', $current_user);
                View::share('loggedUser', $current_user);

                $active_project_cnt = Project::where('user_id', $current_user->id)->where('stat', '2')->count();
                View::share('active_project_cnt', $active_project_cnt);
            }
        });
        $permission = OperateMenu::where('type', 'round')->first();
        $vote = OperateMenu::where('type', 'vote')->first();

        View::share('displayPermission', $permission->is_disable);
        View::share('displayPublicVote', $vote->is_votable);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}