<?php namespace PatrickRose\BoardGameGeek;

use Illuminate\Support\ServiceProvider;

class BoardGameGeekServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('patrickrose/boardgamegeek');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['boardgame'] = $this->app->share(
            function ($app) {
                return new BoardGame;
            }
        );
        $this->app['boardgamegeekapi'] = $this->app->share(
            function ($app) {
                return new BoardGameGeekApi;
            }
        );
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('boardgame', 'boardgamegeekapi');
    }

}
