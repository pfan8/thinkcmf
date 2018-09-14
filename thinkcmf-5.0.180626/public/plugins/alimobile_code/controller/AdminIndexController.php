<?php
namespace plugins\alimobile_code\controller;

use cmf\controller\PluginAdminBaseController;
use plugins\alimobile_code\AlimobileCodePlugin;

class AdminIndexController extends PluginAdminBaseController {

	public function _initialize() {
		$adminId = cmf_get_current_admin_id(); //获取后台管理员id，可判断是否登录
		if (!empty($adminId)) {
			$this->assign("admin_id", $adminId);
		} else {
			$this->error('未登录');
		}
	}

	/**
	 * 短信模板管理
	 * @adminMenu(
	 *     'name'   => '短信模板管理',
	 *     'parent' => 'admin/Plugin/default',
	 *     'display'=> true,
	 *     'hasView'=> true,
	 *     'order'  => 10000,
	 *     'icon'   => '',
	 *     'remark' => '短信模板管理',
	 *     'param'  => ''
	 * )
	 */
	public function index() {
		$alimobileCodePlugin = new AlimobileCodePlugin();
		$scenes = $alimobileCodePlugin->getScenes();
		$alismsSettings = cmf_get_option('alisms_settings');

		$alisms = empty($alismsSettings['alisms']) ? [] : $alismsSettings['alisms'];
		$this->assign('alisms', $alisms);
		$this->assign('scenes', $scenes);

		return $this->fetch('/admin_index');
	}

}
