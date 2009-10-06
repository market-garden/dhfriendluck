<?php

/**
 +------------------------------------------------------------------------------
 *  test
 +------------------------------------------------------------------------------
 * @Author: Nonant <nonant@163.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
import('AdvModel');
class NotifyTemplateModel extends AdvModel
{
	var $table_name = 'notify_template';
	
	//表单验证
	protected  $_validate = array(
		array('type','require','动态类别不能为空！',1),
		array('title','require','动态标题不能为空！',1),
	);
}

?>