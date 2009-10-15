<?php

class InfoAction extends BaseAction{

	//类定义开始
	function _initialize(){
		parent::_initialize();
	}

	public function index(){
		//过滤
		$map = array();

		//ID
		if(isset($_POST['id'])&&!empty($_POST['id'])) $map['id'] = $_POST['id'];

		//标题
		if(isset($_POST['title'])&&!empty($_POST['title'])) $map['title'] = array("like","%".$_POST['title']."%");

		//用户ID
		if(isset($_POST['userId'])&&!empty($_POST['userId'])) $map['userId'] = $_POST['userId'];

		import("ORG.Util.Page");
		$Model = D('Info');

		//排序字段 默认为主键名
		if(isset($_REQUEST['order'])) {
			$order = $_REQUEST['order'];
		}else {
			$order = !empty($sortBy)? $sortBy: $Model->getPk();
		}

		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if(isset($_REQUEST['sort'])) {
			$sort = $_REQUEST['sort']?'asc':'desc';
		}else {
			$sort = $asc?'asc':'desc';
		}

		$count = $Model->count($map);
		$listRows = 30;
		$page = new Page($count,$listRows);
		
		//$list = $Model->where($map)->findAll();
		$list = $Model ->where($map)->limit($page->firstRow.','.$page->listRows)->findAll();
		
		
		$pageHtml = $page->show();

		//列表排序显示
		$sortImg    = $sort ;                                   //排序图标
		$sortAlt    = $sort == 'desc'?'升序排列':'倒序排列';    //排序提示
		$sort       = $sort == 'desc'? 1:0;                     //排序方式
		$this->assign('sort',       $sort);
		$this->assign('order',      $order);
		$this->assign('sortImg',    $sortImg);
		$this->assign('sortType',   $sortAlt);

		$this->assign("count",$count);
		$this->assign("page",$pageHtml);
		$this->assign("list",$list);
		$this->display();
		return;
	}
	//添加文章分类
	function addCategory() {
		$tree	=	json_encode($this->_makeTree(0));
		
		$this->assign('category_json',$tree);
		$categoryList	=	$this->getCategoryList(0);
		$this->assign('categoryList',$categoryList);
		$this->display();
	}
	
   		

