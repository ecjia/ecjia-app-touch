<?php
/**
 * ECJIA 程序说明
 */
defined('IN_ECJIA') or exit('No permission resources.');

class admin_navigator extends ecjia_admin {
	private $nav_type = 'touch';
	public function __construct() {
		parent::__construct();

		if (!ecjia::config('navigator_data',2)) {
			$this->config->insert_config('hidden', 'navigator_data', serialize(array(array('type'=>'top','name'=>'顶部'),array('type'=>'middle','name'=>'中间'),array('type'=>'bottom','name'=>'底部'))), array('type' => 'hidden'));
		}
		RC_Style::enqueue_style('chosen');
		RC_Script::enqueue_script('smoke');
		RC_Script::enqueue_script('jquery-form');
		RC_Script::enqueue_script('jquery-chosen');
		RC_Script::enqueue_script('bootstrap-placeholder');
		RC_Script::enqueue_script('jquery-uniform');
		RC_Style::enqueue_style('uniform-aristo');
	}


	/**
	 * 菜单列表
	 */
	public function init() {
		$this->admin_priv('touch_navigator');

		$showstate = !empty($_GET['showstate'])?strip_tags(htmlspecialchars($_GET['showstate'])):'';
		RC_Script::enqueue_script('ecjia-touch-admin_nav', RC_App::apps_url('statics/js/ecjia-touch-admin_nav.js', __FILE__), array('ecjia-admin'), false, true);
		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('微商城菜单管理')));

		$this->assign('ur_here', __('微商城菜单管理'));

		$this->assign('full_page',  1);
		$this->assign('showstate', $showstate);
		$this->assign('_FILE_STATIC', RC_Uri::admin_url('statics/'));

		$nav_list = unserialize(ecjia::config('navigator_data'));

		// 获取菜单名称
		$nav_name = '';
		if (!empty($nav_list)) {
			foreach ($nav_list as $v) {
				if ($v['type'] == $this->nav_type) {
					$nav_name = $v['name'];
				}
			}
		} else {
			$this->add_nav_list();
			exit();
		}

		//如果菜单不存在，报错
		if (empty($nav_name)) {
			$nav_list[] = array(
				'type' => $this->nav_type,
				'name' => 'ECJiaTouch'
			);
			ecjia_config::instance()->write_config('navigator_data', serialize($nav_list));
			$nav_name = 'ECJiaTouch';
		}
		$tmp_navdb = $this->get_nav();

		$pagenav = $this->get_pagenav();

		$this->assign('nav_type',     $this->nav_type);
		$this->assign('nav_name',     $nav_name);
		$this->assign('navdb',        $tmp_navdb);
		$this->assign('pagenav',      $pagenav);
		$this->display('navigator.dwt');
	}

	/**
	 * 编辑菜单内容
	 */
	public function edit_nav() {
		$db_nav = RC_Loader::load_model('nav_model');
		$navlist_del = strip_tags ( htmlspecialchars($_POST ['navlist_del']) );
		if (! empty ( $navlist_del )) {
			$nav_del = explode ( ',', $navlist_del );
			foreach ( $nav_del as $v ) {
				if (!empty($v)) {
					$url = $db_nav->where(array('id' => $v))->get_field('url');
					$temp_url = str_replace(array('&icon='), '&var|', $url);
					$tmp_arr = explode('&var|', $temp_url);
					$str = strstr($tmp_arr[1],'data');
					if(!empty($str)){
						$path = RC_Upload::upload_path().$tmp_arr[1];
						$disk = RC_Filesystem::disk();
						$disk->delete($path);
					}
					$db_nav->where ('id='.$v.'')->delete();
				}
			}
		}
		$navlist = $_POST['nav_list'];
		if (!empty($navlist)) {
			foreach ($navlist as $k=>$v) {
				$v['url'] = $v['url'] . '&icon=' . $v['icon'] ;
				unset($v['icon']);
				unset($v['bgc']);

				if ($v['id'] == 'new') {
					unset($v['id']);
					$v['type'] = $_POST['nav_type'];
					$db_nav->insert($v);
				} else {
					$db_nav->where('id = '.$v['id'].'')->update($v);
				}
			}
		}

		$navlist_name = strip_tags(htmlspecialchars($_POST['navlist_name']));
		$type = strip_tags(htmlspecialchars($_POST['nav_type']));
		$list = unserialize(ecjia::config('navigator_data'));

		if (empty($type)) {
			die(__('菜单名不能为空'));
		}

		//判断是否重复
		foreach ($list as $k => $v) {
			if ($type == $v['type'])$list[$k]['name'] = $navlist_name;
			if ($type == $v['name'] && $type != $v['type'])die(__('重复了'));
		}

		//插入新菜单
		if (ecjia_config::instance()->write_config('navigator_data', serialize($list))) {
			$this->showmessage('成功保存修改！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('pjaxurl'=>RC_Uri::url('touch/admin_navigator/init')));
		} else {
			$this->showmessage('保存修改失败！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
		}
	}

	/**
	 * 编辑菜单内容
	 */
	public function update_nav() {
		$db_nav = RC_Loader::load_model('nav_model');

		$icon = $_FILES['iconimg'];
		$upload = RC_Upload::uploader('image', array('save_path' => '/data/assets/'.ecjia::config('template'), 'auto_sub_dirs' => true));
		if(!empty($icon)){
			/* 检查上传的文件类型是否合法 */
			if (!$upload->check_upload_file($icon)) {
				$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
			}else{
				$image_info = $upload->upload($_FILES['iconimg']);
				if (empty($image_info)) {
					$this->showmessage($upload->error(), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_ERROR);
				}
				$icon_img = $upload->get_position($image_info);
			}
		}else{
			$icon_img = empty($_POST['icon'])? '' : htmlspecialchars($_POST['icon']);
		}
		$url 		= htmlspecialchars($_POST['url']);
		$ifshow 	= htmlspecialchars($_POST['ifshow']) == 'on'? 1 : 0 ;
		$opennew 	= htmlspecialchars($_POST['opennew']) == 'on' ? 1 : 0;
		$vieworder 	= htmlspecialchars($_POST['vieworder']);
		$name 		= htmlspecialchars($_POST['name']);
		$id 		= intval($_POST['id']);
		$data 		= array(
			'id' 		=> $id,
			'url' 		=> $url.'&icon='.$icon_img,
			'ifshow' 	=> $ifshow,
			'opennew' 	=> $opennew,
			'vieworder' => $vieworder,
			'type' 		=> touch,
			'name' 		=> $name
		);
		$count = $db_nav->where(array('id' => $id))->count();
		if($count > 0){
			$url = $db_nav->where(array('id' => $id))->get_field('url');
			$temp_url = str_replace(array('&icon='), '&var|', $url);
			$tmp_arr = explode('&var|', $temp_url);
			$str = strstr($tmp_arr[1],'data');
			if(!empty($str) && $icon){
// 				$path = RC_Upload::upload_path().$tmp_arr[1];
// 				unlink($path);
				$upload->remove($tmp_arr[1]);
			}
			$db_nav->where(array('id' => $id))->update($data);
		}else{
			$db_nav->insert($data);
		}
		$this->showmessage('保存导航成功！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS,array('iconimg' =>$icon_img));
	}

	/**
	 * 删除图片
	 */
	public function del_icon(){
		$id = $_GET['id'];
		$db_nav = RC_Loader::load_model('nav_model');
		$url = $db_nav->where(array('id' => $id))->get_field('url');
		$temp_url = str_replace(array('&icon='), '&var|', $url);
		$tmp_arr = explode('&var|', $temp_url);
		$url = $tmp_arr[0].'&icon=';
		$data = array('url' => $url);
		$db_nav->where(array('id' =>$id))->update($data);
		$str = strstr($tmp_arr[1],'data');
		if(!empty($str)){
			$path = RC_Upload::upload_path().$tmp_arr[1];
// 			unlink($path);
			$disk = RC_Filesystem::disk();
			$disk->delete($path);
		}
		$this->showmessage('图片删除成功！', ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS,array('pjaxurl'=>RC_Uri::url('touch/admin_navigator/init')));
	}
	/**
	 * 菜单栏列表Ajax
	 */
	public function query() {
		global $ecs, $db, $_CFG, $sess;

		$navdb = $this->get_nav();
		$this->assign('navdb',    $navdb['navdb']);
		$this->assign('filter',       $navdb['filter']);
		$this->assign('record_count', $navdb['record_count']);
		$this->assign('page_count',   $navdb['page_count']);
		$this->showmessage($this->fetch_string('navigator.dwt'), ecjia::MSGTYPE_JSON | ecjia::MSGSTAT_SUCCESS, array('filter' => $navdb['filter'], 'page_count' => $navdb['page_count']));
	}

	/**
	 * 菜单栏删除
	 */
	public function del() {
		$db_nav = RC_Loader::load_model('nav_model');
		$id = intval($_GET['id']);
		$row = $db_nav->field('ctype,cid,type')->find(array('id' => $id));
		if ($row['type'] == 'middle' && $row['ctype'] && $row['cid']) {
			$this->set_show_in_nav($row['ctype'], $row['cid'], 0);
		}

		$this->db->where('id='.$id.'')->delete();
		$this->redirect(RC_Uri::url('@navigator/init'));
	}

	/**
	 * 编辑排序
	 */
	public function edit_sort_order() {
		$this->admin_priv('nav', ecjia::MSGTYPE_JSON);

		$id    = intval($_POST['id']);
		$order = trim($_POST['val']);
	}

// 	/**
// 	 * 切换是否显示
// 	 */
// 	public function toggle_ifshow() {

// 		$id = intval($_POST['id']);
// 		$val = intval($_POST['val']);

// 		$row = $this->db_nav->field('type,ctype,cid')->find('id = '.$id.'');

// 		if ($row['type'] == 'middle' && $row['ctype'] && $row['cid']) {
// 			$this->set_show_in_nav($row['ctype'], $row['cid'], $val);
// 		}

// 		if ($this->nav_update($id, array('ifshow' => $val)) != false) {
// 			make_json_result($val);
// 		} else {
// 			make_json_error($db->error());
// 		}
// 	}

	/**
	 * 切换是否新窗口
	 */
// 	public function toggle_opennew() {

// 		$id = intval($_POST['id']);
// 		$val = intval($_POST['val']);

// 		if ($this->nav_update($id, array('opennew' => $val)) != false) {
// 			make_json_result($val);
// 		} else {
// 			make_json_error($db->error());
// 		}
// 	}



	private function get_nav()
	{
		$db = RC_Loader::load_model('nav_model');
		$filter['sort_by']      = empty($_REQUEST['sort_by']) ? 'type DESC, vieworder' : 'type DESC, '.trim($_REQUEST['sort_by']);
		$filter['sort_order']   = empty($_REQUEST['sort_order']) ? 'ASC' : trim($_REQUEST['sort_order']);
		$result = $db->field('id, name, ifshow, vieworder, opennew, url, type')->where(array('type' => $this->nav_type))->order($filter['sort_by'] . ' ' . $filter['sort_order'])->select();

		$navdb = array();
		if (!empty($result)) {
			foreach($result as $k=>$v) {
				$tmp_str = str_replace(array('&icon=','&bgc='), '&var|', $v['url']);
				$tmp_arr = explode('&var|', $tmp_str);
				$v['url'] = $tmp_arr[0];
				$v['icon'] = $tmp_arr[1];
				$v['icon_path'] = RC_Upload::upload_url().'/'.$tmp_arr[1];
				$v['bgc'] = $tmp_arr[2];
				$navdb[] = $v;
			}
		}
		return $navdb;
	}

	/**
	 * 获得页面列表
	 * @return multitype:multitype:string  multitype:string Ambigous <string, mixed>
	 */
	private function get_pagenav() {
		return $sysmain = array(
			// array(
			// 	'name'	=> __('全部分类'),
			// 	'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('goods/category/top_all')),
			// 	'icon'	=> 'iconfont icon-sort'
			// ),
			// array(
			// 	'name'	=> __('我的订单'),
			// 	'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/user_order/order_list')),
			// 	'icon'	=> 'iconfont icon-calendar'
			// ),
			// array(
			// 	'name'	=> __('个人中心'),
			// 	'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/index/init')),
			// 	'icon'	=> 'iconfont icon-gerenzhongxin'
			// ),
			// array(
			// 	'name'	=> __('我的收藏'),
			// 	'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/user_collection/collection_list')),
			// 	'icon'	=> 'iconfont icon-shoucang'
			// ),
			// array(
			// 	'name'	=> __('浏览记录'),
			// 	'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/index/history')),
			// 	'icon'	=> 'iconfont icon-attention'
			// ),
			// array(
			// 	'name'	=> __('资金管理'),
			// 	'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/user_account/account_detail')),
			// 	'icon'	=> 'iconfont icon-qianbao'
			// ),
			// array(
			// 	'name'	=> __('客户服务'),
			// 	'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/user_package/service')),
			// 	'icon'	=> 'iconfont icon-kefu'
			// ),
			// array(
			// 	'name'	=> __('购物车'),
			// 	'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('cart/index/init')),
			// 	'icon'	=> 'iconfont icon-gouwuche2'
			// )
			array(
				'name'	=> __('全部分类'),
				'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('goods/category/top_all')),
				'icon'  => ''
			),
			array(
				'name'	=> __('我的订单'),
				'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/user_order/order_list')),
				'icon'  => ''
			),
			array(
				'name'	=> __('个人中心'),
				'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/index/init')),
				'icon'  => ''
			),
			array(
				'name'	=> __('我的收藏'),
				'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/user_collection/collection_list')),
				'icon'  => ''
			),
			array(
				'name'	=> __('浏览记录'),
				'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/index/history')),
				'icon'  => ''
			),
			array(
				'name'	=> __('资金管理'),
				'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/user_account/account_detail')),
				'icon'  => ''
			),
			array(
				'name'	=> __('客户服务'),
				'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('user/user_package/service')),
				'icon'  => ''
			),
			array(
				'name'	=> __('购物车'),
				'url'	=> str_replace(RC_Uri::site_url(), '', RC_Uri::url('cart/index/init')),
				'icon'  => ''
			)
		);
	}

	/**
	 * 获得分类列表
	 * @return multitype:unknown mixed
	 */
	private function get_categorynav()
	{
		RC_Loader::load_app_func('front_category','goods');
		RC_Loader::load_app_func('front_article','article');
//         $cat_list = cat_list(0, 0, false) ? cat_list(0, 0, false) : array();
//         $article_cat_list = article_cat_list(0, 0, false) ? article_cat_list(0, 0, false) : array();

//         $catlist = array_merge($cat_list, $article_cat_list);
		if (!empty($catlist)) {
			foreach($catlist as $key => $val) {
				$val['view_name'] = $val['cat_name'];
				for($i=0;$i<$val['level'];$i++) {
					$val['view_name'] = '&nbsp;&nbsp;&nbsp;&nbsp;' . $val['view_name'];
				}
				$val['url'] = str_replace( '&amp;', '&', $val['url']);
				$val['url'] = str_replace( '&', '&amp;', $val['url']);
				$sysmain[] = array($val['cat_name'], $val['url'], $val['view_name']);
			}
		}
		return $sysmain;
	}

	/**
	 * 列表项修改
	 * @param number $id
	 * @param array $args
	 * @return boolean
	 */
	private function nav_update($id, $args)
	{
		$db = RC_Loader::load_model('nav_model');
		if (empty($args) || empty($id)) {
			return false;
		}
		return  $db->where('id = '.$id.'')->update($args);
	}

	/**
	 * 根据URI对导航栏项目进行分析，确定其为商品分类还是文章分类
	 * @param string $uri
	 * @return multitype:string Ambigous <> |multitype:string multitype: |boolean
	 */
	function analyse_uri($uri)
	{
		$uri = strtolower(str_replace('&amp;', '&', $uri));
		$arr = explode('-', $uri);
		switch ($arr[0]) {
			case 'category' :
				return array('type' => 'c', 'id' => $arr[1]);
				break;
			case 'article_cat' :
				return array('type' => 'a', 'id' => $arr[1]);
				break;
			default:
				break;
		}

		list($fn, $pm) = explode('?', $uri);

		if (strpos($uri, '&') === false) {
			$arr = array($pm);
		} else {
			$arr = explode('&', $pm);
		}
		switch ($fn) {
			case 'category.php' :
				//商品分类
				foreach ($arr as $k => $v) {
					list($key, $val) = explode('=', $v);
					if ($key == 'id') {
						return array('type' => 'c', 'id' => $val);
					}
				}
				break;
			case 'article_cat.php'  :
				//文章分类
				foreach($arr as $k => $v) {
					list($key, $val) = explode('=', $v);
					if ($key == 'id') {
						return array('type' => 'a', 'id'=> $val);
					}
				}
				break;
			default:
				//未知
				return false;
				break;
		}

	}

	/**
	 * 是否显示
	 * @param string $type
	 * @param number $id
	 */
	private function is_show_in_nav($type, $id)
	{
		if ($type == 'c') {
			$db = RC_Loader::load_app_model('category_model','goods');
		} else {
			$db = RC_Loader::load_app_model('article_cat_model','article');
		}

		return  $db->field('show_in_nav')->find('cat_id = '.$id.'');
	}

	/**
	 * 设置是否显示
	 * @param string $type
	 * @param int $id
	 * @param string $val
	 */
	function set_9show_in_nav($type, $id, $val)
	{
		if ($type == 'c') {
			$db = RC_Loader::load_app_model('category_model','goods');
		} else {
			$db = RC_Loader::load_app_model('article_cat_model','article');
		}
		$db->where('cat_id = '.$id.'')->update(array('show_in_nav' => $val));
	}

