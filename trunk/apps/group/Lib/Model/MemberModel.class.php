<?php

 
class MemberModel extends BaseModel {
	var $tableName = 'group_member';
	
	function memberCount($uid){
		return $this->where("uid=".$uid)->count();
	}
}

?>