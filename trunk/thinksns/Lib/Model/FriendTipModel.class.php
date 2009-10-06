<?php
import('AdvModel');
class FriendTipModel extends AdvModel
{

    // 自动验证设置
    protected $_validate     =     array(
		array('content','checkLength','内容字数不合要求',self::MUST_VALIDATE,'callback'),
    );


	function checkLength($data,$field)
	{
		switch($field) {
			case "content": return ( strlen($data)>0 && strlen($data)<=20 )? true : false; 
		}
		
	}


	function get($uid) {
		$tip = $this->where("uid=$uid")->find();
		return $tip["content"];
	}


}
?>