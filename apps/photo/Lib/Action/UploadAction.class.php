<?php
//相册应用 - UploadAction 上传照片 及 处理
class UploadAction extends BaseAction{
	//普通上传
	public function index() {
		$this->display();
	}

	//flash上传
	public function flash() {
		$max_flash_upload_num	=	intval($this->setting['max_flash_upload_num']);
		if($max_flash_upload_num==0){
			$max_flash_upload_num	=	10;
		}
		$this->assign('max_flash_upload_num',$max_flash_upload_num);
		$this->display();
	}

	//在线拍照
	public function camera() {
		if($this->setting['open_camera']){
			$this->error('摄像头拍照功能已关闭！');
		}
		$this->display();
	}

	//执行在线拍照
	public function docamera(){
		if($this->setting['open_camera']){
			$this->error('摄像头拍照功能已关闭！');
		}
		$jpg	=	$GLOBALS["HTTP_RAW_POST_DATA"];
		header('Content-Type: image/jpeg');
		echo $jpg;
	}

	//执行单张照片上传
    public function upload_single_pic(){

		$albumId	=	intval($_REQUEST['albumId']);

		$options['userId']		=	$this->mid;
		$options['allow_exts']	=	'jpg,bmp,gif,png';
		$options['save_photo']['albumId']	=	$albumId;

		$info		=	$this->api->attach_upload('photo',$options);
		if($info['status']){

			//启用session记录flash上传的图片数，也可以防止意外提交。
			$upload_count	=	intval($_SESSION['upload_count']);
			$_SESSION['upload_count']	=	$upload_count + 1;

			//重置相册照片数
			D('Album')->updateAlbumPhotoCount($albumId);

			//上传成功
			echo json_encode($info);
		}else{
			//上传出错
			echo "0";
		}
    }

	//执行多张照片上传
	public function upload_muti_pic() {

		$albumId	=	intval($_REQUEST['albumId']);

		$options['userId']		=	$this->mid;
		$options['allow_exts']	=	'jpg,bmp,gif,png';
		$options['save_photo']['albumId']	=	$albumId;

		$info		=	$this->api->attach_upload('photo',$options);

		if($info['status']){

			//启用session记录flash上传的图片数，也可以防止意外提交。
			$_SESSION['upload_count']	=	count($info['info']);

			//重置相册照片数
			D('Album')->updateAlbumPhotoCount($albumId);

			$this->redirect('/Upload/muti_edit_photos/albumId/'.$albumId);

		}else{
			$this->error('上传出错：'.$info['info']);
		}
	}

	//上传后执行编辑操作
	public function muti_edit_photos() {

		//判断session,防止意外提交
		if( intval($_SESSION['upload_count']) > 0 ){
			$upnum	=	intval($_SESSION['upload_count']);
			unset($_SESSION['upload_count']);
		}else{
			$this->error('上传错误，请正常提交！不要多次点击 "保存照片信息" 按钮！');
		}

		$albumId	=	intval($_REQUEST['albumId']);
		$albumInfo	=	D('Album')->find($albumId);

		if(!$albumInfo){
			$this->error('请上传到指定的相册！');
		}

		//公开相册发布动态
		if($albumInfo['privacy']<=2){
			$this->assign('publish_feed',1);
		}

		if( $upnum > 0 ) {

			//同步个人空间照片数
			$photoCount	=	D('Photo')->where("userId='$this->mid' AND isDel=0")->count();
			$this->api->space_changeCount( 'photo',$photoCount );

			//
			$photos		=	D('Photo')->limit($upnum)->order("cTime DESC")->where("userId='$this->mid'")->findAll();
			$this->assign('photos',$photos);
			$this->assign('album',$albumInfo);
			$this->assign('upnum',$upnum);
			$this->display();

		}else{

			$this->error('上传出错：没有上传任何照片！');
		}
	}

	//保存上传的照片
	public function save_upload_photos() {

		//相册信息
		$albumId		=	intval($_POST['albumId']);
		$album_cover	=	intval($_POST['album_cover']);
		$publish_feed	=	intval($_POST['publish_feed']);
		$upnum			=	intval($_POST['upnum']);

		$albumInfo		=	D('Album')->find($albumId);

		if(!$albumInfo){
			$this->error('请先正确选择相册，再上传图片！');
		}

		//发布动态
		if($publish_feed==1){
			/* 提交动态 - 动态模版中{__SITE_PATH__}路径问题需要解决 */
				$photos		=	D('Photo')->limit($upnum)->order("cTime DESC")->where("userId='$this->mid'")->findAll();
				foreach($photos as $k=>$v){
					$pic_data[$k]['pid']		=	$v['id'];
					$pic_data[$k]['uid']		=	$v['userId'];
					$pic_data[$k]['aid']		=	$v['albumId'];
					$pic_data[$k]['savepath']	=	$v['savepath'];

					//动态中取前5张显示，应该在后台配置
					if($k<4){
						$pic	.=	'<span style="margin:2px;"><a href="{SITE_URL}/apps/photo/index.php//Index/photo/id/'.$v['id'].'/aid/'.$v['albumId'].'/uid/'.$v['userId'].'"><img src="{SITE_URL}/thumb.php?w=120&w=100&t=f&url={UPLOAD_URL}'.$v['savepath'].'" width=80 /></a></span>';
					}

				}
				$title_data['num']			=	$upnum;
				$title_data['album']	=	'<a href="{SITE_URL}/apps/photo/index.php/Index/album/id/'.$albumInfo['id'].'/uid/'.$albumInfo['userId'].'">'.$albumInfo['name'].'</a>';
				$body_data['pic']			=	$pic.'<span style="margin:2px;"><a href="{SITE_URL}/apps/photo/index.php/Index/album/id/'.$albumInfo['id'].'/uid/'.$albumInfo['userId'].'">全部照片>></a></span>';
				$body_data['pic_data']		=	$pic_data;
				$this->api->feed_publish("photo",$title_data,$body_data,$this->appId);
			/* 提交动态 */
		}

		//保存照片数据
		foreach($_POST['name'] as $k=>$v){
			$data['name']		=	$v['name'];
			D('Photo')->where($photo_map)->save($map);
		}

		//保存相册数据
		D('Album')->setAlbumCover($albumId,$album_cover);

		//跳转到相册页面
		$this->redirect('/Index/album/id/'.$albumId.'/uid/'.$this->mid);
	}
}
?>