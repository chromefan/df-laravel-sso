<?php
/**
 * Created by PhpStorm.
 * User: luohuanjun
 * Date: 2017/6/15
 * Time: 下午8:13
 */
namespace sso;

class SSO{

    public static $api_url;
    public static $auth_key;
    public static $user;
    public static $login_url;
    public static  $base_url;
    public static $client_id;



    public static function setConfig(){
        self::$api_url = env('CLIENT_AUTH_URL');
        self::$auth_key = 'auth_user';
        self::$login_url = self::$api_url.'/login';
        self::$base_url = 'http://'.$_SERVER['SERVER_NAME'];
        self::$client_id = env('CLIENT_ID');
    }
    public static function isLogin(){
        self::setConfig();
        $user = session()->get('auth_user');
        if(isset($user['uid'])){
            return true;
        }
        return false;
    }

    public static function login($request){
        self::setConfig();
        if(!isset($request->access_token)){

            $post = [
                'client_id' => env('CLIENT_ID'),
                'client_secret' => env('CLIENT_SECRET'),
                'username'  =>  $request->username,
                'password' => $request->password,
                'grant_type'    =>  'password'
            ];
            $api_url = self::$api_url;
            $url=$api_url.'/api/oauth/access_token';
            $res=self::curl_post($url,$post);

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
        session()->put(self::$auth_key,json_encode($auth_user));
        return self::$user;
    }
    public static function getUser($access_token='',$user=''){
        if(self::isLogin()){
            return session()->get(self::$auth_key);
        }
        if(!empty($access_token) && !empty($user)){
            $url = self::$api_url.'/api/users/'.$user.'?access_token='.$access_token;
            $res = file_get_contents($url);
            $res = json_decode($res,true);
            self::$user = $res['data'];
            session()->put(self::$auth_key,self::$user);
            session()->save();
            return self::$user;
        }
        return '';

    }
    public static function redirectToLogin($actions){
        SSO::setConfig();
        if($actions=='/'){
            $actions='';
        }
        $callback = self::$base_url.'/'.$actions;
        $url = SSO::$login_url.'?client_id='.self::$client_id.'&&callback='.urlencode($callback);
        Header("HTTP/1.1 303 See Other");
        Header("Location: $url");
        exit;
    }
    public static function logout(){
        session()->flush();
    }

    public static function curl_post($url, $data = array(), $header = array()) {

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