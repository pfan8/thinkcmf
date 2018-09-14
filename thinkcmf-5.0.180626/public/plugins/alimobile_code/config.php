<?php
return array(
	'AccessKeyId' => array( // 在后台插件配置表单中的键名 ,会是config[text]
		'title' => 'AccessKeyId', // 表单的label标题
		'type' => 'text', // 表单的类型：text,password,textarea,checkbox,radio,select等
		'value' => 'LTAIWGS9Zb821FIj', // 表单的默认值
		'tip' => '阿里云安全认证公钥<a href="https://help.aliyun.com/document_detail/59031.html?spm=5176.10629532.106.14.53071cbeNCqtBL" target="_blank">如何获取</a>', //表单的帮助提示
	),
	'AccessKeySecret' => array( // 在后台插件配置表单中的键名 ,会是config[text]
		'title' => 'AccessKeySecret', // 表单的label标题
		'type' => 'text', // 表单的类型：text,password,textarea,checkbox,radio,select等
		'value' => 'FFdDiCnjNq68HdMmyMULA4DFDfTp36', // 表单的默认值
		'tip' => '阿里云安全认证私钥<a href="https://help.aliyun.com/document_detail/59031.html?spm=5176.10629532.106.14.53071cbeNCqtBL" target="_blank">如何获取</a>', //表单的帮助提示
	),
	'isHttps' => array(
		'title' => '请求协议',
		'type' => 'radio',
		'options' => [ //select 和radio,checkbox的子选项
			'1' => 'https协议(推荐)',
			'2' => 'http协议', // 值=>显示
		],
		'value' => '1',
		'tip' => '',
	),
	'SignName' => array( // 在后台插件配置表单中的键名 ,会是config[text]
		'title' => '短信签名', // 表单的label标题
		'type' => 'text', // 表单的类型：text,password,textarea,checkbox,radio,select等
		'value' => '', // 表单的默认值
		'tip' => '请填写阿里云通讯审核通过的短信签名<a href="https://help.aliyun.com/document_detail/55327.html?spm=a2c4g.11186623.6.549.Qdp3dx" target="_blank">如何申请</a>', //表单的帮助提示
	),
	'Scenes' => array(
		'title' => '场景设置',
		'type' => 'text',
		'value' => '',
		'tip' => '不是必选项,如：register|用户短信注册,login|用户短信登录,general|通用短信,findpassword|密码短信找回。此项可不填写',
	),
	'TemplateCode' => array(
		'title' => '默认短信模板',
		'type' => 'text',
		'value' => '',
		'tip' => '请填写阿里云通讯审核通过的短信模板的CODE，此模板变量默认为六位验证码。<a href="https://help.aliyun.com/document_detail/55330.html?spm=a2c4g.11186623.6.550.AYSLmD" target="_blank">如何申请模板</a>',
	),
	'expire_minute' => array( // 在后台插件配置表单中的键名 ,会是config[text]
		'title' => '默认有效期', // 表单的label标题
		'type' => 'text', // 表单的类型：text,password,textarea,checkbox,radio,select等
		'value' => '30', // 表单的默认值
		'tip' => '短信验证码过期时间，单位分钟', //表单的帮助提示
	),
);
