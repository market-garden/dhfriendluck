<?php
import('AdvModel');
class FriendPingModel extends AdvModel
{

    // 自动验证设置
    protected $_validate     =     array(
		array('pingUserId','reqiure','用户id不能为空',self::MUST_VALIDATE),
    );


	


   
}
?>