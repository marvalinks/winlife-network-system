<?php

namespace App\Providers;

use App\Models\Achivement;
use App\Models\Agent;
use App\Observers\AchivementObserver;
use App\Observers\AgentObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //Product::observe(ProductObserver::class);
        Agent::observe(AgentObserver::class);
        Achivement::observe(AchivementObserver::class);
    }
}
