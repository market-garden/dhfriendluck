<?php
//相册应用 - indexaction 照片和专辑的列表
class IndexAction extends BaseAction{
	public function _initialize() {
		parent::_initialize();
	}

	public function index(){
		//相册应用介绍页面
		$this->redirect('/Index/photos/uid/'.$this->mid);
    }

	//显示一张照片
	public function photo() {

		$id		=	intval($_REQUEST['id']);
		$aid	=	intval($_REQUEST['aid']);
		$uid	=	intval($_REQUEST['uid']);
		$type	=	t($_REQUEST['type']);	//照片来源类型，来自某相册，还是其他的

		//判断来源类型
		if(!empty($type) && !in_array($type,array('album','mAll','fAll'))){
			$this->error('错误的链接！');
		}
		$this->assign('type',$type);

		//获取照片信息
		$photo	=	D('Photo')->where(" id='$id' AND albumId='$aid' AND userId='$uid' AND isDel=0 ")->find();
		$this->assign('photo',$photo);

		//验证照片信息是否正确
		if(!$photo){
			$this->error('照片不存在或已被删除！');
		}

		//获取所在相册信息
		$album	=	D('Album')->find($aid);
		$this->assign('album',$album);

		//隐私控制
		if($this->mid!=$photo['userId']){
			$album_privacy	=	get_privacy_code($album['privacy']);
			$relationship	=	check_relationship($uid);

			if($album_privacy=='self' && $relationship!='self'){
				$this->error('这张照片，只有主人自己可见。');
			}else
			if($album_privacy=='friend' && $relationship=='stranger'){
				$this->error('这张照片，只有主人的好友可见。');
			}else
			if($album_privacy=='password'){
				$cookie_password	=	Cookie::get('album_password_'.$aid);
				if($cookie_password	!= md5($album['privacy_data'].'_'.$aid.'_'.$uid)){
					$this->redirect('/Index/need_password/uid/'.$uid.'/aid/'.$aid.'/pid/'.$id);
				}
			}
		}

		$order	=	$this->setting['album_default_order'];

		//获取所有照片数据
		$photos	=	D('Album')->getPhotos($uid,$aid,$type,$order,5);
		$this->assign('photos',$photos);


		//获取上一页 下一页 和 预览图
		if($photos){
			foreach($photos as $v){
				$photoIds[]	=	intval($v['id']);
			}
			$photoCount	=	count($photoIds);

			//颠倒数组，取索引
			$pindex		=	array_flip($photoIds);

			//当前位置索引
			$now_index	=	$pindex[$id];

			//上一张
			$pre_index	=	$now_index-1;
			if( $now_index <= 0 )	{
				$pre_index	=	$photoCount-1;
			}
			$pre_photo	=	$photos[$pre_index];

			//下一张
			$next_index	=	$now_index+1;
			if( $now_index >= $photoCount-1 ) {
				$next_index	=	0;
			}
			$next_photo	=	$photos[$next_index];

			//预览图的位置索引
			$start_index	=	$now_index - 2;
			if( ($photoCount+1-$now_index)<2){
				$start_index	=	($photoCount+1-5);
			}
			if($start_index<0){
				$start_index	=	0;
			}

			//取出预览图列表 最多5个
			$preview_photos	=	array_slice($photos,$start_index,5);
		}else{
			$this->error('照片列表数据错误！');
		}

		$this->assign('photoCount',$photoCount);
		$this->assign('now',$now_index+1);
		$this->assign('pre',$pre_photo);
		$this->assign('next',$next_photo);
		$this->assign('previews',$preview_photos);

		unset($pindex);
		unset($photos);
		unset($album);
		unset($preview_photos);

		$this->setTitle(getUserName($this->uid).'的照片：'.$photo['name']);

		$this->display();
	}

