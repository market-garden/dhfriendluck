<?php
class AttachModel extends Model{

	//执行上传操作,存储目录,文件名,其他选项
	public function upload($attach_type='attach_type',$path,$save_name,$options=array()){

		//检查文件名和目录是否为空
		if(empty($path)){
			$path	=	C('UPLOAD_PATH').'/'.date('Ymd').'/';
		}

		//创建目录
		if(!checkDir($path)){
			$return['status']	=	false;
			$return['info']		=	'目录创建失败: '.$path;
			return	$return;
		}

		import("ORG.Net.UploadFile");
        $upload = new UploadFile();

		//设置上传文件大小
		$upload->maxSize		=	(empty($options['maxsize']))?'2000000':$options['maxsize'];

        //设置上传文件类型
        $upload->allowExts		=	(empty($options['allowExts']))?explode(',',strtolower('jpg,gif,png,jpeg,bmp')):explode(',',$options['allowExts']);

		//存储规则
		$upload->saveRule		=	'uniqid';
		//设置上传路径
		$upload->savePath		=	$path;
        //保存的名字
        $upload->saveName		=   $save_name;
        //是否缩略图
        $upload->thumb          =   (empty($options['is_thumb']))?false:$options['is_thumb'];
        $upload->thumbMaxWidth  =   (empty($options['thumb_width']))?'200':$options['thumb_width'];
        $upload->thumbFile      =   (empty($options['thumb_file']))?'thumb_'.$save_name:$options['thumb_file'];

        //存在是否覆盖
        $upload->uploadReplace  =   (empty($options['is_replace']))?true:$options['is_replace'];

		//执行上传操作
        if(!$upload->upload()) {

			//上传失败，返回错误
			$return['status']	=	false;
			$return['info']		=	$upload->getErrorMsg();
			return	$return;

		}else{

			$upload_info		=	$upload->getUploadFileInfo();

			//保存信息到附件表
			$api	=	new TS_API();
			$uid	=	$api->user_getLoggedInUser();
			foreach($upload_info as $u){
				unset($map);
				$map['attach_type']	=	$attach_type;
				$map['userId']		=	$uid;
				$map['uploadTime']	=	time();
				$map['name']		=	$u['name'];
				$map['type']		=	$u['type'];
				$map['size']		=	$u['size'];
				$map['extension']	=	$u['extension'];
				$map['hash']		=	$u['hash'];
				$map['savepath']	=	$u['savepath'];
				$map['savename']	=	$u['savename'];
				//$map['savedomain']=	C('ATTACH_SAVE_DOMAIN');
				$result	=	$this->add($map);
				$map['id']	=	$result;
				$infos[]	=	$map;
			}

			//输出信息
			$return['status']	=	true;
			$return['info']		=	$infos;
			//上传成功，返回信息
			return	$return;
    	}
	}

    public function getAttach( $map ){
        $map = array_filter( $map );
        $attach = $this->where( $map )->field( 'id,savepath,savename' )->findAll();

        if( false == $attach )  return false;

        //重组数据结构
        $result = array();
        foreach( $attach as $value ){
            $result[$value['id']] = array( $value['savepath'],$value['savename'] );
        }
        return $result;
    }



    public function getOneAttach( $id ){
        $result = $this->where( 'id='.$id )->field( 'id,savepath,savename' )->find();
        return $result['savepath'].$result['savename'];
    }
}
