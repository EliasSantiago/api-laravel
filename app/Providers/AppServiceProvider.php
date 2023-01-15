<?php

namespace App\Providers;

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
    $this->app->bind(
      'App\Http\Interfaces\Book\IBookCreate',
      'App\Application\Book\BookCreate'
    );
    $this->app->bind(
      'App\Http\Interfaces\Book\IBookShow',
      'App\Application\Book\BookShow'
    );
    $this->app->bind(
      'App\Http\Interfaces\Book\IBookUpdate',
      'App\Application\Book\BookUpdate'
    );
    $this->app->bind(
      'App\Http\Interfaces\Book\IBookDelete',
      'App\Application\Book\BookDelete'
    );
    $this->app->bind(
      'App\Http\Interfaces\Book\IBookIndex',
      'App\Application\Book\BookIndex'
    );
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    //
  }
}
