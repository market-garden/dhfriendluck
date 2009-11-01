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
 *  地区网络
 +------------------------------------------------------------------------------
 * @Author: Nonant <nonant@163.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
import('AdvModel');
class NetworkModel extends AdvModel
{
	function getNetworkList($pid='0') {
		return $this->_MakeTree($pid);
	}	
	
	function _MakeTree($pid,$level='1') {
		$result = $this->where('pid='.$pid)->findall();
		if($result){
			foreach ($result as $key => $value){
				$id = $value['id'];
				$list[$id]['id']    = $value['id'];
				$list[$id]['pid']    = $value['pid'];
				$list[$id]['title']  = $value['title'];
				$list[$id]['level']  = $level;
				$list[$id]['child'] = $this->_MakeTree($value['id'],$level+1);
			}
		}
		return $list;
	}
}

?>