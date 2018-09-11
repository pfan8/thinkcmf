<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2018 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace plugins\alimobile_code;

use cmf\lib\Plugin;
use plugins\alimobile_code\lib\SignatureHelper;

//Demo插件英文名，改成你的插件英文就行了
class AlimobileCodePlugin extends Plugin {

	public $info = [
		'name' => 'AlimobileCode',
		'title' => '阿里云手机短信插件',
		'description' => '阿里云手机短信服务插件',
		'status' => 1,
		'author' => '瘦先森',
		'version' => '1.0',
	];

	public $hasAdmin = 1; //插件是否有后台管理界面

	public $scenes = array(
		"register" => "用户短信注册",
		"login" => "用户短信登录",
		"general" => "通用短信",
		"findpassword" => "密码短信找回",
	); //默认场景

	// 插件安装
	public function install() {
		return true; //安装成功返回true，失败false
	}

	// 插件卸载
	public function uninstall() {
		return true; //卸载成功返回true，失败false
	}

	//实现的send_mobile_verification_code钩子方法
	/**
	 * [sendMobileVerificationCode description]
	 * @param  [type] $param [mobile,scene场景,code]
	 * @return [type]        [description]
	 */
	public function sendMobileVerificationCode($param) {
		// die("SSS");
		$mobile = $param['mobile']; //手机号
		$config = $this->getConfig();
		/************  场景判断  ************/
		if(!isset($param['scene'])||!$param['scene']){
		    $param['scene']='register';
        }
		$template_code = $this->getTemplateCode($param['scene']); //场景;自己优化包括模板多个变量优化
        if(!$template_code){
            $template_code = 'SMS_10165016';
        }
		$template_param = array('code' => $param['code']);
		$expire_minute = intval($config['expire_minute']);
		$expire_minute = empty($expire_minute) ? 30 : $expire_minute;
		$expire_time = time() + $expire_minute * 60;

		/************  发送短信  ************/
		//可选-启用https协议
		$isHttps = $config['isHttps'] == 1 ? true : false;

		$params = array();

		// *** 需用户填写部分 ***
		//获取配置信息
		$config = $this->getConfig();

		// fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
		$accessKeyId = $config['AccessKeyId'];
		$accessKeySecret = $config['AccessKeySecret'];
		// fixme 必填: 短信接收号码
		$params["PhoneNumbers"] = $mobile;

		// fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
		$params["SignName"] = $config['SignName'];

		// fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
		$params["ContentCode"] = $template_code;

		// fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
		$params['ContentParam'] = $template_param;

		// *** 需用户填写部分结束, 以下代码若无必要无需更改 ***
		if (!empty($params["ContentParam"]) && is_array($params["ContentParam"])) {
			$params["ContentParam"] = json_encode($params["ContentParam"], JSON_UNESCAPED_UNICODE);
		}

		// 初始化SignatureHelper实例用于设置参数，签名以及发送请求
		$helper = new SignatureHelper();

		// 此处可能会抛出异常，注意catch
		$content = $helper->request(
			$accessKeyId,
			$accessKeySecret,
			"dysmsapi.ap-southeast-1.aliyuncs.com",
			array_merge($params, array(
				"RegionId" => "ap-southeast-1",
				"Action" => "SendSms",
				"Version" => "2018-05-01",
			))
			// fixme 选填: 启用https
			, $isHttps
		);

		return $content;
		/************  发送短信结束  ************/
	}

	/**
	 * [getTemplateCode 获取模板CODE]
	 * @return [type] [description]
	 */
	private function getTemplateCode($scene) {
		$scenes = $this->getScenes();
		$templateCodes = cmf_get_option('alisms_settings');

		if (!array_key_exists($scene, $scenes) || array_key_exists($scene, $templateCodes['alisms'])) {
			$config = $this->getConfig();
			return $config['TemplateCode'];
		}
		return $templateCodes['alisms'][$scene]['template_code'];
	}

	/**
	 * [getScenes 获取场景]
	 * @return [type] [description]
	 */
	public function getScenes() {
		$config = $this->getConfig();
		$scenes = array();
		if (empty($config['Scenes'])) {
			$scenes = $this->scenes;
		} else {
			$options = explode(",", $config['Scenes']);
			foreach ($options as $key => $value) {
				$arr = explode("|", $value);
				$scenes[$arr[0]] = $arr[1];
			}
		}
		return $scenes;
	}

}