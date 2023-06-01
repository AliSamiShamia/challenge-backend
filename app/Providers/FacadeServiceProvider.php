<?php

namespace App\Providers;

use App\Services\Facades\FArticle;
use App\Services\Facades\FBase;

use App\Services\Facades\FSource;
use App\Services\Facades\FUser;
use App\Services\Interfaces\IArticle;
use App\Services\Interfaces\IBase;

use App\Services\Interfaces\ISource;
use App\Services\Interfaces\IUser;
use Illuminate\Support\ServiceProvider;

class FacadeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(IBase::class, FBase::class);
        $this->app->singleton(IArticle::class, FArticle::class);
        $this->app->singleton(IUser::class, FUser::class);
        $this->app->singleton(ISource::class, FSource::class);
    }
}
