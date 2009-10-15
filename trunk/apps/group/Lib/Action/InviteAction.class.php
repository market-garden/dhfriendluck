<?php

class InviteAction extends BaseAction {
	
	//群组创建的邀请
	function create() {
		$from  = isset($_GET['from']) ? t($_GET['from']) : '';
		if($_POST['sendsubmit']) {
			
			$toUserIds = explode(",",$_POST["fri_ids"]);
    		$title_data['actor'] =  getUserName($this->mid);
			$body_data['actor'] = getUserName($this->mid);
			$body_data['gid'] = $this->groupinfo['id'];
			$body_data['title'] = $this->groupinfo['name'];
    		//$body_data = getUserName($this->mid).'创建了一个群'.'<a href="{'.$this->appId.'}/Group/index/gid/'.$this->groupinfo['id'].'">'.$this->groupinfo['name']."</a>";
            //通知
            $cate = "notification";
            $this->api->notify_setAppId($this->appId);
            $this->api->notify_send($toUserIds,'group_create',$title_data,$body_data,$url,$cate);
        	
           
            $this->assign('sendCount',count($toUserIds));
			$this->display('create_invite_success');
			exit;
		}
		
		$this->assign('from',$from);
		$this->display();
	}
	
}


?>