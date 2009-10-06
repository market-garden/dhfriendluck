<?php
//附件管理
class AttachAction extends  BaseAction{

	//我的附件列表
	function index(){
		$aid	=	intval($_REQUEST['id']);
		$uid	=	intval($_REQUEST['uid']);
		$attach	=	D('Attach')->where("id='$aid' AND userId='$uid'")->find();
		if(!$attach){
			$this->error('附件不存在或已被删除！');
		}
		//下载函数
		import("ORG.Net.Http");             //调用下载类
		if(file_exists(UPLOAD_PATH.$attach['savepath'].$attach['savename'])) {
			$filename = iconv("utf-8",'gb2312',$attach['savename']);
			Http::download(UPLOAD_PATH.$attach['savepath'].$attach['savename'],$filename);
		}else{
			$this->error('附件不存在或已被删除！');
		}
	}

	//上传附件
	function ajax_upload(){
		//获取总分类
		$attach_type	=	t($_REQUEST['type']);

		//执行附件上传操作
		if($this->uid==$this->mid){

			$options['userId']		=	$this->mid;
			//$options['allow_exts']	=	'jpg,gif,png,bmp,doc,docx,ppt,pptx,pdf,zip,rar';
			$options['allow_exts']	=	'jpg,gif,png,bmp,zip,rar';
			$info	=	$this->api->attach_upload($attach_type,$options);

			//上传成功
			echo json_encode($info);

		}else{
			echo "-1";	//没有权限，需要登陆后上传
		}
	}

}
?>