	//幻灯播放
	public function autoplayer(){

		$id		=	intval($_REQUEST['id']);
		$aid	=	intval($_REQUEST['aid']);
		$uid	=	intval($_REQUEST['uid']);
		$type	=	t($_REQUEST['type']);	//照片来源类型，来自某相册，还是其他的

		//判断来源类型
		if(!empty($type) && !in_array($type,array('album','mAll','fAll'))){
			$this->error('错误的链接！');
		}
		$this->assign('type',$type);

		//获取照片信息
		$photo	=	D('Photo')->where(" id='$id' AND albumId='$aid' AND userId='$uid' AND isDel=0 ")->find();
		$this->assign('photo',$photo);

		//验证照片信息是否正确
		if(!$photo){
			$this->error('照片不存在或已被删除！');
		}

		//获取所在相册信息
		$album	=	D('Album')->find($aid);
		$this->assign('album',$album);

		//隐私控制
		if($this->mid!=$photo['userId']){
			$album_privacy	=	get_privacy_code($album['privacy']);
			$relationship	=	check_relationship($uid);

			if($album_privacy=='self' && $relationship!='self'){
				$this->error('这张照片，只有主人自己可见。');
			}else
			if($album_privacy=='friend' && $relationship=='stranger'){
				$this->error('这张照片，只有主人的好友可见。');
			}else
			if($album_privacy=='password'){
				$cookie_password	=	Cookie::get('album_password_'.$aid);
				if($cookie_password	!= md5($album['privacy_data'].'_'.$aid.'_'.$uid)){
					$this->redirect('/Index/need_password/uid/'.$uid.'/aid/'.$aid.'/pid/'.$id);
				}
			}
		}

		$this->display("autoplayer");
	}

	//相册数据输出
	public function photo_xml() {

		$id		=	intval($_REQUEST['id']);
		$aid	=	intval($_REQUEST['aid']);
		$uid	=	intval($_REQUEST['uid']);
		$type	=	t($_REQUEST['type']);	//照片来源类型，来自某相册，还是其他的

		//判断来源类型
		if(!empty($type) && !in_array($type,array('album','mAll','fAll'))){
			echo "0";exit();
		}

		//获取所在相册信息
		$album	=	D('Album')->find($aid);
		$this->assign('album',$album);

		//验证隐私信息
		if($this->mid!=$album['userId']){
			$album_privacy	=	get_privacy_code($album['privacy']);
			$relationship	=	check_relationship($uid);

			if($album_privacy=='self' && $relationship!='self'){
				echo "0";exit();
			}else
			if($album_privacy=='friend' && $relationship=='stranger'){
				echo "0";exit();
			}else
			if($album_privacy=='password'){
				$cookie_password	=	Cookie::get('album_password_'.$aid);
				if($cookie_password	!= md5($album['privacy_data'].'_'.$aid.'_'.$uid)){
					echo "0";exit();
				}
			}
		}

		$order	=	$this->setting['album_default_order'];

		//获取所有照片数据
		$photos	=	D('Album')->getPhotos($uid,$aid,$type,$order,5);
		$this->assign('photos',$photos);
		header('Content-type: application/xml');
		$this->display("flash_autoplayer");
	}

	//显示一个照片专辑
	public function album() {

		$id		=	intval($_REQUEST['id']);
		$uid	=	intval($_REQUEST['uid']);

		//获取相册信息
		$album	=	D('Album')->where(" id='$id' AND userId='$uid' AND isDel=0 ")->find();

		if(!$album){
			$this->error('专辑不存在或已被删除！');
		}

		//隐私控制
		$album_privacy	=	get_privacy_code($album['privacy']);
		$relationship	=	check_relationship($uid);
		if($album_privacy=='self' && $relationship!='self'){
			$this->error('这个相册，只有主人自己可见。');
		}else
		if($album_privacy=='friend' && $relationship=='stranger'){
			$this->error('这个相册，只有主人的好友可见。');
		}else
		if($album_privacy=='password' && $this->mid!=$album['userId']){

			//$this->error('这个相册，需要输入密码才能查看。');
			$cookie_password	=	Cookie::get('album_password_'.$id);

			//如果密码不正确，则需要输入密码
			if($cookie_password != md5($album['privacy_data'].'_'.$id.'_'.$uid)){
				$this->redirect('/Index/need_password/uid/'.$uid.'/aid/'.$id);
			}
		}

		//获取照片数据
		$raws	=	$this->setting['photo_raws'];
		$order	=	$this->setting['album_default_order'];

		$map['albumId']	=	$id;
		$map['userId']	=	$uid;
		$map['isDel']	=	0;

		$photos	=	D('Photo')->order($order)->where($map)->findPage($raws);
		$this->assign('photos',$photos);

		//获取标记数据
		//D('PhotoMarks')->where($map)->findAll();

		$this->setTitle(getUserName($this->uid).'的相册：'.$album['name']);

		$this->assign('album',$album);
		$this->display();
	}

	//输入相册密码
	public function need_password() {

		$aid	=	intval($_REQUEST['aid']);
		$pid	=	intval($_REQUEST['pid']);
		$uid	=	intval($_REQUEST['uid']);

		//获取相册信息
		$album	=	D('Album')->where(" id='$aid' AND userId='$uid' AND isDel=0 ")->find();

		if(!$album){
			$this->error('专辑不存在或已被删除！');
		}

		$this->assign('uid',$uid);
		$this->assign('aid',$aid);
		$this->assign('pid',$pid);
		$this->assign('album',$album);
		$this->display();
	}

