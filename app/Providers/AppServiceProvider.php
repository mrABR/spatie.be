<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Spatie\Flash\Flash;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        dump('somewhere in vendor');
        Model::unguard();
dump(app());
        Gate::define('viewMailcoach', function ($user = null) {
            return optional($user)->is_admin;
        });

        Flash::levels([
            'success' => 'success',
            'error' => 'error',
        ]);
    }
}
