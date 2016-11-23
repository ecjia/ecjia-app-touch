<?php

class ecjia_touch_user extends RC_Object
{
    
    /**
     * 登录
     */
    public function signin() 
    {
        $data = array(
        	'name' => 'test100',
            'password' => 'a123456',
        );
        $res = ecjia_touch_manager::make()->api(ecjia_touch_api::USER_SIGNIN)->data($data)->send();
        _dump($res,1);
    }
    
    /**
     * 检查是否登录
     */
    public function isSignin() 
    {
        
    }
    
    /**
     * 退出
     */
    public function signout()
    {
        
    }
    
    
}

// end