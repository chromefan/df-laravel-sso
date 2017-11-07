<?php
/**
 * Created by PhpStorm.
 * User: luohuanjun
 * Date: 2017/6/16
 * Time: 下午9:52
 */
return [
    'client_id'=> env('USER_CLIENT_ID'),             //从用户中心注册获取aclient_id
    'client_secret'=> env('USER_CLIENT_SECRET'),           //从用户中心注册获取client_secret
    'api_url'=> env('USER_API_URL'),     //用户中心url
    'auth_key'=>'auth_name',            //session key 存储用户信息
    'is_permission'=>env('USER_IS_PERMISSION'),            //是否需要权限
    'is_api'=>env('USER_IS_API'),            //是否通过API调用
];