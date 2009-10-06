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
class SystemPopedomModel extends AdvModel
{
	var $table_name = 'system_popedom';
	
	
	function getGroupPopedom($groupid){
		$arrPopedomList = $this->where('groupid='.$groupid)->field('menuid,modelid,arraction')->findAll();
		foreach ($arrPopedomList as $key=>$val){
			
			$modelname = D('SystemNode')->where('id='.$val['modelid'])->field('name')->find();
			$map['id'] = array('in',unserialize($val['arraction']));
			$actionlist = D('SystemNode')->where($map)->field('title,name')->findall();
			foreach ($actionlist as $k=>$v){
				$arr[strtoupper($v['name'])] = true;
			}
			$result['ADMIN'][strtoupper($modelname['name'])] = $arr;
		}

		$r= array_change_key_case($result,CASE_UPPER);
		return $r;
	}
}
?>