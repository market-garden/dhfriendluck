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
 *  description
 +------------------------------------------------------------------------------
 * @Author: Nonant <nonant@163.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
import('AdvModel');
class SystemGroupModel extends AdvModel
{
	var $table_name = 'system_group';
	
	//表单验证
	protected  $_validate = array(
		array('name','require','用户组名不能为空！',1),
	);
	
	//获取所有组ID
	function getGroupList(){
		$result = $this->findAll();
		foreach ($result as $val){
			$return[$val['id']] = $val['name'];
		}
		return $return;
	}
}