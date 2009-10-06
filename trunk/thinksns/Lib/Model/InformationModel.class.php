<?php

class InformationModel extends BaseModel {
	var $tableName = 'info_content';
	//生成分类Tree
	function _makeTree($pid) {
		$c = D('InformationCate')->where("pid IN( $pid )")->findAll();
		
		if( !empty($c) ){
			foreach($c as $v){
				$cTree[t]	=	$v['title'];
				$cTree[a]	=	$v['id'];
				$cTree[d]	=	$this->_makeTree($v['id']);
				$cTrees[]	=	$cTree;
			}
		}
		
		return $cTrees;
	}
	
	
	function  getInfoData($conditions,$num=10,$order="id DESC") {
	//查询条件，默认是分类ID
	if(is_array($conditions)){
		$map	=	$conditions;
	}
	//$map['category']	=	intval($conditions);
	$map['status'] = 1; 
	
	$list	=	$this->where($map)->order($order)->limit($num)->findAll();
	
	return $list;
	}
	
	
	//根据分类名称获取内容
	function  getInfoByCate($cateData,$num=10,$order="id DESC",$type='name') {
	//查询条件，默认是分类ID
	if($type == 'name') {
		$name = is_array($cateData) ? $cateData : array($cateData);
		foreach($name as $v){
			$new_name[] = "'".$v."'";
		}
		$name  = implode(',',$new_name);
		$cateId = D('InformationCate')->field('id')->where("name In ($name)")->findAll();
		unset($cateData);
		$cateData = array();
		foreach($cateId as $v){
			$cateData[] = $v['id'];
		}
	}
	$ids  = implode(',',$cateData);
	$status = 1; 
	$list	=	$this->where("category IN ($ids) AND status=1")->order($order)->limit($num)->findAll();
	
	return $list;
	}
}

?>