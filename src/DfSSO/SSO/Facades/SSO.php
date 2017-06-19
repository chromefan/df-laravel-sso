<?php
/**
 * Created by PhpStorm.
 * User: luohuanjun
 * Date: 2017/6/19
 * Time: 下午8:10
 */
namespace DfSSO\SSO\Facades;
use Illuminate\Support\Facades\Facade;

class SSO extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sso';
    }
}