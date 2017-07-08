
# df-laravel-sso

这是一个基于oauth2.0协议在laravel框架下的SSO登录客户端组件

## 安装

- Install the package via composer:

`composer require chromefan/df-laravel-sso`

- Add the service provider to `app/config/app.php`:

`DfSSO\SSO\SSOServiceProvider::class,`

- Add the alias to `app/config/app.php`:

`'SSO'=>DfSSO\SSO\Facades\SSO::class,`

- Add the Middleware to `app/Http/Kernel.php`:

`$middleware[`
     `\Illuminate\Session\Middleware\StartSession::class,`
    
 `]`
 `$routeMiddleware[`
     `permission' => \DfSSO\SSO\Middleware\Permission::class,`
  `]`

- Publish the configuration file:

`php artisan vendor:publish`

## 配置

Open `config/sso.php` ,需要在 [鼎复用户中心](http://usercenter.df.cn/) 中注册app，获取client_id,
和client_secret。

打开`routes/web.php`
将需要加入控制的路由放入permission组

`Route::group(['middleware' => 'permission'],function (){}`

## 权限控制

1、首先在[鼎复用户中心](http://usercenter.df.cn/) 中注册app，并创建角色、权限，并给用户赋予角色；

2、权限值pvalue必须与要控制的路由route名一一对应'

3、如果需要管理员权限，请设置权限值为'admin'或者将该用户设为leader或者用户中心管理员'



## 使用示例

1、检测登录

`$user = SSO::isLogin()`

2、获取用户信息

`$user = SSO::getUser()`

3、注销退出

`SSO::logout()`






