<?php
/**
 * AdminAction
 * 相册管理
 * @uses Action
 * @package Admin
 * @version $2009-7-29$
 * @copyright 2009-2011 LiuXiaoqing
 * @author LiuXiaoqing <liuxiaoqing@thinksns.com>
 * @license ThinkSNS Version 1.6
 */
class AdminAction extends Administrator{
	var $appname;

	/**
	 * _initialize
	 * 初始化相册管理
	 * @access public
	 * @return void
	 */
	public function _initialize() {
		$this->appname	=	'photo';
	}

	/**
	 * index
	 * 获取配置信息
	 * @access public
	 * @return void
	 */
    public function index(){
		//获取配置
		$config		=	D('AppConfig')->getConfig();
		$this->assign($config);
		$this->display();
    }

	/**
	 * do_change_config
	 * 更改相册配置
	 * @access public
	 * @return void
	 */
	public function do_change_config() {
		//变量过滤 todo:更细致的过滤
		foreach($_POST as $k=>$v){
			$map[$k]	=	t($v);
		}
		$result	=	D('AppConfig')->editConfig($map);
		if($result){
			$this->success('相册设置成功！');
		}else{
			$this->error('相册设置失败！');
		}
	}

	/**
	 * album_list
	 * 专辑管理
	 * @access public
	 * @return void
	 */
    public function album_list(){

		$map['isDel']	=	0;

		$list		=	D('Album')->order('id DESC')->where($map)->findPage(20);

		$this->assign($list);
		$this->display();
    }

	/**
	 * photo_list
	 * 照片管理
	 * @access public
	 * @return void
	 */
    public function photo_list(){

		$map['isDel']	=	0;

		$list		=	D('Photo')->order('id DESC')->where($map)->findPage(20);

		$this->assign($list);
		$this->display();
    }

	/**
	 * photo_recycle
	 * 照片回收站管理
	 * @access public
	 * @return void
	 */
    public function photo_recycle(){

		$map['isDel']	=	1;

		$list		=	D('Photo')->order('id DESC')->where($map)->findPage(20);

		$this->assign($list);
		$this->display();
    }

	/**
	 * album_recycle
	 * 相册回收站管理
	 * @access public
	 * @return void
	 */
    public function album_recycle(){

		$map['isDel']	=	1;

		$list		=	D('Album')->order('id DESC')->where($map)->findPage(20);

		$this->assign($list);
		$this->display();
    }

	/**
	 * delete_photo
	 * 删除照片
	 * @access public
	 * @return void
	 */
	public function delete_photo() {
		$map['id']		=	t($_REQUEST['id']);

		$result	=	D('Album')->deletePhoto($map['id'],$this->mid,1);
		if($result){
			//删除成功
			//同步个人空间照片数
			$photoCount	=	D('Photo')->where("userId='$this->mid' AND isDel=0")->count();
			$this->api->space_changeCount( 'photo',$photoCount );

			echo "1";exit;
		}else{
			//删除失败
			echo "0";exit;
		}
	}

	/**
	 * delete_album
	 * 解锁照片或相册
	 * @access public
	 * @return void
	 */
	public function delete_album() {
		$map['id']		=	t($_REQUEST['id']);

		$result	=	D('Album')->deleteAlbum($map['id'],$this->mid,1);

		if($result){
			//删除成功
			//同步个人空间照片数
			$photoCount	=	D('Photo')->where("userId='$this->mid' AND isDel=0")->count();
			$this->api->space_changeCount( 'photo',$photoCount );
			echo "1";exit;
		}else{
			//删除失败
			echo "0";exit;
		}
	}

	/**
	 * restore_photo
	 * 恢复照片
	 * @access public
	 * @return void
	 */
	public function restore_photo() {
		$map['id']		=	t($_REQUEST['id']);

		$result	=	D('Album')->restorePhoto($map['id'],$this->mid,1);
		if($result){
			//删除成功
			//同步个人空间照片数
			$photoCount	=	D('Photo')->where("userId='$this->mid' AND isDel=0")->count();
			$this->api->space_changeCount( 'photo',$photoCount );

			echo "1";exit;
		}else{
			//删除失败
			echo "0";exit;
		}
	}

	/**
	 * restore_album
	 * 恢复照片
	 * @access public
	 * @return void
	 */
	public function restore_album() {
		$map['id']		=	t($_REQUEST['id']);

		$result	=	D('Album')->restoreAlbum($map['id'],$this->mid,1);
		if($result){
			//删除成功
			//同步个人空间照片数
			$photoCount	=	D('Photo')->where("userId='$this->mid' AND isDel=0")->count();
			$this->api->space_changeCount( 'photo',$photoCount );

			echo "1";exit;
		}else{
			//删除失败
			echo "0";exit;
		}
	}

	/**
	 * clean_photo
	 * 彻底清除回收站的照片
	 * @access public
	 * @return void
	 */
    public function clean_photo(){

		$ids	=	t($_REQUEST['id']);

		//解析ID成数组
		if(!is_array($ids)){
			$aids	=	explode(',',$ids);
		}

		$map['id']		=	array('in',$aids);
		$map['isDel']	=	1;

		$result		=	D('Photo')->where($map)->delete();

		if($result){
			echo "1";exit;
		}else{
			//删除失败
			echo "0";exit;
		}
    }

	/**
	 * clean_album
	 * 彻底清除回收站的相册
	 * @access public
	 * @return void
	 */
    public function clean_album(){

		$ids	=	t($_REQUEST['id']);

		//解析ID成数组
		if(!is_array($ids)){
			$aids	=	explode(',',$ids);
		}

		$map['id']		=	array('in',$aids);
		$map['isDel']	=	1;

		$result		=	D('Album')->where($map)->delete();

		if($result){
			echo "1";exit;
		}else{
			//删除失败
			echo "0";exit;
		}
    }
}
?>