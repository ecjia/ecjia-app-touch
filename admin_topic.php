<?php
/**
 * ECJIA 管理中心商店设置
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin_topic extends ecjia_admin {

    public function __construct() {
        parent::__construct();
        RC_Lang::load('brand');
        /* 加载所全局 js/css */
        RC_Script::enqueue_script('bootstrap-placeholder');
        RC_Script::enqueue_script('jquery-validate');
        RC_Script::enqueue_script('jquery-form');
        RC_Script::enqueue_script('smoke');
        RC_Script::enqueue_script('jquery-uniform');
        RC_Style::enqueue_style('uniform-aristo');
        RC_Style::enqueue_style('chosen');
        RC_Script::enqueue_script('jquery-chosen');

        RC_Loader::load_app_func('topic');
        RC_Loader::load_app_func('functions');

        $this->db_brand = RC_Loader::load_app_model('brand_model','goods');
        $this->db_goods = RC_Loader::load_app_model('goods_model','goods');

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('专题页管理'), RC_Uri::url('touch/admin_topic/init')));
    }


    /**
     * 系统
     */
    public function init () {
        $this->admin_priv('touch_topic');

        $ur_here = '专题页管理';
        $goods_add_lang = '添加新专题页';
        ecjia_screen::get_current_screen()->remove_last_nav_here();
        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($ur_here));
        $this->assign('ur_here', $ur_here);

        $topic_page_list = get_topic_list();
        foreach ($topic_page_list as $key => $library ) {
            $topic_list[$key] = $library['File'] . ' - ' . $library['Name'];
        }
        ksort($topic_list);
        $this->assign('topic_page_list', $topic_list);

        $this->assign_lang();
        $this->display('topic_list.dwt');
    }

    /**
     * 系统
     */
    public function add () {
        $this->admin_priv('touch_topic');

        $ur_here = '添加专题页';
        $goods_add_lang = '返回专题页列表';
        $this->assign('ur_here', $ur_here);
        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here($ur_here));
        $this->assign('action_link', array('href' => RC_Uri::url('touch/admin_topic/init', empty($code)), 'text' => $goods_add_lang));
        $topic = get_touch_topic_list();
        $topic_list = array();
        $this->assign('topic_list', $topic_list);
        $this->assign('form_action', RC_Uri::url('touch/admin_topic/insert'));
        $this->assign('topic'               , $topic);
        $this->assign_lang();
        $this->display('topic_info.dwt');
    }

    /**
     * 编辑页面
     */
    public function edit(){
        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('编辑专题页')));
        $this->assign('ur_here'			, __('编辑专题页'));
        $this->assign('action_link', array('href' => RC_Uri::url('touch/admin_topic/init'), 'text' => '返回'));
        $template = $_GET['value'];
        $topic = get_touch_topic_list();
        $library = load_library(trim($template));
        $curr_theme = ecjia::config('template') ? ecjia::config('template') : RC_Config::system('TPL_STYLE');
        $theme_dir = SITE_THEME_PATH . $curr_theme . DIRECTORY_SEPARATOR;
        $library_dir = $theme_dir. 'library' . DIRECTORY_SEPARATOR.'topic'. DIRECTORY_SEPARATOR;
        $library_handle = $library_dir.$template;
        $file = get_library_info($library_handle);

        $db_topic_model = RC_Loader::load_app_model('topic_model');
        $id = $db_topic_model->where(array('template' => $template))->get_field('topic_id');

        $this->assign('id'                  , $id);
        $this->assign('file'                , $file);
        $this->assign('form_action'         , RC_Uri::url('touch/admin_topic/update'));
        $this->assign('template'            , $template);
        $this->assign('topic'               , $topic);
        $this->assign('template_html'       , $library['html']);
        $this->display('topic_info.dwt');
    }

    /**
     * 更新页面
     */
    public function update(){
        $template = $_POST['tpl'];
        $html = $_POST['content'];
        $topic = $_POST['topic_link'];
        $db_topic_model = RC_Loader::load_app_model('topic_model');
        $data = array('template' => $template);
        $db_topic_model->where(array('topic_id' => $topic))->update($data);
        if(update_library($template,stripslashes(htmlspecialchars_decode($html)))){
            return $this->showmessage('更新成功',ecjia_admin::MSGSTAT_SUCCESS| ecjia_admin::MSGTYPE_JSON);
        }
        return $this->showmessage('编辑失败',ecjia_admin::MSGSTAT_ERROR| ecjia_admin::MSGTYPE_JSON);
    }

    /**
     * 新增页面
     */
    public function insert(){
        $name = htmlspecialchars($_POST['topic_name']);
        $desc = htmlspecialchars($_POST['topic_desc']);
        $html = stripslashes($_REQUEST['content']);
        $curr_theme = ecjia::config('template') ? ecjia::config('template') : RC_Config::system('TPL_STYLE');
        $theme_dir = SITE_THEME_PATH . $curr_theme . DIRECTORY_SEPARATOR;
        $library_dir = $theme_dir. 'library' . DIRECTORY_SEPARATOR.'topic'.DIRECTORY_SEPARATOR;
        $content = "<?php \r\n /* \r\n Name:".$name."\r\n Description:".$desc." \r\n */ \r\n defined('IN_ECJIA') or header(\"HTTP/1.0 404 Not Found\");exit('404 Not Found');\r\n ?> \r\n <!-- {extends file=\"touch.dwt.php\"} --> \r\n <!-- {block name=\"con\"} --> \r\n <!-- TemplateBeginEditable name=\"内容区域\" --> \r\n <!-- #BeginLibraryItem \"/library/page_header.lbi\" --> \r\n <!-- #EndLibraryItem --> \r\n <!-- TemplateEndEditable --> \r\n".$html." \r\n <!-- {/block} -->";
        $file_name = rand(1000,9999).'.dwt.php';
        $data = RC_Time::gmtime();
        $file  = $library_dir.$data.$file_name;
        $fp = fopen($file,'x');
        $topic = $_POST['topic_link'];
        $db_topic_model = RC_Loader::load_app_model('topic_model');
        $data = array('template' => $data.$file_name);
        $db_topic_model->where(array('topic_id' => $topic))->update($data);
        if(fwrite($fp,stripslashes(htmlspecialchars_decode($content)))){
            return $this->showmessage('添加成功',ecjia_admin::MSGSTAT_SUCCESS| ecjia_admin::MSGTYPE_JSON);
        }
        return $this->showmessage('添加失败',ecjia_admin::MSGSTAT_ERROR| ecjia_admin::MSGTYPE_JSON);
    }

    /**
     *  删除主题
     */
    public function drop_topic(){
        $template = $_GET['value'];
        $curr_theme = ecjia::config('template') ? ecjia::config('template') : RC_Config::system('TPL_STYLE');
        $theme_dir = SITE_THEME_PATH . $curr_theme . DIRECTORY_SEPARATOR;
        $library_dir = $theme_dir. 'library' . DIRECTORY_SEPARATOR.'topic'. DIRECTORY_SEPARATOR;
        if(!file_exists($library_dir.$template)){
            return $this->showmessage('请指定要删除的橱窗模板',ecjia_admin::MSGTYPE_JSON | ecjia_admin::MSGSTAT_ERROR);
        }else{
            unlink($library_dir.$template);
        }
        return $this->showmessage('文件已成功删除',ecjia_admin::MSGTYPE_JSON | ecjia_admin::MSGSTAT_SUCCESS);
    }
}

// end