	//获取LI列表
	function getCategoryList($pid='0') {
		$list	=	$this->_makeLiTree($pid);
		return $list;
	}
	function _makeLiTree($pid) {
		
		if( $c = D('InfoCate')->where("pid='$pid'")->findAll() ){
			
			$list	.=	'<ul>';
			foreach($c as $p){
				@extract($p);
				
				$ptitle	=	"<span id='category_".$id."' title='".$title."'><a href='javascript:void(0)' onclick=\"edit('".$id."')\">".$title."</a></span>";
				$title	=	'['.$id.'] '.$ptitle;

				$list	.=	'
					<li id="li_'.$id.'">
					<span style="float:right;">
						<a href="javascript:void(0)" onclick="edit(\''.$id.'\')">修改</a>
						<a href="javascript:void(0)" onclick="del(\''.$id.'\')">删除</a>
					</span> '.$title.'
					</li>
					<hr style="height:1px;color:#ccc" />';
				
				$list	.=	$this->_makeLiTree($id);
			
			}
			$list	.=	'</ul>';
			
		}
		return $list;
	}
	
	
	//添加文章分类
	function doAddCategory() {
		$cate['name']	=	t($_POST['name']);
		$cate['title']	=	t($_POST['title']);
		$cate['pid']	=	$this->_digCate($_POST);
		$cate['addcontent_tpl']=	t($_POST['addcontent_tpl']);
		$cate['content_tpl']=	t($_POST['content_tpl']);
		$cate['category_tpl']=	t($_POST['category_tpl']);
		$categoryId = D('InfoCate')->add($cate);
		if($categoryId){
			header("Location:".__URL__.'/addCategory');
			//$this->success('');
			$this->assign("message",'添加分类成功！');
				$this->assign("jumpUrl", __URL__.'/addCategory');
				//$this->forward();
		}else{
			$this->error('分类添加失败！');
		}
	}
	//添加文章内容
	function addContent() {
		$tree	=	json_encode($this->_makeTree(0));
		
		$this->assign('category_json',$tree);
		$this->display();
	}
	function addP() {
		$tree	=	json_encode($this->_makeTree(0));
		$this->assign('category_json',$tree);
		$this->display();
	}
	function addV() {
		$tree	=	json_encode($this->_makeTree(0));
		$this->assign('category_json',$tree);
		$this->display();
	}
	function addM() {
		$tree	=	json_encode($this->_makeTree(0));
		$this->assign('category_json',$tree);
		$this->display();
	}
	function addD() {
		$tree	=	json_encode($this->_makeTree(0));
		$this->assign('category_json',$tree);
		$this->display();
	}
	//添加文章内容
	function doAddContent() {
	
		$map['content']		=	addslashes($_POST['content']);
		$map['title']		=	t($_POST['title']);
		$map['url']			=	t($_POST['url']);
		
		$map['author']		=	t($_POST['author']) ? t($_POST['author']) : '管理员';
		$map['category']	=	$this->_digCate($_POST);
		$map['publish_time']=	time();
		$map['user_id']		=   $this->api->user_getLoggedInUser();
		$map['cate_tree']	=	$this->_digCateTree($_POST);
		
		//上传图片
		//$path	=	'./Public/Uploads/CMS/'.date('Y-m-d',time()).'/';
		//checkDir($path);
		//$info	=	$this->_upload($path);
		//$map['img']	=	$info[0]['savepath'].$info[0]['savename'];
		$contentId	=	D('Info')->add($map);
		if($contentId){
			$this->success('内容添加成功！');
		}else{
			$this->error('内容添加失败！');
		}
	}
	
	//修改分类
   		function editCategory() {
   			$id	=	intval($_POST['id']);
   			if(isset($_POST['editSubmit'])) {
				if(!$id) $this->error('错误的分类ID！');
				if(empty($_POST['title'])){
					$this->error('名称不能为空！');
				}
			
				$cate['title']	=	t($_POST['title']);
				//$cate['pid']	=	$this->Cagetory->_digCate($_POST);  //多级分类需要处理开始
				
				$pid = $cate['pid'] = intval($_POST['pid0']);  //1级分类
				
				//$pid	=	intval($this->_digCate($_POST));
				if(!$pid){
					$this->error('分类必须选！');
				}
				if($pid==$id){
					$categoryId = D('InfoCate')->setField('title',$cate['title'],'id='.$id);
				}else{	
					$categoryId = D('InfoCate')->where("id='$id'")->save($cate);
				}
				
				
				if($categoryId){
						$this->assign("message",'修改成功！');
						$this->assign("jumpUrl", __URL__.'/addCategory');
						$this->forward();
				}else{
					$this->error('分类添加失败！');
				}
   			}
   			$vo	= D('InfoCate')->where("id=$id")->find();
   			//$tree	=	json_encode($this->_makeTree(0));
		
			//$this->assign('category_json',$tree);
			$this->assign('vo',$vo);
			$this->display();
   		}
   		
   		//删除分类
   		function delCategory() {
   			$id	=	intval($_POST['id']);
			if(D('InfoCate')->where('id='.$id)->delete()){
				$this->assign("message",'删除成功！');
				$this->assign("jumpUrl", __URL__.'/addCategory');
				$this->forward();
			}else{
				$this->error('删除失败！');
			}
	
   		}
   
	
	
