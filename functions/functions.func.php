<?php
/**
 * touch共用功能加载
 */
function touch_common_loading() {

	/* 商店关闭了，输出关闭的消息 */
	if (ecjia::config('wap_config') == 0) {
		RC_Hook::do_action('ecjia_shop_closed');
	}

    RC_Loader::load_app_config('constant', 'touch');
    // RC_Loader::load_app_func('front_global','touch');
    // RC_Loader::load_app_class('touch_page', 'touch', false);
    RC_Loader::load_theme('extras/class/touch/touch_page.class.php');

    RC_Lang::load('touch/common');
    RC_Lang::load('touch/user');
    //判断是否显示头部和底部
    if (!empty($_GET['hidenav']) && !empty($_GET['hidetab'])) {
        RC_Cookie::set('hideinfo', 1, array('expire' => 360000));
    }elseif (isset($_GET['hidenav']) && isset($_GET['hidetab']) && $_GET['hidenav'] == 0 && $_GET['hidetab'] == 0) {
        RC_Cookie::delete('hideinfo');
    }

    if (RC_Cookie::get('hideinfo')) {
        ecjia_front::$view_object->assign('hideinfo', 1);
    }

    if (!empty($_GET['hidenav'])) {
        ecjia_front::$view_object->assign('hidenav', intval($_GET['hidenav']));
    }
    if (!empty($_GET['hidetab'])) {
        ecjia_front::$view_object->assign('hidetab', intval($_GET['hidetab']));
    }
    $stylename_code = RC_Hook::apply_filters('ecjia_theme_stylename_code', 'stylename');
    $curr_style = ecjia::config($stylename_code) ? 'style_'.ecjia::config($stylename_code).'.css' : 'style.css';
    ecjia_front::$view_object->assign('curr_style', $curr_style);

    // 提供APP下载广告的配置项
    $shop_app_icon = ecjia::config('shop_app_icon');
    !empty($shop_app_icon) && ecjia_front::$controller->assign('shop_app_icon', RC_Upload::upload_url() . '/' . $shop_app_icon);
}



RC_Hook::add_action('ecjia_front_finish_launching', 'touch_common_loading');

RC_Loader::load_app_class('touch', 'touch', false);

RC_Hook::add_filter('ecjia_theme_template_code', function() {
    return touch::STORAGEKEY_template;
});
RC_Hook::add_filter('ecjia_theme_stylename_code', function() {
    return touch::STORAGEKEY_stylename;
});
RC_Hook::add_filter('page_title_suffix', function ($suffix) {
	return ;
});

/**
 * 设置api session id
 * @param string $session_id session id
 * @return session_id
 */
function set_touch_session_id($session_id) {
    if (isset($_GET['token']) && !empty($_GET['token'])) {
        return $_GET['token'];
    }
    return ;
}
RC_Hook::add_filter('ecjia_front_session_id', 'set_touch_session_id');

