<?php
    /**
     * AdminAction 
     * 群组管理
     * @uses Action
     * @package Admin
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
   class AdminAction extends Administrator {
   		var $GroupSetting;
   		var $Cagetory;
   		 public function _initialize(){
            $this->GroupSetting  = D('GroupSetting' );
            //$this->config = D( 'BlogConfig' );
            $this->Cagetory = D('Category');
            
        }
   		/**
         * basic 
         * 基础设置管理
         * @access public
         * @return void
         */
   		function index() {
   			
   			if(isset($_POST['editSubmit']) == 'do') {
   				$setting = $this->GroupSetting->create();
   				
   				$this->GroupSetting->add($setting);
   			}
   			
   			$GroupSetting = $this->GroupSetting->getGroupSetting();
   			$categoryList	=	$this->Cagetory->getCategoryList(0);
   			
			$this->assign('categoryList',$categoryList);
   			$this->assign('setting',$GroupSetting);
   		
   			$this->display();
   		}
   		
   		/**
         * basic 
         * 管理
         * @access public
         * @return void
         */
   		function manage() {
   			$type = !empty($_REQUEST['type']) ?  trim($_REQUEST['type']) : 'group';
   			$uid = intval($_POST['uid']) ? intval($_POST['uid']) : '';
   			$username = !empty($_POST['name']) ?  trim($_POST['name']) : '';
   			$title = !empty($_POST['title']) ?  trim($_POST['title']) : '';
   			$content = !empty($_POST['content']) ?  trim($_POST['content']) : '';
   			$field = !empty($_POST['field']) ?  trim($_POST['field']) : 'id';
   			$asc = !empty($_POST['asc']) ?  trim($_POST['asc']) : 'desc';
   			$limit = !empty($_POST['limit']) ?  trim($_POST['limit']) : 10;
   			//传递参数
   			$this->assign('uid',$uid);
   			$this->assign('title',$title);
   			$this->assign('content',$content);
   			$this->assign('field',$field);
   			$this->assign('asc',$asc);
   			$this->assign('limit',$limit);
   			$this->assign('name',$username);
   			$data = $this->GroupSetting->searchData($type,$uid,$username,$title,$content,$field,$asc,$limit);
   			
   			//群组
   			if($type == 'group'){
   				
   				$this->assign('groupData',$data);
   			}
   			//话题
   			elseif($type == 'topic'){
   	
   				$this->assign('topicData',$data);
   				$this->display('managetopic');
   				exit;
   			}
   			//相册
   			elseif($type == 'album'){
   				
   				
   				$this->assign('albumData',$data);
   				$this->display('managealbum');
   				exit;
   			}elseif ($type == 'photo'){
   				
   				$this->assign('photoData',$data);
   				$this->display('managephoto');
   				exit;
   			}
   			//文件
   			elseif($type == 'file'){
   				
   				$this->assign('fileData',$data);
   				$this->display('managefile');
   				exit;
   			}
   			//回帖
   			elseif($type == 'post'){
   				
   			
   				$this->assign('postData',$data);
   				$this->display('managepost');
   				exit;
   			}
   			$this->display();
   		}
   		
   		/**
         * basic 
         * 回收站
         * @access public
         * @return void
         */
   		function recycle() {
   			$type = !empty($_REQUEST['type']) ?  trim($_REQUEST['type']) : 'group';
   			$limit = !empty($_POST['limit']) ?  trim($_POST['limit']) : 10;
   			$title = !empty($_POST['title']) ?  trim($_POST['title']) : '';
   			$field = !empty($_POST['field']) ?  trim($_POST['field']) : 'id';
   			$asc = !empty($_POST['asc']) ?  trim($_POST['asc']) : 'desc';
   			
   			//传递参数
   			$this->assign('uid',$uid);
   			$this->assign('title',$title);
   			$this->assign('content',$content);
   			$this->assign('field',$field);
   			$this->assign('asc',$asc);
   			$this->assign('limit',$limit);
   			
   			$data = $this->GroupSetting->searchData($type,$uid,$username,$title,$content,$field,$asc,$limit,1);
   			//群组
   			if($type == 'group'){
   				
   				$this->assign('groupData',$data);
   			}
   			//话题
   			elseif($type == 'topic'){
   				$this->assign('topicData',$data);
   			
   				$this->display('recycletopic');
   				exit;
   			}
   			//相册
   			elseif($type == 'album'){ 
   				$this->assign('albumData',$data);
   				$this->display('recyclealbum');
   				exit;
   			}elseif ($type == 'photo'){
   				
   				$this->assign('photoData',$data);
   				$this->display('recyclephoto');
   				exit;
   			}
   			//文件
   			elseif($type == 'file'){
   				
   				$this->assign('fileData',$data);
   				$this->display('recyclefile');
   				exit;
   			}
   			//回帖
   			elseif($type == 'post'){
   			
   			    $this->assign('postData',$data);
   				$this->display('recyclepost');
   				exit;
   			}
   			$this->display();
   		}
   		
   		//放入回收站
   		function remove(){
   			$type = !empty($_POST['act']) ?  trim($_POST['act']) : 'group';
   			$ids = isset($_POST['id']) ? $_POST['id'] : 0;
   			
   			//群组  关联 群组，话题，文件，相册，话题回复
   			if($type == 'group'){
   				D('Group')->remove($ids);
   			}
   			//话题
   			elseif($type == 'topic'){
   				D('Topic')->remove($ids);	
   			}
   			//相册
   			elseif($type == 'album'){ 
   				D('Album')->deleteAlbum($ids);
   			}elseif ($type == 'photo') {
   				D('Album')->deletePhoto($ids);
   			}
   			//文件
   			elseif($type == 'file'){
   				D('Dir')->remove($ids);
   			}
   			//回帖
   			elseif($type == 'post'){
   				
   				D('Post')->remove($ids);	
   			}
   		}
   		
   		//删除
   		function delete(){
   			$type = !empty($_POST['act']) ?  trim($_POST['act']) : 'group';
   			$ids = isset($_POST['id']) ? $_POST['id'] : 0;
   			
   			//群组  关联 群组，话题，文件，相册，话题回复
   			if($type == 'group'){
   				D('Group')->del($ids);
   			}
   			//话题
   			elseif($type == 'topic'){
   				D('Topic')->del($ids);	
   			}
   			//相册
   			elseif($type == 'album'){ 
   				D('Album')->removePhoto($ids);
   			}elseif($type == 'photo'){
   				
   				D('Album')->removePhoto($ids);
   			}
   			//文件
   			elseif($type == 'file'){
   				
   				D('Dir')->del($ids);
   			}
   			//回帖
   			elseif($type == 'post'){
   				
   				D('Post')->del($ids);	
   			}
   		}
   		
   		
   		
   		//恢复内容
		function recover(){
			$type = !empty($_POST['act']) ?  trim($_POST['act']) : 'group';
   			$ids = isset($_POST['id']) ? $_POST['id'] : 0;
   			
   			//群组  关联 群组，话题，文件，相册，话题回复
   			//话题
   			if($type == 'topic'){
   				D('Topic')->recover($ids);	
   			}
   			//相册  
   			elseif($type == 'album'){ 
   				D('Album')->recoverAlbum($ids);
   			}elseif ($type == 'photo') {
   				D('Album')->recoverPhoto($ids);
   			}
   			//文件
   			elseif($type == 'file'){
   				D('Dir')->recover($ids);
   			}
   			//回帖
   			elseif($type == 'post'){
   				
   				D('Post')->recover($ids);	
   			}
		}
		
		//群组设置推荐
		function recom(){
			$type = !empty($_POST['act']) ?  trim($_POST['act']) : 'group';
			$id = !empty($_POST['id']) ?  trim($_POST['id']) : 0;
			$isrecom = !empty($_POST['isrecom']) ?  trim($_POST['isrecom']) : 0;
			
			if($type == 'group'){
				D('Group')->where('id='.$id)->setField('isrecom',$isrecom);
				
			}elseif($type == 'topic'){
				D('Topic')->where('id='.$id)->setField('isrecom',$isrecom);
				
			}
			return false;
		}
   		
   		
   		
   		
   		
   		//添加分类
   		function addCategory() {
   			
   			if(empty($_POST['title'])){
				$this->error('名称不能为空！');
			}
		
			$cate['name']	=	t($_POST['name']);
			$cate['title']	=	t($_POST['title']);
			//dump($_POST);
			
			//$cate['pid']	=	$this->Cagetory->_digCate($_POST); //多级分类需要打开
			$cate['pid'] = intval($_POST['cid0']);  //1级分类
			$categoryId = $this->Cagetory->add($cate);
			if($categoryId){
				
				header("Location:".__APP__."/Admin");
			}else{
				$this->error('分类添加失败！');
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
				
				//$pid	=	intval($this->Cagetory->_digCate($_POST));
				if(!$pid){
					$this->error('分类必须选！');
				}
				if($pid==$id){
					$categoryId = $this->Cagetory->setField('title',$cate['title'],'id='.$id);
				}else{	
					$categoryId = $this->Cagetory->where("id='$id'")->save($cate);
				}
				
				
				if($categoryId){
					header("Location:".__APP__."/Admin");
				}else{
					$this->error('分类添加失败！');
				}
   			}
   			$vo	= $this->Cagetory->where("id=$id")->find();
			$this->assign('vo',$vo);
			$this->display();
   		}
   		
   		//删除分类
   		function delCategory() {
   			$id	=	intval($_POST['id']);
			if($this->Cagetory->where('id='.$id)->delete()){
				header("Location:".__APP__."/Admin");
			}else{
				$this->error('删除失败！');
			}
	
   		}
   
   }
