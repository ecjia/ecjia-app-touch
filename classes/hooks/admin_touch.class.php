<?php
defined('IN_ECJIA') or exit('No permission resources.');

class touch_admin_hooks {
	
   public static function append_admin_setting_group($menus) 
   {
       $setting = ecjia_admin_setting::singleton();
       
       $menus[] = ecjia_admin::make_admin_menu('nav-header', 'H5应用', '', 10)->add_purview(array('mobile_config_manage', 'mobile_manage'));
       $menus[] = ecjia_admin::make_admin_menu('wap', $setting->cfg_name_langs('wap'), RC_Uri::url('setting/shop_config/init', array('code' => 'wap')), 11)->add_purview('shop_config')->add_icon('fontello-icon-tablet');
       
       return $menus;
   }
}

RC_Hook::add_action( 'append_admin_setting_group', array('touch_admin_hooks', 'append_admin_setting_group') );

// end