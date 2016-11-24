<?php

class ecjia_touch_user extends RC_Object
{
    
    const API_USER_COOKIE = 'api_token';
    
    /**
     * 登录
     */
    public function signin() 
    {
        $data = array(
        	'name' => 'test100',
            'password' => 'a123456',
        );
        $api = ecjia_touch_manager::make()->api(ecjia_touch_api::USER_SIGNIN)->data($data);
        $res = $api->run();
        if ( ! $res) {
            return $api->getError();
        }
        
        $sid = array_get($res, 'session.sid');
        
        $minutes = RC_Config::get('cookie.lifetime');
        $response = royalcms('response');
        $response->withCookie(RC_Cookie::make(self::API_USER_COOKIE, $sid, $minutes));
        
        $this->cacheUserinfo($sid, array_get($res, 'user'));
        
        return array_get($res, 'user');
    }
    
    protected function cacheUserinfo($cookieid, $user) 
    {
        $cache_key = 'api_request_user_info::' . $cookieid;
        
        RC_Cache::app_cache_set($cache_key, $user, 'touch');
    }
    
    protected function getCacheUserinfo()
    {
        $cache_key = 'api_request_user_info::' . RC_Cookie::get(self::API_USER_COOKIE);
        
        $data = RC_Cache::app_cache_get($cache_key, 'touch');

        return $data ?: array();
    }
    
    
    /**
     * 检查是否登录
     */
    public function isSignin() 
    {
        $user = $this->getCacheUserinfo();
        if (array_get($user, 'id') > 0 && array_get($user, 'name')) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 退出
     */
    public function signout()
    {
        
    }
    
    /**
     * 获取用户登录凭证
     */
    public function getToken() 
    {
        return RC_Cookie::get(self::API_USER_COOKIE);
    }
    
    public function getUserinfo()
    {
        return $this->getCacheUserinfo();
    }
    
    
}

// end