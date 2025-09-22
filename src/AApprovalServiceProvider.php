<?php

namespace Aurorawebsoftware\AApproval;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AApprovalServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/approvals.php' => config_path('approvals.php'),
        ], 'aapproval-config');

        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations'),
        ], 'aapproval-migrations');

        Gate::define('aapproval.direct', function ($user, $modelClass = null) {
            return $user->hasPermissionTo('aapproval.direct')
                || $user->hasPermissionTo('aapproval.direct:' . $modelClass);
        });
    }

    public function register() {}
}
