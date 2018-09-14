<?php
namespace plugins\alimobile_code\controller;

use cmf\controller\PluginAdminBaseController;
use plugins\alimobile_code\AlimobileCodePlugin;

class AdminAlismsController extends PluginAdminBaseController {

	/**
	 * 添加短信模板
	 * @adminMenu(
	 *     'name'   => '添加短信模板',
	 *     'parent' => 'AdminIndex/index',
	 *     'display'=> false,
	 *     'hasView'=> true,
	 *     'order'  => 10000,
	 *     'icon'   => '',
	 *     'remark' => '添加短信模板',
	 *     'param'  => ''
	 * )
	 */
	public function add() {
		$alimobileCodePlugin = new AlimobileCodePlugin();
		$scenes = $alimobileCodePlugin->getScenes();
		$scenesStr = "";
		foreach ($scenes as $key => $value) {
			$scenesStr .= "<option value='" . $key . "'>" . $value . "</option>";
		}
		$this->assign('scenes', $scenesStr);
		return $this->fetch();
	}

	/**
	 * 添加短信模板提交保存
	 * @adminMenu(
	 *     'name'   => '添加短信模板提交保存',
	 *     'parent' => 'AdminIndex/index',
	 *     'display'=> false,
	 *     'hasView'=> false,
	 *     'order'  => 10000,
	 *     'icon'   => '',
	 *     'remark' => '添加短信模板提交保存',
	 *     'param'  => ''
	 * )
	 */
	public function addPost() {
		$data = $this->request->param();

		$result = $this->validate($data, "AdminAlisms");

		if ($result !== true) {
			$this->error($result);
		}

		$alismsSettings = cmf_get_option('alisms_settings');

		$alismsSettings['alisms'][$data['scene']] = $data;

		cmf_set_option('alisms_settings', $alismsSettings);

		$this->success('添加成功！', cmf_plugin_url('AlimobileCode://AdminAlisms/edit', ['scene' => $data['scene']]));

	}

	/**
	 * 编辑短信模板
	 * @adminMenu(
	 *     'name'   => '编辑短信模板',
	 *     'parent' => 'AdminIndex/index',
	 *     'display'=> false,
	 *     'hasView'=> true,
	 *     'order'  => 10000,
	 *     'icon'   => '',
	 *     'remark' => '编辑短信模板',
	 *     'param'  => ''
	 * )
	 */
	public function edit() {
		$scene = $this->request->param('scene');

		$alismsSettings = cmf_get_option('alisms_settings');

		if (!empty($alismsSettings['alisms'][$scene])) {
			$this->assign('alisms', $alismsSettings['alisms'][$scene]);
		}

		$alimobileCodePlugin = new AlimobileCodePlugin();
		$scenes = $alimobileCodePlugin->getScenes();
		$scenesStr = "";
		foreach ($scenes as $key => $value) {
			$selected = $key === $scene ? "selected" : "";
			$scenesStr .= "<option value='" . $key . "' " . $selected . ">" . $value . "</option>";
		}
		$this->assign('scene', $scene);
		$this->assign('scenes', $scenesStr);

		return $this->fetch();
	}

	/**
	 * 编辑短信模板提交保存
	 * @adminMenu(
	 *     'name'   => '编辑短信模板提交保存',
	 *     'parent' => 'AdminIndex/index',
	 *     'display'=> false,
	 *     'hasView'=> false,
	 *     'order'  => 10000,
	 *     'icon'   => '',
	 *     'remark' => '编辑短信模板',
	 *     'param'  => ''
	 * )
	 */
	public function editPost() {
		$data = $this->request->param();

		$result = $this->validate($data, "AdminAlisms");

		if ($result !== true) {
			$this->error($result);
		}

		$alismsSettings = cmf_get_option('alisms_settings');

		$alismsSettings['alisms'][$data['scene']] = $data;

		cmf_set_option('alisms_settings', $alismsSettings);

		$this->success('保存成功！');
	}

	/**
	 * 删除短信模板
	 * @adminMenu(
	 *     'name'   => '删除短信模板',
	 *     'parent' => 'AdminIndex/index',
	 *     'display'=> false,
	 *     'hasView'=> false,
	 *     'order'  => 10000,
	 *     'icon'   => '',
	 *     'remark' => '删除短信模板',
	 *     'param'  => ''
	 * )
	 */
	public function delete() {
		$scene = $this->request->param('scene');
		$alismsSettings = cmf_get_option('alisms_settings');
		unset($alismsSettings['alisms'][$scene]);
		cmf_set_option('alisms_settings', $alismsSettings);

		$this->success('删除成功！');
	}

}
