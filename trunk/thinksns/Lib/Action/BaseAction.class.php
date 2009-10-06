<?php

class BaseAction extends Action
{

    var $sex;
	protected  function _initialize(){

        //执行计划任务
		A('Cron')->run();
	}

	//执行单图上传操作
	protected function _upload($path,$save_name,$is_replace,$is_thumb,$thumb_name,$thumb_max_width) {

		if(!checkDir($path)){
			return '目录创建失败: '.$path;
		}

		import("ORG.Net.UploadFile");

        $upload = new UploadFile();

        //设置上传文件大小
        $upload->maxSize	=	'2000000' ;

        //设置上传文件类型
        $upload->allowExts	=	explode(',',strtolower('jpg,gif,png,jpeg,bmp'));

		//存储规则
		$upload->saveRule	=	'uniqid';
		//设置上传路径
		$upload->savePath	=	$path;
        //保存的名字
        $upload->saveName   =   $save_name;
        //是否缩略图
        $upload->thumb          =   $is_thumb;
        $upload->thumbMaxWidth  =   $thumb_max_width;
        $upload->thumbFile      =   $thumb_name;

        //存在是否覆盖
        $upload->uploadReplace  =   $is_replace;
        //执行上传操作
        if(!$upload->upload()) {
            //捕获上传异常
            return $upload->getErrorMsg();
        }else{
			//上传成功
			return $upload->getUploadFileInfo();
    	}
    }
}
?>