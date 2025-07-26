<?php

   namespace App\Providers;

   use Illuminate\Support\ServiceProvider;
   use App\Services\MellatBank;
   use App\Services\SamanBank;

   class BankServiceProvider extends ServiceProvider
   {
       public function register()
       {
           $this->app->bind(MellatBank::class, function () {
               return new MellatBank();
           });

           $this->app->bind(SamanBank::class, function () {
               return new SamanBank();
           });
       }

       public function boot()
       {
           //
       }
   }