	//验证相册密码
	public function check_password() {

		$aid	=	intval($_REQUEST['aid']);
		$pid	=	intval($_REQUEST['pid']);
		$uid	=	intval($_REQUEST['uid']);
		$password	=	t($_REQUEST['password']);

		//获取相册信息
		$album	=	D('Album')->where(" id='$aid' AND userId='$uid' AND isDel=0 ")->find();

		if(!$album){
			$this->error('专辑不存在或已被删除！');
		}

		//验证密码
		if( $password == $album['privacy_data'] ){

			//加密保存密码
			$cookie_password	=	md5( $album['privacy_data'].'_'.$aid.'_'.$uid );

			//密码保存7天
			Cookie::set( 'album_password_'.$aid , $cookie_password , 3600*24*7 );

			if($pid>0){
				//跳转到照片页面
				$url	=	__APP__.'/Index/photo/aid/'.$aid.'/uid/'.$uid.'/id/'.$pid;
			}else{
				//跳转到相册页面
				$url	=	__APP__.'/Index/album/id/'.$aid.'/uid/'.$uid;
			}
			$this->assign('jumpUrl',$url);
			$this->success('密码验证成功，将自动保存7天。马上跳转到相册页面！');

		}else{

			$url	=	__APP__.'/Index/need_password/aid/'.$aid.'/uid/'.$uid.'/pid/'.$pid;
			$this->assign('jumpUrl',$url);
			$this->error('密码验证失败，请重新输入！');

		}
	}

	//某人的全部照片
	public function photos() {

		//获取照片数据
		$rows	=	$this->setting['photo_raws'];
		$order	=	$this->setting['album_default_order'];

		$map['userId']	=	$this->uid;
		$map['isDel']	=	0;

		$photos	=	D('Photo')->order($order)->where($map)->findPage($rows);
		$this->assign('data',$photos);

		$this->display();
	}

	//某人的全部专辑
	public function albums() {

		//每页显示相册数
		$rows	=	$this->setting['album_raws'];

		//获取相册数据
		$map['userId']	=	$this->uid;
		$map['isDel']	=	0;

		$data	=	D('Album')->order("mTime DESC")->where($map)->findPage($rows);
		$this->assign('data',$data);

		$this->display();
	}

	//标记某人的照片
	public function marked() {

		//每页显示照片数
		$rows	=	$this->setting['photo_raws'];
		$order	=	$this->setting['album_default_order'];

		//获取相片数据
		$map['userId']	=	$this->uid;
		$map['isDel']	=	0;
		$data	=	D('Photo')->order($order)->where($map)->findPage($rows);
		$this->assign('data',$data);

		$this->display();
	}

	//好友的最新照片更新状态
	public function friends_photos() {

		//每页显示照片数
		$rows	=	$this->setting['photo_raws'];

		//获取好友的全部照片
		$friends	=	$this->api->friend_get();

		//获取好友最新照片的时间间隔
		$day_limit	=	$this->setting['friends_new_photo_limit'];

		//默认设置7天
		$time_limit	=	$day_limit*24*3600;

		//获取好友更新数据
		$map['userId']	=	array('in',$friends);
		$map['cTime']	=	array('gt',(time()-$time_limit) );
		$map['isDel']	=	0;

		$data	=	D('Photo')->group('userId')->order('updatetime DESC')->field('userId,cTime,max(savepath) as savepath,count(userId) as photocount,max(cTime) as updatetime')->where($map)->limit($rows)->findAll();

		$this->assign('data',$data);

		$this->display();
	}

	//好友的全部专辑
	public function friends_albums() {

		//获取当前登录用户的好友列表
		$friends	=	$this->api->friend_get();

		//每页显示相册数
		$rows	=	$this->setting['album_raws'];

		//获取相册数据
		$map['userId']	=	array('in',$friends);
		$map['isDel']	=	0;
		$data	=	D('Album')->order("mTime DESC")->where($map)->findPage($rows);
		$this->assign('data',$data);

		$this->display();
	}

	//标记好友的照片
	public function friends_marked() {
		$this->display();
	}

	//大家的最新照片
	public function all_photos() {

		//获取照片数据
		$rows	=	$this->setting['photo_raws'];
		$order	=	$this->setting['album_default_order'];

		$map['privacy']	=	1; //所有人公开的照片
		$map['isDel']	=	0;

		$photos	=	D('Photo')->order($order)->where($map)->findPage($rows);
		$this->assign('data',$photos);

		$this->display();
	}

