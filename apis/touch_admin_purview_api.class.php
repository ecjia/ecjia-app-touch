<?php
defined('IN_ECJIA') or exit('No permission resources.');
/**
 * 后台权限API
 * @author royalwang
 *
 */
class touch_admin_purview_api extends Component_Event_Api {

    public function call(&$options) {
        $purviews = array(
            array('action_name' => __('微商城商店设置'), 'action_code' => 'touch_shop_config', 'relevance'   => ''),
            array('action_name' => __('微商城快捷菜单'), 'action_code' => 'touch_navigator', 'relevance'   => ''),
            array('action_name' => __('微商城专题管理'), 'action_code' => 'touch_topic', 'relevance'   => ''),
            array('action_name' => __('微商城橱窗布局'), 'action_code' => 'touch_layout', 'relevance'   => ''),
            array('action_name' => __('微商城橱窗管理'), 'action_code' => 'touch_showcase', 'relevance'   => ''),
        );

        return $purviews;
    }
}

// end
