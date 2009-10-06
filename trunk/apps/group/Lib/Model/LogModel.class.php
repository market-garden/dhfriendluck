<?php
class LogModel extends BaseModel
{
	
	var $tableName = 'group_log';
	
	
	//写话题日志
	function writeLog($gid,$uid,$content,$type='topic') {
		$map['gid'] = $gid;
		$map['uid'] = $uid;
		$map['type'] = $type;
		$map['content'] = "<a href='__TS__/space/{$uid}'>".getUserName($uid)."</a> ".$content;
		$map['ctime'] = time();
		$this->add($map);
		
	}
	
	//写成员日志
	function writeMemberLog() {
		
	}
	
	//写设置日志
	function writeSettingLog() {
		
	}
	
	
	
	
}

?>