	//大家的最新专辑
	public function all_albums() {

		//每页显示相册数
		$rows	=	$this->setting['album_raws'];

		//获取相册数据
		$map['privacy']	=	1; //所有人公开的相册
		$map['isDel']	=	0;
		$data	=	D('Album')->order("mTime DESC")->where($map)->findPage($rows);
		$this->assign('data',$data);

		$this->display();
	}

	//输出exif信息
	public function show_exif() {
		if($this->setting['open_exif']){
			$img	=	base64_decode($_REQUEST['img']);
			if(function_exists(exif_read_data)){
				$exif = GetImageInfo($img);
				//输出exif信息
				$this->assign('exif',$exif);
			}else{
				//系统不支持
				$error_info	=	'服务器不支持照片exif信息提取功能。';
				$this->assign('error',$error_info);
			}
		}else{
			//系统不支持
			$error_info	=	'管理员关闭了照片exif信息提取功能。';
			$this->assign('error',$error_info);
		}
		$this->display();
	}

	public function commentSuccess(){
		$result = json_decode(stripslashes($_POST['data']));  //json被反解析成了stdClass类型
		$dao = D('Photo');
		//计数更新
			$count = $this->__setCount($result->appid);

		 //发送两条消息
		$data['toUid'] = $result->toUid;
		$need  = $dao->where('id='.$result->appid)->field('userId,name,albumId')->find();
		$data['uids'] =$need['userId'];
		$data['url'] = sprintf('%s/apps/photo/index.php?s=/Index/photo/id/%s/aid/%s/uid/%s/type/mAll',
									'{SITE_URL}',$result->appid,$need['albumId'],$data['uids']
							);
		$data['title_body']['comment'] = $result->comment;
		$data['title_data']['title'] = sprintf("<a href='%s'>%s</a>",$data['url'],$need['name']);
		$data['title_data']['type']  = "相册";
		$this->api->comment_notify('photo',$data,$this->appId);
		echo $count;
	}

    public function deleteSuccess() {
        $id = $_POST['id'];
        echo $this->__setCount($id);
    }

    private function __setCount($id){
        $count = $this->api->comment_getCount('photo',$id);
        $this->blog->setCount($id,$count);
        return $count;
    }

	/* 分享相关操作 */
	public function addShare_check(){
		$result = 1;

		$aimId = $_REQUEST['aimId'];
		$type = $_REQUEST['type'];

		if($type=='album'){
			//$dao = D('Album');
			$typeId = 6;
		}else{
			//$dao = D('Photo');
			$typeId = 7;
		}
//		$res = $dao->where("id='$aimId'")->field('userId,privacy')->find();
//
//		if($res['userId']==$this->mid){
//			$result = -1;
//		}else{
			$test = $this->api->share_isForbid($this->mid,$typeId,$aimId);
			if($test==-1){
				$result = -2;
			}
//		}

        echo $result;
	}

	function addShare(){
		$aimId = $_REQUEST['aimId'];
		$this->assign('aimId',$aimId);

		$type = $_REQUEST['type'];
		$this->assign('type',$type);

		if($type=='album'){
			$dao = D('Album');
			$typeId = 6;
		}else{
			$dao = D('Photo');
			$typeId = 7;
		}
		$result = $dao->where("id='$aimId'")->field('name')->find();

		$this->assign('name',$result['name']);
		$this->assign($result);

		$this->display();
	}

	function doaddShare(){

		$info = h($_REQUEST['info']);
		$aimId = intval($_REQUEST['aimId']);

		$fids = $_REQUEST['fids'];

		if($_REQUEST['type']=='album'){
			$type['typeId'] = 6;
			$type['typeName'] = '相册';
			$type['alias'] = 'album';

			$field = 'userId,name,info';
			$data = D('Album')->where("id='$aimId'")->field($field)->find();

			//$data['name'] = h($_REQUEST['name']);
			$data['username'] = getUserName($data['userId']);
			$data['cover'] = get_album_cover($aimId);

		}else {
			$type['typeId'] = 7;
			$type['typeName'] = '相片';
			$type['alias'] = 'photo';

			$field = 'albumId,userId,name,info,savepath';
			$data = D('Photo')->where("id='$aimId'")->field($field)->find();

			//$data['name'] = h($_REQUEST['name']);
			$data['username'] = getUserName($data['userId']);
			$data['albumName'] = D('Album')->where("id=".$data['albumId'])->getField('name');
			$data['photo'] = get_photo_url($data['savepath']);

			//dump($data);exit;
		}

		$result = $this->api->share_addShare($type,$aimId,$data,$info,0,$fids);

		echo $result;
	}
}
?>