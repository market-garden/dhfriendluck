<?php
class CategoryModel extends BaseModel
{

	var $tableName = 'group_category';
	//生成分类Tree
	function _makeTree($pid) {
		
		if( $c = $this->where("pid='$pid'")->findAll() ){
			
			foreach($c as $v){
				$cTree[t]	=	$v['title'];
				$cTree[a]	=	$v['id'];
				//$cTree[d]	=	$this->_makeTree($v['id']);
				$cTree[d] = '';//$v['id'];
				$cTrees[]	=	$cTree;
			}
		}
		return $cTrees;
	}
	//获取LI列表
	function getCategoryList($pid='0') {
		$list	=	$this->_makeLiTree($pid);
		return $list;
	}
	function _makeLiTree($pid) {
		
		if( $c = $this->where("pid='$pid'")->findAll() ){
			
			$list	.=	'<ul>';
			foreach($c as $p){
				@extract($p);
				
				$ptitle	=	"<span id='category_".$id."' title='".$title."'><a href='javascript:void(0)' onclick=\"edit('".$id."')\">".$title."</a></span>";
				$title	=	'['.$id.'] '.$ptitle;

				$list	.=	'
					<li id="li_'.$id.'">
					<span style="float:right;">
						<a href="__APP__/Admin/editCategory/id/'.$id.'" >修改</a>
						<a href="__APP__/Admin/delCategory/id/'.$id.'" >删除</a>
					</span> '.$title.'
					</li>
					<hr style="height:1px;color:#ccc" />';
				
				$list	.=	$this->_makeLiTree($id);
			
			}
			$list	.=	'</ul>';
			
		}
		return $list;
	}
	//解析分类
	function _digCate($array) {
	
		foreach($array as $k=>$v){
			
			$nk	=	str_replace('pid','',$k);
			if(is_numeric($nk) && !empty($v)){
				$cates[$nk]	=	intval($v);
			}
		}
		$pid	=	is_array($cates)?end($cates):0;
		
		unset($cates);
		return intval($pid);
	}
	//解析分类树
	function _digCateTree($array) {
		foreach($array as $k=>$v){
			$nk	=	str_replace('pid','',$k);
			if(is_numeric($nk) && !empty($v)){
				$cates[$nk]	=	intval($v);
			}
		}
		if(is_array($cates)){
			return implode(',',$cates);
		}else{
			return intval($cates);
		}
	}
	//生成分类树
	function _makeParentTree($id,$onlyShowPid=false) {
		$tree	=	$this->_makeCateTree($id);
		if($onlyShowPid){
			$tree	=	str_replace(','.$id,'',$tree);
		}
		return $tree;
	}
	function _makeCateTree($id) {
		//$pid	=	$this->find($id,'pid')->pid;
		
		$pid = $this->getField('pid','id='.$id);
		if($pid>0){
			$tree	=	$this->_makeCateTree($pid).','.$id;
		}else{
			$tree	=	$id;
		}
		return $tree;
	}
}
?>