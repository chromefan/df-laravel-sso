<?php
/**
 * Created by PhpStorm.
 * User: luohuanjun
 * Date: 2017/6/16
 * Time: 下午9:50
 */
namespace DfSSO\SSO;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class SSOServiceProvider extends ServiceProvider
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
            __DIR__ . '/../../config/sso.php' => config_path('sso.php'),
        ]);
    }

    public function register()
    {
        // Register 'disqus-sso' instance container to our DisqusAuth object
        $this->app->singleton('sso',function ($app) {
            return new SSO;
        });
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['sso'];
    }
}