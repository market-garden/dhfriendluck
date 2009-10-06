<?php

import('AdvModel');
class GroupModel extends AdvModel {
	function  _initialize() {
		parent::_initialize();

	}



	//某人加入某群组
	function joingroup($mid,$gid,$level,$incMemberCount=false,$reason='') {

		//if(D('Member')->find("uid=$mid AND gid=$gid")) exit('你已经加入过');

		
		$name = getUserName($mid);
		
		$ctime = time();
		$mtime = time();
		
		//群组邀请
		$sql = "INSERT INTO `{$this->tablePrefix}group_member` (uid,gid,name,level,ctime,mtime) VALUES ($mid,$gid,'$name',$level,$ctime,$mtime)";
		
		$ret = $this->query($sql);
		
        if($incMemberCount)D('Group')->setInc('membercount','id='.$gid);  //不需要审批直接添加群组数量，审批就不用添加了。

		return $ret;

	}
	
	
	function isMember($uid,$gid){
		return $this->table("{$this->tablePrefix}group_member")->where("uid=$uid AND gid=$gid")->count();
	}


}

?>