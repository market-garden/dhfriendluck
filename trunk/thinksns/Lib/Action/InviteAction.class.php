<?php

class InviteAction extends BaseAction{
	var $opts;
	var $Invite;
	var $gid;
	function _initialize(){
		parent::_initialize();
		$opts = $this->api->option_get();
		$this->assign('site_name',$opts['site_name']);
		$this->Invite = D('Invite');

		$this->gid = intval($_REQUEST['gid']);
		
		if(intval($_REQUEST['gid']) <= 0 || !D('Group')->isMember($this->mid,$this->gid)){
			$this->gid = 0;
		}
		
		$this->assign('gid',$this->gid);
	}

	function index(){
		$this->display();
	}


	//直接发送链接邀请
	function directSendLink() {

        //url
        $url = C("TS_URL")."/index.php?s=/Index/reg/uid/".$this->mid."/code/";
        $this->assign("url",$url);
        //好友分类
		$map = "uid = 0 or uid = ".$this->mid;
		$groups = D("FriendGroup")->where($map)->order("id asc")->findAll();
        $this->assign("groups",$groups);
        //邀请内容
        $opts = $this->api->option_get();
        $invite = str_replace( '{SITE_NAME}',$opts['site_name'],$opts['invite_content'] );
        $this->assign( 'invite_content',$invite );

		$this->display('directSendLink');
	}

	//直接发送email
	function directSendEmail(){

		$map = "uid = 0 or uid = ".$this->mid;
		$groups = D("FriendGroup")->where($map)->order("id asc")->findAll();
        $this->assign("groups",$groups);
		$this->display();
	}
    /*
     * 直接发送邀请的email邮件
     *
     */
    function doDirectSendEmail() {

       set_time_limit(0);

       if(empty($_POST["emails"]) || empty($_POST['content'])) $this->error('Email和留言信息不能为空');
	   $email_arr = explode("\n",$_POST["emails"]);

       $name = $_POST["name"]?$_POST["name"]:$this->my_name;
       $title = $name."给你发来邀请邮件";

       $url = C("TS_URL")."/index.php?s=/Index/reg/uid/{$this->mid}/code/".$_POST["code"];
       $link = '<a href="'.$url.'">'.$url.'</a>';
       $content = "<html>".$_POST["content"]."<br>".$link."</html>";


       //$opts = $this->api->option_get();
       //$options['stmp'] = $opts['email_stmp'];
       //$options['port'] = $opts['email_port'];
       //$options['username'] = $opts['email_address'];
       //$options['password'] = $opts['email_password'];
       //$options['site_name'] = $opts['site_name'];
        foreach($email_arr as $k=>$v){
            $v = trim($v);
            //if(is_email($v)) $sr=true;//{ exit('ffffffff');}//send_email($v,$title,$content,'HTML',$options);
        	if(is_email($v)){ $ret = $this->Invite->saveEmail(array($v),$title,$content,$this->mid,$name);}
        }

		if($ret) {
        	$url = __APP__.'/Invite/index';
    		$this->assign('jumpUrl',$url);
			$this->success('发送成功');
		}else{
			$this->error('邮件发送失败');
		}
    }

    //songhongguang 修改
    //msn邀请
    function msn(){
    	$this->display();
    }

    function doMsnInvite(){
    	 set_time_limit(120);
    	 
    	 if(empty($_POST['account']) || empty($_POST['password'])){
    			$this->error('账号或者密码不能为空');
    	 }
    	$account = t($_POST['account']);
    	$password = $_POST['password'];


    		vendor('Loginer.msn_loginer');

    		$msnFriends = get_msn_friends($account,$password);

    		if(!$msnFriends){
    			vendor('Sync.msn#class');
    			$msn2 = new hotmail;
    			$msnFriends=	$msn2->qGrab($account,$password);
    		}
    		if(!$msnFriends) $this->error('没有找到好友');
    		$emails = array();
    		foreach($msnFriends as $k => $v){
    			$emails[] = $k;
    		}
    		$emailArr = D('Invite')->filterEmail($emails,$this->mid);

    		//print_r($emailArr);
    		//好友分类
    		$map = "uid = 0 or uid = ".$this->mid;
    		$groups = D("FriendGroup")->where($map)->order("id asc")->findAll();
			
    	 	//$emailArr = array('sendInviteFriend'=>array('song_0803@126.com','jiezo@vip.qq.com','melec@163.com'),'sendInviteReg'=>array('song@sohu.com'));
    		$this->assign("groups",$groups);
    		$this->assign('emailArr',$emailArr);
    		$this->display('doMsnInvite');
    }


