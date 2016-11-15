<?php
/**
 * ecjia touch manager API管理类
 * @author royalwang
 *
 */
class ecjia_touch_manager extends Ecjia\System\Api\ApiManager
{
    
    
    /**
     * 服务器地址
     * @var serverHost
     */
    const serverHost = '/sites/api/?url=';
     
    public function __construct() {
        $this->header = array(
        	'device_udid' => '',
            'device_client' => 'html5',
            'device_code' => '6004',
            'api_version' => '1.2',
        );
    }
        
    public function serverHost() {
        return RC_Uri::home_url() . static::serverHost;
    }
    
    
    
    
}

// end