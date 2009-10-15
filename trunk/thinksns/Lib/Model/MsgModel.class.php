<?php
import('AdvModel');
class MsgModel extends AdvModel
{

    // 自动验证设置
    protected $_validate     =     array(
		array('fri_ids','require','请选择用户!',self::MUST_VALIDATE),
		array('subject','checkLength','标题字数不能超过60个字符',self::MUST_VALIDATE,'callback'),
		array('content','checkLength','内容字数不能超过1000个字符',self::MUST_VALIDATE,'callback'),
    );


	function checkLength($data,$field)
	{
		switch($field) {
			case "subject": return ( strlen($data)>0 && strlen($data)<=60 )? true : false;
			case "content": return ( strlen($data)>0 && strlen($data)<=2000 )? true : false;
		}

	}


}
?>