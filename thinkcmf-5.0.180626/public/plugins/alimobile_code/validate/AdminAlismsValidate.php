<?php
namespace plugins\alimobile_code\validate;

use think\Validate;

class AdminAlismsValidate extends Validate {
	protected $rule = [
		'name' => 'require',
		'template_code' => 'require',
	];

	protected $message = [
		'name.require' => "短信模板名称不能为空！",
		'template_code.require' => "短信模板CODE不能为空!",
	];

}