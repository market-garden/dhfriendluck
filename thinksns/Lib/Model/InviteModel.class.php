<?php

class InviteModel extends BaseModel
{
	function  _initialize() {
		parent::_initialize();
		
	}
	var $tableName = 'saveemail';
	
	function saveEmail($toEmail,$title,$content,$mid,$name,$type=''){
		
		$result = false;
		if($toEmail){
			foreach($toEmail as $k=>$v){
				$map['toemail']=$v;
				$map['title'] = $title;
				$map['content'] = $content;
				$map['uid'] = $mid;
				$map['userName'] = $name;
				if(is_email($v))$result = $this->add($map);
			}
		}
		return $result;
	}
	
	
	
	//分析email 3 种情况
	function filterEmail($emails,$mid){
		if(empty($emails) && !is_array($emails)) return false;
		$sendInviteFriend = array();  //发送邀请成为朋友
		$friends = array();        //已经是朋友的
		$sendInviteReg = array();  //发送邀请注册
		$num = 0;
		foreach($emails as $email){
			$userInfo = D('User')->field('id,name,sex,current_province')->where("email='$email'")->find();
			if($userInfo['id'] == $mid) continue;  //判读邮箱里面是自己，直接跳过
			if($userInfo){
				//$ret = $this->api->friend_areFriends($mid,$userInfo['id']);  
				$ret = D('Friend')->where('uid='.$mid.' AND fuid='.$userInfo['id'])->count();
				if(!$ret){  //注册用户，但不是朋友
    				$sendInviteFriend[$num]['email'] = $email;           
    				$sendInviteFriend[$num]['uid']= $userInfo['id'];
    				$sendInviteFriend[$num]['username']= $userInfo['name'];
    				$sendInviteFriend[$num]['sex'] = $userInfo['sex'];
    				$sendInviteFriend[$num]['current_province'] = $userInfo['current_province'];
    				$num++;
				}else{
					$friends[$userInfo['id']] = $email;          //已经是朋友的,或者等待朋友通过
				}
			}else{
				$sendInviteReg[] = $email;             //没有在系统注册 
			}
		}
		return array('sendInviteFriend'=>$sendInviteFriend,'sendInviteReg'=>$sendInviteReg,'friends'=>$friends);
	}
	
	
	//发送信息 $to_uids 发送到用户的id，from_uid 来自于用户id，$gid 群组id 
	function sendInviteMsg($to_uids,$from_uid,$gid=0){
		if(empty($to_uids)) return false;
		foreach($to_uids as $k=>$v){
			//群组邀请
			if($gid == 0){
				
			}else{ //好友邀请
				
			}
		}
	}
	
	//添加朋友
	public function addFriend($uids,$mid,$msg=''){

        $fuid = intval($_POST["fuid"]);

        //发送请求
        $dao = D("Friend");
        $dao->create();
     
		foreach($uids as $k=>$v){
			 $dao->fuid = $v;
			 $dao->note = $msg;
			 $dao->uid       =   $mid;
       		 $dao->fusername =   getUserName($v);
       		 $dao->dateline = time();
        	 $ret = $dao->add();
		}
        
        
        //通知
        $type = "add_friend";
        $body_data["note"] = $msg;
        $url  = $mid;
        $cate = "friend";
		
        $r = $this->api->notify_send($uids,$type,$title_data,$body_data,$url,$cate);
		return $ret;
    }
	
	
	
	
}