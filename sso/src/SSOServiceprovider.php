<?php
/**
 * Created by PhpStorm.
 * User: luohuanjun
 * Date: 2017/6/16
 * Time: 下午9:50
 */
namespace df\sso\src;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
class SSOServiceprovider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    public function boot()
    {
        // this for conig
        $this->publishes([
            __DIR__ . '/config/sso.php' => config_path('sso.php'),
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/sso.php', 'sso'
        );
    }
}