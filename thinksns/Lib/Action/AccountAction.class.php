<?php

class AccountAction extends BaseAction{

	function changePw(){
       $dao = D("User");

       $userNewPassword = trim($_POST['passwd']);
       $userReNewPassword = trim($_POST['repasswd']);



       if(empty($userNewPassword)){
           $this->error('新密码不能为空');
       }else{
       		if($userNewPassword!=$userReNewPassword){
       			$this->error('您两次输入的密码不一致');
       		}else{
		       $map["id"] = $this->mid;
		       $map["passwd"] = md5($_POST["oldpass"]);

		       $user = $dao->where($map)->count();

		       if($user){
		           if($this->mid && is_numeric($this->mid)){
		              $r = $dao->setField("passwd",md5($userNewPassword),"id=".$this->mid);
		              $r ? $this->success("密码修改成功!") : $this->error("密码修改失败!");
		           }
		       }else{
		           $this->error("您输入的旧密码有误，请重新尝试！");
		       }
       		}
       }
    }

    function account(){
        $info = D("User")->find($this->mid);
        $this->assign("old_email",$info["email"]);

        $this->display();

    }


    function changeAccount(){


   		if(md5($_POST['verify']) != $_SESSION['verify']){
			$this->error("验证码输入有误");
		}

        $this->jihuo($this->mid, $_POST["email"]);

    }
    function score(){
            $score = D('UserScore')->getUserScore($this->mid);
            $this->assign(($score));
            $this->display();
    }
    function score_faq(){
            $credit = $this->api->CreditSetting_getAllCredit();
            $credit_type = $this->api->CreditSetting_getCreditType();
            $rank_rule = $this->api->SystemUserRank_getAllRule();
             $this->assign('rank_rule',$rank_rule);
            $this->assign('list',$credit);
            $this->assign('type',$credit_type);
            $this->display();
    }
    function security(){
        $dao = D("LoginRecord");
        $pLoginRecord = $dao->where("uid=$this->mid")->order("login_time DESC")->limit(2)->findAll();

		//上次登陆
        $lastLoginInfo = $pLoginRecord[1];
		//本次登陆
        $thisLoginInfo = $pLoginRecord[0];


        $this->assign('lastLogin',$lastLoginInfo);
        $this->assign('thisLoginInfo',$thisLoginInfo);

        /*
			//Ip 识别 需要QQWry.dat飘云地址库。由于地址库太大，在安装包中先关掉了。
			//如果接口失效，可以自己下载QQWry放到 thinkphp/Lib/ORG/Net/ 目录下。
			import("ORG.Net.IpLocation");
			$ipLocation = new IpLocation('QQWry.Dat');

			//$lastAddress = $ipLocation->getlocation($lastLoginInfo["login_ip"]);
			//$this->assign('lastLoginAddress',iconv('gbk','utf-8',$lastAddress['country']));

			//$thisAddress = $ipLocation->getlocation($thisLoginInfo["login_ip"]);
			//$this->assign('thisLoginAddress',iconv('gbk','utf-8',$thisAddress['country']));
		*/

        $this->display();
    }



    /*
     * 邮件提醒
     *
     */
    function subscribe() {

        //我的邮箱
        $info = D("User")->find($this->mid);
        $this->assign("email",$info["email"]);


        $dao = D("Privacy");

        //隐私选项
        $map["uid"] = $this->mid;
        $map["type"] = "remind";
        $data = $dao->where($map)->find();

        $privacy = unserialize($data["privacy"]);
        $this->assign("privacy",$privacy);
        if(!$privacy) $this->assign("null",0);

        $this->display();
    }

    /*
     * 设置邮件提醒
     *
     */
     function doSubscribe(){

        $dao = D("Privacy");

        $privacy = serialize($_POST);

        $data["uid"] = $this->mid;
        $data["type"] = "remind";

        $r = $dao->where($data)->find();

        if(!$r){
            $data["privacy"] = $privacy;
            $dao->add($data);

        }else{
            $dao->privacy = $privacy;
            $dao->save();
        }
        exit;

        $this->redirect("Account/subscribe");
     }




    /**
     * jihuo
     *
     * @param mixed $uid
     * @param mixed $email
     * @return void
     */
    public function jihuo($uid,$email){
         //发送激活email
        $jh_url =	"http://".$_SERVER['HTTP_HOST'].__APP__."/Index/active/code/".jiami($uid)."/new/".$email;
        //$subject = iconv("utf-8","gbk","ThinkSNS给您发来的注册激活邮件。");
        $subject = "ThinkSNS给您发来的修改账号激活邮件。";
        $body = "尊敬的先生（女士），您好！<p>请点击此链接即可激活您的帐户：<p><a href='".$jh_url."'>激活ThinkSNS的新账号</a>";

        $sr = send_email($email,$subject,$body);

        if($sr){
            $this->redirect("/Account/account/t/1");
        }else{
            $this->error("发送激活邮件失败!");
        }


    }




    /**
     * checkEmail
     *
     * 检测邮箱是否被占用
     *
     * @return void
     */
    public function checkEmail(){
        $map["email"] = $_REQUEST["email"];
        $user = D("User")->where($map)->count();
        echo $user == 0 ?  "success": "邮箱已被占用";
    }

    /**
     * checkVerify
     *
     * 检测验证码是否正确
     *
     * @return void
     */
    public function checkVerify(){
  		echo (md5($_REQUEST['verify']) == $_SESSION['verify']) ?  "success": "验证码输入有误，请重输！";
    }


    /**
     * verify
     *
     * @return void
     */
    public function verify() {
        import("ORG.Util.Image");
        Image::buildImageVerify();
    }
}
?>
