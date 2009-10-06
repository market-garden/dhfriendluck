<?php
//相册应用 - BaseAction 公共基础Action
class BaseAction extends Action{

	var $api;
	var $mid;
	var $uid;
	var $appId;
	var $setting;

	//执行应用初始化
	public function _initialize() {

		//实例化API接口
		$this->mid	=	$this->api->user_getLoggedInUser();
		$this->assign('appid',$this->appId);

		//获取相册设定
		D('AppConfig')->setAppname	=	APP_NAME;
		$this->setting	=	D('AppConfig')->getConfig();
		$this->assign('setting',$this->setting);

		//添加默认相册
		D('Album')->createNewData($this->mid);

		//获取用户信息
		$user	=	$this->api->user_getInfo($this->uid);
		if(!$user){
			$this->error('该用户不存在，或已被删除！');
		}else{
			if($this->mid==$user['id']){
				$user['myname']	=	$user['name'];
				$user['name']	=	'我';
			}
		}
		$this->assign('user',$user);

		$this->setTitle($user['name'].'的照片');
	}
}
?>