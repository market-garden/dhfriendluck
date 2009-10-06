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
 *  后台权限配置
 +------------------------------------------------------------------------------
 * @Author: Nonant <nonant@163.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
import('AdvModel');
class SystemNodeModel extends AdvModel
{
	var $table_name = 'system_node';
	
	//表单验证
	protected  $_validate = array(
		array('title','require','菜单名不能为空！',1),
		array('description','require','描述不能为空！',1),
	);
	
	function getNodeList($pid='0',$type='admin') {
		return $this->_MakeTree($pid,$type);
	}	
	
	function _MakeTree($pid,$type='admin') {
		$result = $this->where("pid=".$pid." AND type='".$type."'")->findall();
		if($result){
			foreach ($result as $key => $value){
				$name = $value['name'];
				$list[$name]['id']    = $value['id'];
				$list[$name]['pid']    = $value['pid'];
				$list[$name]['name']  = $value['name'];
				$list[$name]['title'] = $value['title'];
				$list[$name]['level'] = $value['level'];
				$list[$name]['containaction'] = $value['containaction'];
				$list[$name]['state'] = $value['state'];
				if($value['level']<3){
					$list[$name]['child'] = $this->_MakeTree($value['id'],$type);
				}
			}
		}
		return $list;
	}
	
	function getAppsList(){
		
	}
	
	//删除节点
	function _DelNode($id){
		$list = $this->where('id='.$id)->find();
		if($list['level']=='2'){
			$this->where('pid='.$id)->delete();
		}elseif ($list['level']=='1'){
			$alllist = $this->where('pid='.$id)->findall();
			foreach ($alllist as $key=>$val){
				$this->where('pid='.$val['id'])->delete();
			}
			$this->where('pid='.$id)->delete();
		}
		$this->delete($id);
		return true;
	}
}
?>