<?php

namespace App\Providers;

use App\Models\Font;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('*',function($settings){
            $settings->with('gs', DB::table('generalsettings')->find(1));
            $settings->with('pages', DB::table('pages')->whereStatus(1)->get());
            $settings->with('ps', DB::table('pagesettings')->first());
            $settings->with('social', DB::table('socialsettings')->first());
            $settings->with('default_font', Font::where('is_default','=',1)->first());
            $settings->with('defaultCurrency', Session::get('currency') ?  DB::table('currencies')->where('id','=',Session::get('currency'))->first() : DB::table('currencies')->where('is_default','=',1)->first());

        });
        Paginator::useBootstrap();


    }




}