// 	/**
// 	 * 添加菜单列表
// 	 */
// 	public function add_nav_list() {
// 		$this->admin_priv('touch_navigator');

// 		RC_Script::enqueue_script('ecjia-admin_nav', RC_Uri::admin_url() . '/statics/js/ecjia/ecjia-admin_nav.js', array('ecjia-admin'), false, true);

// 		$pagenav = $this->get_pagenav();
// 		$categorynav = $this->get_categorynav();
// 		$this->assign('pagenav',      $pagenav);
// 		$this->assign('categorynav',  $categorynav);
// 		ecjia_screen::get_current_screen()->add_nav_here(new admin_nav_here(__('菜单管理')));

// 		$this->assign('ur_here', __('菜单管理'));
// 		$this->assign('nav_list',     unserialize(ecjia::config('navigator_data')));

// 		$this->assign('form_action',     RC_Uri::url('@navigator/init'));
// 		$this->display('navigator_addlist.dwt');
// 	}

// 	/**
// 	 * 执行添加菜单栏列表
// 	 */
// 	public function update_nav_list() {
// 		$name = strip_tags(htmlspecialchars($_GET['nav_name']));
// 		$list = unserialize(ecjia::config('navigator_data'));

// 		if (empty($name))die(__('菜单名不能为空'));

