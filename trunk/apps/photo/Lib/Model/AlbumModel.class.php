<?php
class AlbumModel extends Model{
	var $tableName	=	'photo_album';

	//为新用户创建默认数据
	public function createNewData($uid=0) {
		//创建默认相册
		if( intval($uid) <= 0 ){
			$uid	=	$this->mid;
		}

		$count	=	$this->where("userId='$uid' AND isDel=0")->count();
		if($count==0){
			$name	=	'我的相册';	//默认的相册名
			$album['cTime']		=	time();
			$album['mTime']		=	time();
			$album['userId']	=	$uid;
			$album['name']		=	$name;
			$album['privacy']	=	1;
			$this->add($album);
		}
	}

	//更新相册照片数量
	function updateAlbumPhotoCount($aid) {
		$count	=	D('Photo')->where("albumId='$aid' AND isDel=0")->count();
		$map['photoCount']	=	$count;
		return $this->where("id='$aid'")->save($map);
	}

	//设置相册封面
	function setAlbumCover($albumId,$cover=0) {
		//插入照片封面
		$cover_info	=	D('Photo')->where("id='$cover'")->find();
		if($cover>0 && $cover_info){
			$map['coverImageId']	=	$cover_info['id'];
			$map['coverImagePath']	=	$cover_info['savepath'];
		}
		$map['mTime']	=	time();
		//更新相册信息
		$result	=	$this->where("id='$albumId'")->save($map);
		if($result){
			return true;
		}else{
			return false;
		}
	}

	//通过相册ID 获取照片ID集
	function getPhotoIds($uid,$albumId,$type) {
		$photos	=	$this->getPhotos($uid,$albumId,$type);
		if($photos){
			foreach($photos as $v){
				$photoIds[]	=	$v['photoId'];
			}
			return $photoIds;
		}else{
			return false;
		}
	}

	//通过相册ID 获取照片集
	function getPhotos($uid,$albumId,$type,$order='id ASC',$shownum=5) {
		//我的全部照片
		if($type=='mAll'){
			$map['userId']	=	$uid;
		//好友的全部照片
		}elseif($type=='fAll'){
			$api			=	new TS_API();
			$friends		=	$api->friend_get();
			$map['userId']	=	array('in',$friends);
		}else{
		//某个专辑的全部照片
			$map['albumId']	=	$albumId;
			$map['userId']	=	$uid;
		}
		$map['isDel']	=	0;
		$result	=	 D('Photo')->order($order)->where($map)->findAll();
		return $result;
	}

	//删除相册
	function deleteAlbum($aids,$uid,$isAdmin=0,$delFile=false) {
		//解析ID成数组
		if(!is_array($aids)){
			$aids	=	explode(',',$aids);
		}

		//非管理员只能删除自己的照片
		if(!$isAdmin){
			$map['userId']	=	$uid;
		}

		//标记为已删除
		$map['id']		=	array('in',$aids);
		$save['isDel']	=	1;
		$result	=	$this->where($map)->save($save);

		if($result){
			//同步删除照片及附件
			$album['albumId']	=	array('in',$aids);
			$photos		=	D('Photo')->field('id')->where($album)->findAll();

			foreach($photos as $v){
				$photoIds[]	=	$v['id'];
			}

			//处理照片及附件
			$this->deletePhoto($photoIds,$uid,$isAdmin,$delFile);

			return true;
		}else{
			return false;
		}
	}

	//恢复相册
	function restoreAlbum($aids,$uid,$isAdmin=0,$delFile=false) {
		//解析ID成数组
		if(!is_array($aids)){
			$aids	=	explode(',',$aids);
		}

		//非管理员只能恢复自己的照片
		if(!$isAdmin){
			$map['userId']	=	$uid;
		}

		//标记为未删除
		$map['id']		=	array('in',$aids);
		$save['isDel']	=	0;
		$result	=	$this->where($map)->save($save);

		if($result){
			//同步删除照片及附件
			$album['albumId']	=	array('in',$aids);
			$photos		=	D('Photo')->field('id')->where($album)->findAll();

			foreach($photos as $v){
				$photoIds[]	=	$v['id'];
			}

			//处理照片及附件
			$this->restorePhoto($photoIds,$uid,$isAdmin,$delFile);

			return true;
		}else{
			return false;
		}
	}

	//删除照片
	function deletePhoto($pids,$uid,$isAdmin=0,$delFile=false) {
		//解析ID成数组
		if(!is_array($pids)){
			$pids	=	explode(',',$pids);
		}

		//非管理员只能删除自己的照片
		if(!$isAdmin){
			$map['userId']	=	$uid;
		}

		//标记为已删除
		$map['id']	=	array('in',$pids);
		$save['isDel']	=	1;
		$result	=	D('Photo')->where($map)->save($save);

		if($result){

			$photos		=	D('Photo')->where($map)->findAll();

			foreach($photos as $v){
				$attachIds[]	=	$v['attachId'];
				//重置相册照片数
				$this->updateAlbumPhotoCount($v['albumId']);
			}

			//处理附件
			$this->deleteAttach($attachIds);
			return true;
		}else{
			return false;
		}
	}

	//恢复照片
	function restorePhoto($pids,$uid,$isAdmin=0,$delFile=false) {
		//解析ID成数组
		if(!is_array($pids)){
			$pids	=	explode(',',$pids);
		}

		//非管理员只能恢复自己的照片
		if(!$isAdmin){
			$map['userId']	=	$uid;
		}

		//标记为未删除
		$map['id']		=	array('in',$pids);
		$save['isDel']	=	0;
		$result	=	D('Photo')->where($map)->save($save);

		if($result){

			$photos		=	D('Photo')->where($map)->findAll();

			foreach($photos as $v){
				$attachIds[]	=	$v['attachId'];
				//重置相册照片数
				$this->updateAlbumPhotoCount($v['albumId']);
			}

			//处理附件
			$this->restoreAttach($attachIds);
			return true;
		}else{
			return false;
		}
	}

	//删除附件记录
	function deleteAttach($attachIds,$delFile=false) {
		//解析ID成数组
		if(!is_array($attachIds)){
			$aids	=	explode(',',$attachIds);
		}

		$map['id']	=	array('in',$aids);
		//在应用中只能标记删除附件，需要在后台进行清理
		if($delFile){
			return false;
		}else{
			$save['isDel']	=	1;
			$result	=	D('Attach')->where($map)->save($save);
			if($result){
				return true;
			}else{
				return false;
			}
		}
	}

	//恢复附件记录
	function restoreAttach($attachIds) {
		//解析ID成数组
		if(!is_array($attachIds)){
			$aids	=	explode(',',$attachIds);
		}

		$map['id']	=	array('in',$aids);
		//在应用中只能标记恢复附件，需要在后台进行清理
		if($delFile){
			return false;
		}else{
			$save['isDel']	=	0;
			$result	=	D('Attach')->where($map)->save($save);
			if($result){
				return true;
			}else{
				return false;
			}
		}
	}
}
?>