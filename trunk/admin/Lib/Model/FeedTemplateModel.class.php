<?php
// +----------------------------------------------------------------------
// | ThinkSnS
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.thinksns.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: Nonant <nonant@163.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 *  动态模板管理
 +------------------------------------------------------------------------------
 * @Author: Nonant <nonant@163.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
import('AdvModel');
class FeedTemplateModel extends AdvModel
{
	var $table_name = 'feed_template';
	
	
	//表单验证
	protected  $_validate = array(
		array('type','require','动态类别不能为空！',1),
		array('title','require','动态标题不能为空！',1),
	);
}

?>