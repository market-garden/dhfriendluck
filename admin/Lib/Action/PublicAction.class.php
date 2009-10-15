<?php
// +----------------------------------------------------------------------
// | ThinkSnS
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.thinksns.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Nonant <nonant@163.com>
// +----------------------------------------------------------------------
// $Id$
class PublicAction extends BaseAction  {
    
//	public function _initialize() {
//		 $userId = $this->api->user_getLoggedInUser();
//		 $this->userId = $userId;
//		 $this->assign('userId',$userId);
//	}
		
	//登陆
	function login(){
		$this->display();
	}
	
	//退出
	function logout(){
		Session::set('ThinkSnSAdmin','');
		$this->success('退出成功');
	}
	
	//验证登陆
	function checklogin(){
		$strVerify   = h($_POST['verify']);
		if(md5($strVerify)!=$_SESSION['verify']){
			$this->error('验证码错误');
			exit;
		}
		if($this->uid){
			$map['id'] = $this->uid;
		}else{
			$map['email'] = h($_POST['account']);
		} 
		$map['passwd']    = md5($_POST['password']);
		$pUser = D('User');
		$user = $pUser->where($map)->field("id,name,active,admin_level")->find();
		if($user){
			//登陆成功
            $_SESSION["userInfo"] = serialize($user);
			Session::set('ThinkSnSAdmin',$user['id']);
			$this->assign('jumpUrl',U('Index/index'));
            $this->success('登陆成功');
		}else{
			$this->error('登陆失败');
		}
	}
	
	//验证码
    function verify()
    {
        import("ORG.Util.Image");
        Image::buildImageVerify();
    }	
}

?>