    function sendFriendInvite(){

    	 $uids = $_POST['uids'];
    	 
    	 if(empty($uids)) { echo 0;exit();}
    	 $uids = explode(',',$uids);
    	 
    	 if(intval($this->gid) > 0){  //群组邀请信息  //加个gid
      		$msg = "好友邀请,并加入好友邀请的群组";
    	 	 foreach ($uids as $v){
    	 		D('Group')->joingroup($v,$this->gid,0,false,$msg='');
    	 	}
    	 }else{  //注册邀请信息
       		$msg = "好友邀请";
    	 }
    	
    	 $result = D('Invite')->addFriend($uids,$this->mid,$msg);

    	 if($result){
    	 	echo 1;
    	 	
    	 	exit;
    	 }else{
    	 	echo 0;
    	 	exit;
    	 }
    }

	//发送email 邀请注册
    function doSendRegInvite(){
    	$toEmail = $_POST['emails'];
    	if(empty($toEmail)) $this->error('请选择email地址');
    	  if(isset($gid)){  //群组邀请信息  //加个gid
      		$title = getUserName($this->mid)."给你发来邀请邮件,邀请你加入xx群组";
    	 }else{  //注册邀请信息

       		$title = getUserName($this->mid)."给你发来邀请邮件";
    	 }
    	 $name = $_POST["name"]?$_POST["name"]:$this->my_name;
    	 $url = C("TS_URL")."/index.php?s=/Index/reg/uid/{$this->mid}/code/".$_POST["code"];
      	 $link = '<a href="'.$url.'">'.$url.'</a>';
       	 $content = "<html>".$_POST["content"]."<br>".$link."</html>";

       	 $result = $this->Invite->saveEmail($toEmail,$title,$content,$this->mid,$name,$type='');  //保存email地址到数据库

    	 if($result){
    	 	$url = __APP__.'/Invite/index';
    	 	$this->assign('jumpUrl',$url);
			$this->success('邀请发送成功');
    	 }else{
    	 	$this->error('邀请发送失败');
    	 }
    }

    //导入邮件地址薄
   function postOffice(){
   		$this->display();
   }


    function getEmailList(){
    	set_time_limit(0);

    	if(empty($_POST['account']) || empty($_POST['password'])){
    		$this->error('账号或者密码不能为空');
    	}
    	vendor('Sync.mailfactory');
    	switch ($_POST['email_type']){
    		case "126.com":
				$contact = new MailFactory(M126);
			break;
			case "sina.com":
				$contact = new MailFactory(MSINA);
			break;
			case "tom.com":
				$contact = new MailFactory(MTOM);
			break;
			case "gmail.com":
				$contact = new MailFactory(MGOOGLE);
			break;
			case "163.com":
				$contact = new MailFactory(M163);
			break;
			case "sohu.com":
				$_POST['account'] = $_POST['account'] . "@" . $_POST['email_type'];
				$contact = new MailFactory(MSOHU);
			break;
			case "vip.sohu.com":
				$_POST['account'] = $_POST['account'] . "@" . $_POST['email_type'];
				$contact = new MailFactory(MSOHU_VIP);
			break;
			case "yahoo.cn":
			case "yahoo.com":
			case "yahoo.com.cn":
				$_POST['account'] = $_POST['account'] . "@" . $_POST['email_type'];
				$contact = new MailFactory(MYAHOO);
			break;
			default:
				die("error");
    	}
		$contacts = $contact->getcontactlist($_POST['account'], $_POST['password']);

		if(empty($contacts)){
    		$this->error('对不起，没有找到你的联系人');
    	}else{
    		$emailArr = D('Invite')->filterEmail($contacts,$this->mid);

    		//获取朋友类型
    		$map = "uid = 0 or uid = ".$this->mid;
    		$groups = D("FriendGroup")->where($map)->order("id asc")->findAll();

    		$this->assign("groups",$groups);
    		$this->assign('emailArr',$emailArr);
    		$this->display('doMsnInvite');
    	}
    }
}
?>
