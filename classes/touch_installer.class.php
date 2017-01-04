<?php
defined('IN_ECJIA') or exit('No permission resources.');

RC_Loader::load_app_class('touch', 'touch', false);
class touch_installer  extends ecjia_installer {

    protected $dependent = array(
        'ecjia.touch'    => '1.0',
    );

    public function __construct() {
        $id = 'ecjia.touch';
        parent::__construct($id);
    }


    public function install() {
        if (!ecjia::config(touch::STORAGEKEY_template, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('hidden', touch::STORAGEKEY_template, '', array('type' => 'hidden'));
        }
        if (!ecjia::config(touch::STORAGEKEY_stylename, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('hidden', touch::STORAGEKEY_stylename, '', array('type' => 'hidden'));
        }

        if (!ecjia::config(touch::STORAGEKEY_pc_url, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('wap', touch::STORAGEKEY_pc_url, '', array('type' => 'text', 'sort_order' => 1));
        }
        if (!ecjia::config(touch::STORAGEKEY_touch_url, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('wap', touch::STORAGEKEY_touch_url, '', array('type' => 'text', 'sort_order' => 2));
        }
        if (!ecjia::config(touch::STORAGEKEY_iphone_download, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('wap', touch::STORAGEKEY_iphone_download, '', array('type' => 'text', 'sort_order' => 3));
        }
        if (!ecjia::config(touch::STORAGEKEY_android_download, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('wap', touch::STORAGEKEY_android_download, '', array('type' => 'text', 'sort_order' => 4));
        }
        if (!ecjia::config(touch::STORAGEKEY_ipad_download, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('wap', touch::STORAGEKEY_ipad_download, '', array('type' => 'text', 'sort_order' => 5));
        }
        if (!ecjia::config(touch::STORAGEKEY_app_icon, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('wap', touch::STORAGEKEY_app_icon, '', array('type' => 'file', 'store_dir' => 'data/', 'sort_order' => 6));
        }
        if (!ecjia::config(touch::STORAGEKEY_app_description, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->insert_config('wap', touch::STORAGEKEY_app_description, '', array('type' => 'text', 'sort_order' => 7));
        }
        if (!ecjia::config(touch::STORAGEKEY_map_qq_key, ecjia::CONFIG_CHECK)) {
        	ecjia_config::instance()->insert_config('wap', touch::STORAGEKEY_map_qq_key, '', array('type' => 'text', 'sort_order' => 8));
        }

        return true;
    }


    public function uninstall() {

        if (ecjia::config(touch::STORAGEKEY_template, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config(touch::STORAGEKEY_template);
        }
        if (ecjia::config(touch::STORAGEKEY_stylename, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config(touch::STORAGEKEY_stylename);
        }

        if (ecjia::config(touch::STORAGEKEY_pc_url, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config(touch::STORAGEKEY_pc_url);
        }
        if (ecjia::config(touch::STORAGEKEY_touch_url, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config(touch::STORAGEKEY_touch_url);
        }
        if (ecjia::config(touch::STORAGEKEY_iphone_download, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config(touch::STORAGEKEY_iphone_download);
        }
        if (ecjia::config(touch::STORAGEKEY_android_download, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config(touch::STORAGEKEY_android_download);
        }
        if (ecjia::config(touch::STORAGEKEY_ipad_download, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config(touch::STORAGEKEY_ipad_download);
        }
        if (ecjia::config(touch::STORAGEKEY_app_icon, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config(touch::STORAGEKEY_app_icon);
        }
        if (ecjia::config(touch::STORAGEKEY_app_description, ecjia::CONFIG_CHECK)) {
            ecjia_config::instance()->delete_config(touch::STORAGEKEY_app_description);
        }

        return true;
    }

}

// end
