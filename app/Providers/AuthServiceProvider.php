<?php

namespace App\Providers;

use Base;
use App\Policies;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Base\Person::class => Policies\PersonPolicy::class,
        Base\Account::class => Policies\AccountPolicy::class,
        Base\Product::class => Policies\ProductPolicy::class,
        Base\Consultation::class => Policies\ConsultationPolicy::class,
        Base\Prescription::class => Policies\PrescriptionPolicy::class,
        Base\Message::class => Policies\MessagePolicy::class,
        Base\Order::class => Policies\OrderPolicy::class,
        Base\Campaign::class => Policies\CampaignPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function($user, $ability, $args) {
            if (count($args) && $args[0] instanceof \Entity) {
                $ability = strtolower($ability);
                $class   = strtolower(get_class($args[0]));

                if ($user->hasPermissionTo(sprintf('%s.%s', $ability, $class))) {
                    return TRUE;
                }
            }
        });
    }
}
