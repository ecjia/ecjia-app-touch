<?php
/**
* ECJIA 管理中心模版管理程序
*/
defined('IN_ECJIA') or exit('No permission resources.');

class admin_showcase extends ecjia_admin {


    public function __construct() {
        parent::__construct();

        RC_Loader::load_app_func('showcase','touch');
        RC_Style::enqueue_style('chosen');
        RC_Script::enqueue_script('smoke');
        RC_Script::enqueue_script('jquery-form');
        RC_Script::enqueue_script('jquery-chosen');
        RC_Script::enqueue_script('bootstrap-placeholder');
        RC_Script::enqueue_script('jquery-uniform');
        RC_Style::enqueue_style('uniform-aristo');
        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('微商城'), RC_Uri::url('@admin_template/init')));
    }

    /**
    * 模版列表
    */
    public function init() {
        $this->admin_priv('library_manage');

        RC_Script::enqueue_script('smoke');
        RC_Script::enqueue_script('ecjia-utils');

        $arr_library = array();
        $libraries =get_libraries();
        foreach ($libraries as $key => $library ) {
            $arr_library[$key] = $library['File'] . ' - ' . $library['Name'];
        }
        ksort($arr_library);
        $curr_library  = key($arr_library);
        $lib = load_library($curr_library);

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('自定义橱窗')));

        $this->assign('ur_here'			, __('自定义橱窗'));
        $this->assign('libraries'		, $arr_library);
        $this->assign('library_html'	, $lib['html']);
        $this->assign('action_link', array('href' => RC_Uri::url('touch/admin_showcase/add'), 'text' => '添加自定义橱窗'));
        $this->assign_lang();
        $this->display('template_library.dwt');
    }

    /**
     * 模版列表
     */
    public function add() {
        $this->admin_priv('library_manage');

        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('添加自定义橱窗')));

        $this->assign('ur_here'			, __('添加自定义橱窗'));
        $this->assign('form_url'        , RC_Uri::url('touch/admin_showcase/insert'));
        $this->assign('action_link', array('href' => RC_Uri::url('touch/admin_showcase/init'), 'text' => '返回橱窗列表'));
        $this->assign_lang();
        $this->display('template_info.dwt');
    }

    /**
     * 编辑页面
     */
    public function edit(){
        ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('编辑自定义橱窗')));
        $this->assign('ur_here'			, __('编辑自定义橱窗'));
        $this->assign('action_link', array('href' => RC_Uri::url('touch/admin_showcase/init'), 'text' => '返回'));

        $template = str_replace(DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR, '', $_GET['value']);
        $library = load_library(rtrim($template, '.lbi'));
        $curr_theme = ecjia::config('template') ? ecjia::config('template') : RC_Config::system('TPL_STYLE');
        $theme_dir = SITE_THEME_PATH . $curr_theme . DIRECTORY_SEPARATOR;
        $library_dir = $theme_dir. 'library' . DIRECTORY_SEPARATOR;
        $library_handle = $library_dir.$template.'.lbi.php';
        $file = get_library_info($library_handle);
        $this->assign('file'            , $file);
        $this->assign('form_url'        , RC_Uri::url('touch/admin_showcase/update'));
        $this->assign('template'        , $template);
        $this->assign('template_html'   , $library['html']);
        $this->display('template_info.dwt');
    }

    /**
     * 更新文件
     */
    public function update(){
        $template = $_POST['tpl'];
        $html = stripslashes($_POST['html']);
        if(update_library($template,$html)){
            return $this->showmessage('更新成功',ecjia_admin::MSGSTAT_SUCCESS| ecjia_admin::MSGTYPE_JSON);
        }
        return $this->showmessage('编辑失败',ecjia_admin::MSGSTAT_ERROR| ecjia_admin::MSGTYPE_JSON);
    }

    /**
     * 增加自定义橱窗
     */
    public function insert(){
        $name = htmlspecialchars($_POST['template_name']);
        $desc = htmlspecialchars($_POST['template_desc']);
        $html = stripslashes($_REQUEST['html']);
        $curr_theme = ecjia::config('template') ? ecjia::config('template') : RC_Config::system('TPL_STYLE');
        $theme_dir = SITE_THEME_PATH . $curr_theme . DIRECTORY_SEPARATOR;
        $library_dir = $theme_dir. 'library' . DIRECTORY_SEPARATOR;

        $content = "<?php \r\n /* \r\n Name:".$name."\r\n Description:".$desc." \r\n */ \r\n defined('IN_ECJIA') or header(\"HTTP/1.0 404 Not Found\");exit('404 Not Found');\r\n ?> \r\n".$html;
        $file_name = RC_Time::gmtime() . rand(1000,9999) . '.lbi.php';
        $file  = $library_dir.'showcase_'.$file_name;
        $fp = fopen($file,'x');
        if(fwrite($fp,$content)){
            return $this->showmessage('添加成功',ecjia_admin::MSGSTAT_SUCCESS| ecjia_admin::MSGTYPE_JSON);
        }
        return $this->showmessage('添加失败',ecjia_admin::MSGSTAT_ERROR| ecjia_admin::MSGTYPE_JSON);

    }

    /**
     * 编辑页面
     */
    public function delete(){
        $template = $_GET['value'];
        $curr_theme = ecjia::config('template') ? ecjia::config('template') : RC_Config::system('TPL_STYLE');
        $theme_dir = SITE_THEME_PATH . $curr_theme . DIRECTORY_SEPARATOR;
        $library_dir = $theme_dir. 'library' . DIRECTORY_SEPARATOR;
        if(!file_exists($library_dir.$template.'.lbi.php')){
            return $this->showmessage('请指定要删除的橱窗模板',ecjia_admin::MSGTYPE_JSON | ecjia_admin::MSGSTAT_ERROR);
        }else{
            unlink($library_dir.$template.'.lbi.php');
        }
        return $this->showmessage('文件已成功删除',ecjia_admin::MSGTYPE_JSON | ecjia_admin::MSGSTAT_SUCCESS);
    }
}
