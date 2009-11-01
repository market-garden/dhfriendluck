<?php

class DirAction extends BaseAction {
	
	var $dir;
	public function _initialize(){
		parent::_initialize();
		//系统关闭状态
		
		if(!$this->groupinfo['openUploadFile']) $this->error('群文件共享关闭状态');   
		$this->dir = D('Dir');
		$this->assign('current','dir');
	}
	
	//相册列表
	function index() {
		
		$fileList = $this->dir->getFileList($html=1,array("gid={$this->gid}"),null,'ctime DESC');
		$usedSpace = $this->dir->where('gid='.$this->gid.' AND is_del=0')->sum('filesize');
		
		$this->assign('usedRate',($usedSpace/($this->config['spaceSize']*1024*1024)));  //空间使用率
		
		$this->assign('usedSpace',$usedSpace);  //使用空间大小
		$this->assign('fileList', $fileList);
		$this->setTitle($this->siteTitle['file_index']);
		$this->display();
	}
	
	
	function file() {
		$fid = intval($_GET['fid']) > 0 ?  intval($_GET['fid']) : 0;
		if($fid == 0) exit();
		
		$fileInfo = $this->dir->where('id='.$fid.' AND is_del=0')->find();
		if(!$fileInfo) $this->error('文件不存在');
		$this->assign('fileInfo',$fileInfo);
		$this->setTitle($fileInfo['name']);
		$this->display();
	}
	
	//上传文件
	function upload() {
		//权限判读 用户没有加入该群组
		if(!isJoinGroup($this->mid,$this->gid)){
			$this->alert();
		}
		//系统后台配置仅管理员可以上传
		if($this->groupinfo['whoUploadFile'] == 1 && !$this->isadmin) {
			$this->error('对不起，仅管理员可以上传文件');
		}
		
		$usedSpace = $this->dir->where('gid='.$this->gid.' AND is_del=0')->sum('filesize'); //判读空间大小

		
		if($usedSpace > $this->config['spaceSize']*1024*1024) {$this->error('空间已经使用完！');} //如果使用完成，提示错误信息
		
		//设置上传文件大小
       	$upload['max_size']  = $this->config['simpleFileSize']*1024*1024 ;
       		 
        //设置上传文件类型
        $upload['allow_exts']  = str_replace('|',',',$this->config['uploadFileType']);
		
		if(isset($_POST['uploadsubmit'])) {
			if($_FILES['uploadfile']['size'] == 0) exit('请选择上传文件');
		
        	
        	$info		=	$this->api->attach_upload('group_file',$upload);
        	
        	//执行上传操作
        	if($info['status']){  //上传成功
        		list($uploadFileInfo) = $info['info'];
        		
        		$attchement['gid'] = $this->gid;
        		$attchement['uid'] = $this->mid;
        		$attchement['attachId'] = $uploadFileInfo['id'];
        		$attchement['name'] = $uploadFileInfo['name'];
        		$attchement['note'] = !empty($_POST['note']) ? t($_POST['note]']) : '';
            	$attchement['filesize'] = $uploadFileInfo['size'];
            	$attchement['filetype'] = $uploadFileInfo['extension'];
            	$attchement['fileurl'] = $uploadFileInfo['savepath'].$uploadFileInfo['savename'];
            	$attchement['ctime'] = time();
            	$result = $this->dir->add($attchement);
            	
            	
            	if($result) {
            		
            		//添加动态
            		
					$title_data["actor"] = getUserName($this->mid);
					$title_data['gid'] = $this->gid;
					$title_data['group_name'] = $this->groupinfo['name'];
					
   					//$body_data['url'] = __APP__."/Dir/file/gid/{$this->gid}/fid/".$result;
   					$body_data['name'] = $uploadFileInfo['name'];
   					$body_data['gid'] = $this->gid;
   					$body_data['fid'] = $result;
   					
   					$appid= 'group_'.$this->gid;
				   
            		$this->api->feed_publish('group_file',$title_data,$body_data,$this->appId,0,$this->gid);
            		
            		setScore($this->mid,'group_file_upload');
            		$this->assign('fid',$result);
        			$this->assign('uploadSuccess',true);
            	}else{
            		$this->error('上传失败');
            	}
        	}else{
        		$this->error($info['info']);
        	}
		}
		
		$this->setTitle($this->siteTitle['file_upload']);
		$this->assign('upload',$upload);
		$this->display();
	}
	
	//下载
	function download() {
		
		//权限判读 用户没有加入过
		if(!isJoinGroup($this->mid,$this->gid)){
			$this->alert();
		}
		
		$fid = intval($_POST['fid']) > 0 ?  intval($_POST['fid']) : 0;
		if($fid == 0) exit();
		//下载函数
		import("ORG.Net.Http");             //调用下载类
		
		$fileInfo = $this->dir->where('id='.$fid.' AND is_del=0')->find();
		
		
		
		if($fileInfo['fileurl'] && file_exists('../../data/uploads/'.$fileInfo['fileurl'])) {
	   		$this->dir->setInc('totaldowns','id='.$fid);             //增加下载次数
	   		//header("content-type:text/html; charset=utf-8");
	   		//echo $fileInfo['name'];
			//exit;
			$fileInfo['name'] = iconv("utf-8",'gb2312',$fileInfo['name']);
			Http::download('../../data/uploads/'.$fileInfo['fileurl'],$fileInfo['name']);
			
		}
		$this->error('文件不存在');
	}
	
	//删除文件
	function delfile() {
		$fid = intval($_POST['fid']) > 0 ?  intval($_POST['fid']) : 0;
		
		$file = $this->dir->find($fid);
		if($fid == 0 || empty($file)) exit();
		
		//权限判读 管理者，或者用户自己删除
		if(!($this->isadmin || $this->mid == $file['uid'])){
			exit('你没有权限');
		}
		
		if($this->dir->remove($fid)) {
			setScore($this->mid,'group_file_delete');
			$this->redirect('Dir/index/gid/'.$this->gid);
		}else{
			$this->error('删除失败');
		}
		
	}
	
	function deldialog() {
		$this->display();
	}

	
	//修改文件
	function editfile() {
		
		//判段权限
		$fid = intval($_POST['fid']) > 0 ?  intval($_POST['fid']) : 0;
		$file = $this->dir->find($fid);
		if($fid == 0 || empty($file)) exit();
		//权限判读 管理者，或者用户自己删除
		if(!$this->isadmin || $this->mid != $file['uid'] ){
			exit('你没有权限');
		}
		
		$note = isset($_POST['note']) ?  $_POST['note'] : '';
		$this->dir->where('id='.$fid)->setField('note',$note);
	}
	
	function editdialog() {
		$this->display();
	}
}

?>