	//生成分类Tree
	function _makeTree($pid) {
		$c = D('InfoCate')->where("pid='$pid'")->findAll();
		
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
	//解析分类
	function _digCate($array) {
		foreach($array as $k=>$v){
			$nk	=	str_replace('category','',$k);
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
			$nk	=	str_replace('category','',$k);
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
	function edit() {
		$model = D('Info');
		$id = $_REQUEST['id'];
		$vo	=	$model->where('id='.$id)->find();
		
		$this->assign('vo',$vo);

		$tree	=	json_encode($this->_makeTree(0));
		$this->assign('category_json',$tree);

		$this->display();
	}

	function update() {
		$model	= D('Info');
		if(false === $vo = $model->create()) {
			$this->error($model->getError());
		}
		$vo['category']	=	$this->_digCate($_POST);
		$vo['user_id']		=   $this->api->user_getLoggedInUser();
		$vo['cate_tree']	=	$this->_digCateTree($_POST);
		
		$id = intval($_POST['id']);
		if($model->where("id=$id")->save($vo)) {
			//成功提示
			$this->assign("message",'更新成功!');
			$this->assign("jumpUrl",$_POST['returnList']);
			$this->forward();
		}else {
			//错误提示
			$this->error("更新失败! ".$model->getError());
			return;
		}
	}

		/**
		 +----------------------------------------------------------
		 * 默认删除操作
		 +----------------------------------------------------------
		 * @access public
		 +----------------------------------------------------------
		 * @return string
		 +----------------------------------------------------------
		 * @throws ThinkExecption
		 +----------------------------------------------------------
		 */
		public function delete()
		{
			//删除指定记录
			$model = D('Info');
			if(!empty($model)) {
				$id = $_REQUEST['id'];
				if(isset($id)) {
					if($model->where('id In('.$id.')')->delete()){
						$this->assign("message",'删除成功！');
						$this->assign("jumpUrl", __URL__.'/index');

					}else {
						$this->error("删除失败!");
					}
				}else {
					$this->error('非法操作');
				}
				$this->forward();
			}
		}

		/**
		 +----------------------------------------------------------
		 * 默认禁用操作
		 *
		 +----------------------------------------------------------
		 * @access public
		 +----------------------------------------------------------
		 * @return string
		 +----------------------------------------------------------
		 * @throws FcsException
		 +----------------------------------------------------------
		 */
		function forbid()
		{
			$model	=	D('Info');
			$condition = 'id IN ('.$_GET['id'].')';
			if($model->where($model->setField('status','0', $condition))){
				$this->assign("message",'状态禁用成功！');
				$this->assign("jumpUrl", __URL__.'/index');
			}else {
				$this->assign('error',  '状态禁用失败！');
			}
			$this->forward();
		}

		/**
		 +----------------------------------------------------------
		 * 默认恢复操作
		 *
		 +----------------------------------------------------------
		 * @access public
		 +----------------------------------------------------------
		 * @return string
		 +----------------------------------------------------------
		 * @throws FcsException
		 +----------------------------------------------------------
		 */
		function resume()
		{
			//恢复指定记录
			$model	=	D('Info');
			$condition = 'id IN ('.$_GET['id'].')';
			
			if($model->where($model->setField('status','1', $condition))){
				$this->assign("message",'状态恢复成功！');
				$this->assign("jumpUrl", __URL__.'/index');
			}else {
				$this->assign('error',  '状态恢复失败！');
			}
			$this->forward();
		}

	

		function setnew()
		{
			$model	=	D('Info');
			$condition = 'id IN ('.$_GET['id'].')';
			if($model->setField('newicon','1',$condition)){
				$this->assign("message",'文章置新成功！');
				$this->assign("jumpUrl",$this->getReturnUrl());
			}else {
				$this->assign('error',  '文章置新失败！');
			}
			$this->forward();
		}
		
		function unsetnew()
		{
			//恢复指定记录
			$model	=	D('Info');
			$condition = 'id IN ('.$_GET['id'].')';
			if($model->setField('newicon','0',$condition)){
				$this->assign("message",'文章恢复成功！');
				$this->assign("jumpUrl",$this->getReturnUrl());
			}else {
				$this->assign('error',  '文章恢复失败！');
			}
			$this->forward();
		}
	
}
?>