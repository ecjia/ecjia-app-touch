<?php
/**
* ECJIA 管理中心模版管理程序
*/
defined('IN_ECJIA') or exit('No permission resources.');

class admin_layout extends ecjia_admin {
    private $theme;
	private $theme_dir;

	private $template_code;
	private $stylename_code;

	public function __construct() {
		parent::__construct();

        $curr_theme = $theme_name ? $theme_name : (ecjia::config('template') ? ecjia::config('template') : RC_Config::system('TPL_STYLE'));
		$this->theme_dir  = SITE_THEME_PATH . $curr_theme . DIRECTORY_SEPARATOR;

		$this->template_code = RC_Hook::apply_filters('ecjia_theme_template_code', 'template');
		$this->stylename_code = RC_Hook::apply_filters('ecjia_theme_stylename_code', 'stylename');

        RC_Loader::load_app_class('touch_theme', false);
		$this->theme          = new touch_theme(ecjia::config($this->template_code));
	}

    /**
    * 页面列表
    */
    public function init() {
		$this->admin_priv('touch_layout');
		$template_files = $this->theme->get_template_files();
        foreach ($template_files as $key => $value) {
            $template_files[$key]['File'] = str_replace('.dwt.php', '', $value['File']);
        }
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('布局管理')));
		$this->assign('ur_here'                   , __('布局管理'));
		$this->assign('template_files'        , $template_files);
		$this->assign_lang();
		$this->display('template_layout.dwt');
    }

    /**
     * 编辑页面
     */
    public function edit(){
		$this->admin_priv('touch_layout');

		$template_theme = RC_Hook::apply_filters('ecjia_theme_template', ecjia::config($this->template_code));
		$curr_template  = empty($_REQUEST['template_file']) ? 'index' : $_REQUEST['template_file'];
        // 当前页面使用的库
        $region_name = $this->theme->get_template_region($curr_template . '.dwt.php', false);
		$template_files = $this->theme->get_template_region($curr_template . '.dwt.php', true);
        $page_libs  = $this->theme->get_editable_libs($curr_template, $page_libs[$curr_template]);

        // 所有的模块列表
        $template_libs = $this->theme->get_libraries();

        // 可用库项目列表
        $showcase_libs = $this->theme->get_showcase_libraries();

		$temp_options   = array();
        if (!empty($page_libs)) {
            foreach ($page_libs AS $lib) {
                $val = '/library/' . $lib . '.lbi';
                /* 先排除动态内容 */
                if (!in_array($lib, $this->theme->dyna_libs)) {
                    // $temp_options[$lib]                   = $this->theme->get_setted($val, $template_files);
                    $temp_options[$lib]['name']           = $template_libs[$lib]['Name'];
                    $temp_options[$lib]['desc']           = $template_libs[$lib]['Description'];
                    $temp_options[$lib]['library']        = $val;
                }
            }
        }

        $template_info = $this->theme->get_template_info($this->theme_dir . $curr_template . '.dwt.php');

		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('橱窗布局')));
		$this->assign('ur_here'           , __('橱窗布局'));


        $this->assign('add_showcase', array('href' => RC_Uri::url('touch/admin_showcase/add'), 'text' => '添加自定义橱窗'));

        $this->assign('template_info'        , $template_info);
        $this->assign('region_name'          , $region_name[0]);
        $this->assign('curr_template'        , $curr_template);

        $this->assign('temp_options'         , $temp_options);
		$this->assign('template_libs'        , $showcase_libs);

		$this->assign_lang();
		$this->display('template_layout_info.dwt');
    }

    /**
     * 更新文件
     */
    public function update(){
        if (empty($_REQUEST['template_file'])) $this->showmessage('缺少模板参数，修改失败！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        $curr_template  = $_REQUEST['template_file'];

        $post_regions = array();
        if (!empty($_POST['regions'])) {
            foreach ($_POST['regions'] as $key => $value) {
                $post_regions[] = array(
                    'region'     => $_POST['region_name'],
                    'type'       => 0,
                    'number'     => 0,
                    'library'    => $value,
                    'sort_order' => $key,
                    'id'         => 0
                );
            }
        }

        /* 修改模板文件 */
        $template_file = $this->theme_dir . $curr_template ;
        if (!file_exists($template_file))$template_file .= '.dwt.php';
        /* 将模版文件的内容读入内存 */
        $template_content = file_get_contents($template_file);
        $template_content = str_replace("\xEF\xBB\xBF", '', $template_content);

        $pattern          = '/(<!--\\s*TemplateBeginEditable\\sname="%s"\\s*-->)(.*?)(<!--\\s*TemplateEndEditable\\s*-->)/s';
        $replacement      = "\\1\n%s\\3";
        // $lib_template     = "<!-- #BeginLibraryItem \"%s\" -->\n%s\n <!-- #EndLibraryItem -->\n";
        $lib_template     = "<!-- #BeginLibraryItem \"%s\" --><!-- #EndLibraryItem -->\n";

        $region = $_POST['region_name'];
        $region_content = ''; // 获取当前区域内容
        foreach ($post_regions AS $lib)
        {
            if ($lib['region'] == $region)
            {
                // 这里的代码可以把lbi中的内容读取到dwt模板中
                // $tmp_lib_file = $this->theme_dir . ltrim( $lib['library'], DIRECTORY_SEPARATOR);
                // if (!file_exists($tmp_lib_file))$tmp_lib_file .= '.php';
                // if (!file_exists($tmp_lib_file)) continue;
                // $lib_content     = file_get_contents($this->theme_dir . ltrim( $lib['library'], DIRECTORY_SEPARATOR) . '.php');
                // $lib_content     = preg_replace('/<meta\\shttp-equiv=["|\']Content-Type["|\']\\scontent=["|\']text\/html;\\scharset=.*["|\']>/i', '', $lib_content);
                // $lib_content     = str_replace("\xEF\xBB\xBF", '', $lib_content);
                // $region_content .= sprintf($lib_template, $lib['library'], $lib_content);
                $region_content .= sprintf($lib_template, $lib['library']);
            }
        }
        /* 替换原来区域内容 */
        $template_content = preg_replace(sprintf($pattern, $region), sprintf($replacement , $region_content), $template_content);

        if (file_put_contents($template_file, $template_content))
        {
            // 清除对应的编译文件
            $this->clear_cache_files();
            $this->clear_compiled_files();
            RC_Dir::delete(SITE_CACHE_PATH . 'temp' . DS . 'table_caches');

            $this->showmessage('修改成功!', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS);
        }
        else
        {
            $this->showmessage('修改失败！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
        }
    }
}
