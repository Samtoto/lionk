<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('edit-blogs', function($user, $blog) {
            return $user->id === $blog->user_id;
        });

        Gate::define('update-blogs', function($user, $blog) {
            return $user->id === $blog->user_id;
        });

        Gate::define('delete-blogs', function ($user, $blog) {
            return $user->id === $blog->user_id;
        });

        Gate::define('edit-comments', function ($user, $comment) {
            return $user->id === $comment->user_id;
        });

        Gate::define('update-comments', function ($user, $comment) {
            return $user->id === $comment->user_id;
        });

        Gate::define('delete-comments', function ($user, $comment) {
            return $user->id === $comment->user_id;
        });

        Gate::define('create-community', function ($user) {
            // todo user.coins >? 50
            return true;
        });

        //
    }
}