// 		//判断是否重复
// 		foreach ($list as $v) {
// 			if ($name == $v['name'])die(__('重复了'));
// 		}

// 		//插入新菜单
// 		$tmp = 'nav'.substr(time(),-5).rand(0, 99);
// 		$list[] = array('type'=>$tmp,'name'=>$name);

// 		if ($this->config->write_config('navigator_data', serialize($list))) {
// 			$_GET['type'] = $tmp;
// 			$this->init();
// 		} else {
// 			echo __('失败');
// 		}

// 	}

// 	/**
// 	 * 删除菜单内容
// 	 */
// 	public function del_nav() {
// 		$id = intval($_POST['del_id']);
// 		if (empty($id))die('0');
// 		$this->db_nav->where('id = '.$id.'')->delete();
// 		echo 1;
// 	}

// 	/**
// 	 * 删除菜单
// 	 */
// 	public function del_navlist() {
// 		$type = strip_tags(htmlspecialchars($_GET['del_type']));
// 		$list = unserialize(ecjia::config('navigator_data'));

// 		//直接删除
// 		foreach ($list as $k => $v) {
// 			if ($v['type'] == $type)unset($list[$k]);
// 		}

// 		if (empty($list)) {
// 		    ecjia_config::instance()->write_config('navigator_data', '');
// 		}
// 		if (ecjia_config::instance()->write_config('navigator_data', serialize($list))) {
// 			$this->db_nav->where("type = '".$type."'")->delete();
// 			$this->init();
// 		} else {
// 			echo 0;
// 		}

// 	}

}

// end