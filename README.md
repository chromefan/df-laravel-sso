
# df-laravel-sso

A simple Laravel packages used to generate payload for the Disqus SSO feature.

## Installation

- Install the package via composer:

`composer require chromefan/df-laravel-sso`

- Add the service provider to `app/config/app.php`:

`DfSSO\SSO\SSOServiceProvider::class,`

- Add the alias to `app/config/app.php`:

`'SSO'=>DfSSO\SSO\Facades\SSO::class,`

- Publish the configuration file:

`php artisan vendor:publish`

## Configuration

Open `config/sso.php` and fill in your Disqus _secret_ and _public_ API keys. You can find those at your [鼎复用户中心](http://usercenter.df.cn/) page.



