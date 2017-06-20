<?php
/**
 * Created by PhpStorm.
 * User: luohuanjun
 * Date: 2017/6/20
 * Time: 上午10:39
 */
namespace DfSSO\SSO\Middleware;

use Closure;
use DfSSO\SSO\Facades\SSO;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $actions = $request->route()->uri;
        if($actions=='/'){
            $actions='home';
        }
        if(isset($request->access_token)){
            $access_token = $request->access_token;
            $username = $request->user;
        }else{
            $access_token = $username = '';
        }
        $user = SSO::getUser($access_token,$username);

        if(empty($user['uid'])){
            SSO::redirectToLogin($actions);
        }
        if(empty($user['permission'])){
            SSO::redirectToLogin($actions);
        }
        $permissions=[];
        foreach ($user['permission'] as $v){
            $permissions[] = $v['pvalue'];
        }
        //权限控制
        if($user['is_admin']==1 || $user['is_leader']==1 || in_array('admin',$permissions)){
            return $next($request);
        }elseif(in_array($actions,$permissions)){
            return $next($request);
        }else{
            SSO::redirectToLogin($actions);
        }
    }
}