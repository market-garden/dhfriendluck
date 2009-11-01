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
	
	function getNodeList($pid='0',$type='admin',$isall='') {
		return $this->_MakeTree($pid,$type,$isall);
	}	
	
	function _MakeTree($pid,$type='admin',$isall='') {
		if($isall!='All'){
			$map['state'] = 1;
		}
		$map['pid'] = $pid;
		$map['type'] = $type;		
		$result = $this->where($map)->order('ordernum ASC')->findall();
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
	
	
	//获取列表
	function getList($intPid=0,$strType){
		$page = 20;
		if($intPid) $info = $this->where('id='.$intPid)->find();
		$map['type'] = $strType;
		$map['pid']  = $intPid;
		$list = $this->where($map)->findPage($page);
		
		if($intPid==0 && $strType=='guest'){
			$applist = D('App')->where('status=0 OR status=1')->field('id,name,enname')->findall();	
 			$TsArr = array(
                           'id'=>0,
                            'name' => '核心',
                            'enname' => 'thinksns'
                          );
			array_push($applist,$TsArr);
			$return['applist'] = $applist;
		}
		
		$return['level']  = $info['level']+1;
		$return['pid']    = $intPid;
		$return['list']   = $list;
		return $return;
	}
	
	//添加 修改 节点
	function addnode($intId){
        $strType = h($_POST['type']);
        
        if($_POST['level']=='3') {
                $getReturn =$this->_getActionList();
                $data['containaction']  = $getReturn['arrActionList'];
                $data['title']          = $getReturn['indexShow']['title'];
                $data['name']           = $getReturn['indexShow']['name'];
                $data['description']    = $getReturn['indexShow']['title'];
        }else {
        		$data['containaction']  = $_POST['containaction'];
                $data['title']          = $_POST['title'];
                $data['name']           = $_POST['name'];
                $data['description']    = $_POST['description'];
        }
        if($intId){
        	$data['state']              = intval($_POST['state']);
        	$result = $this->where('id='.$intId)->save($data);
        }else{
	        $data['pid']                = intval($_POST['pid']);
	        $data['level']              = intval($_POST['level']);
	        $data['state']              = intval($_POST['state']);
	        $data['type']               = $strType;
	        $result = $this->add($data);
        }
        return $result;
	}
	
	//添加节点集操作
    protected function _getActionList() {
        $arrAction = $_POST['action'];
        $intShowIndex = intval($_POST['showindex']);
        foreach ($_POST['action'] as $key => $val) {
            if(!empty($val)) {
                $arrContainaction[$key]['title'] = $_POST['description'][$key];
                $arrContainaction[$key]['name'] = $val;
            }
        }
        $return['arrActionList'] = serialize($arrContainaction);
        $return['indexShow']     = $arrContainaction[$intShowIndex];
        return $return;
    }
}
?>