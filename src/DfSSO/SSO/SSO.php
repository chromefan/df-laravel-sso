<?php
/**
 * Created by PhpStorm.
 * User: luohuanjun
 * Date: 2017/6/15
 * Time: 下午8:13
 */
namespace DfSSO\SSO;

use Illuminate\Support\Facades\Config;

class SSO{

    protected  $api_url;
    protected  $auth_key;
    protected  $user;
    protected  $login_url;
    protected  $base_url;
    protected  $client_id;
    protected  $is_permission;

    public function __construct()
    {
        $this->api_url = Config::get('sso.api_url');
        $this->auth_key = Config::get('sso.auth_key');
        $this->login_url = $this->api_url.'/login';
        $this->base_url = 'http://'.$_SERVER['SERVER_NAME'];
        $this->client_id = Config::get('sso.client_id');
        $this->client_secret = Config::get('sso.client_secret');
        $this->is_permission = Config::get('sso.is_permission');
    }


    /**检测登录
     * @return bool
     */
    public  function isLogin(){
        $user = session()->get($this->auth_key);
        if(isset($user['uid'])){
            return true;
        }
        return false;
    }

    public function isCheckPermission(){
        $this->is_permission = Config::get('sso.is_permission');
        return $this->is_permission;
    }

    /**
     * 本地登录
     * @param $request
     * @return mixed
     */
    public  function login($request){

        $api_url = $this->api_url;
        if(!isset($request->access_token)){

            $post = [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'username'  =>  $request->username,
                'password' => $request->password,
                'grant_type'    =>  'password'
            ];

            $url=$api_url.'/api/oauth/access_token';
            $res=$this->curl_post($url,$post);

            $result=json_decode($res,true);
            if(isset($result['access_token'])){
                $access_token = $result['access_token'];
            }else{
                return response()->json(['error'=>$res]);
            }
        }else{
            $access_token = $request->access_token;
        }

        $url = $api_url.'/api/users/'.$request->username.'?access_token='.$access_token;

        $res = file_get_contents($url);
        $res = json_decode($res,true);
        $auth_user = $res['data'];
        session()->put($this->auth_key,$auth_user);
        session()->save();
        return $auth_user;
    }

    /**
     * 获取用户信息
     * @param string $access_token
     * @param string $user
     * @return string
     */
    public  function getUser($access_token='',$user=''){
        if($this->isLogin()){
            return session()->get($this->auth_key);
        }
        if(!empty($access_token) && !empty($user)){
            $url = $this->api_url.'/api/users/'.$user.'?access_token='.$access_token;
            $res = file_get_contents($url);
            $res = json_decode($res,true);
            $this->user = $res['data'];
            session()->put($this->auth_key,$this->user);
            session()->save();
            return $this->user;
        }
        return '';
    }

    /**
     * 跳转到登录页面
     * @param $actions
     */
    public  function redirectToLogin($actions){

        if($actions=='/'){
            $actions='';
        }
        $callback = $this->base_url.'/'.$actions;
        $url = $this->login_url.'?client_id='.$this->client_id.'&&callback='.urlencode($callback);
        Header("HTTP/1.1 303 See Other");
        Header("Location: $url");
        exit;
    }

    /**
     * 注销退出
     */
    public  function logout(){
        session()->flush();
        $url = $this->api_url.'/logout';
        Header("HTTP/1.1 303 See Other");
        Header("Location: $url");
    }

    public  function curl_post($url, $data = array(), $header = array()) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        if($header)
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Ubuntu Chromium/34.0.1847.116 Chrome/34.0.1847.116');
        $result = curl_exec($ch);

        if($no = curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            return $error;
        }
        curl_close($ch);
        return $result;
    }
}