<?php

class WishLwModel extends LW_Model {
	/**
    * doaddwish
    * 站内增加分享API
    *
    * @param $type,$aimId,$toUid,$info,$purview,$content
    * @access public
    * @return  0 失败 1 成功 -1 已经分享 -2 分类出错 -3 内容为空  -4 不能分享自己的东西 -10 描述超过100字
    */
	var $notice_uid;
	
	function addWish($type=1,$content='',$fri_ids=null,$url=null) {		
		$check = $this->_check($content);
        if($check!=1){
        	return $check;
        }
				
		$userLwDao	 =	TS_D("User");
		$mid		 =	$userLwDao->getLoggedInUser();
		$username	 =	$userLwDao->getLoggedInName();	        
		
        $content = $this->_dealcontent($content);
	
		$map['type'] = $type;
		$map['status'] = 0;
		$map['uid'] = $mid;
		$map['name'] = $username;	
		$map['tagId'] = $content['id'];	
		$map['cTime'] = time();
		$map['content'] = $this->_getcontent($content,$type,$mid);
		$map['content'] = serialize($map['content']);
	
        $result = $this->add($map);

		if($result){
			//发送通知和动态信息
             $this->_sendMessage($content,$type,$mid,$fri_ids,$url);
			//更新分享统计
			$this->_updateCount($mid);
			//增加积分
			//$this->_addScoure($mid,$content,$type);
		}
		
		return $result;
	}
	
	function setHelp($toUserId,$fromUid,$giftId){
		$this->notice_uid = array();

		if(is_array($toUserId)){
			foreach ($toUserId as $v){
				$this->_setHelp($v,$fromUid,$giftId);
			}
		}else{
		    $this->_setHelp($toUserId,$fromUid,$giftId);
		}		
		
		return $this->notice_uid;
	}
	
	function _setHelp($uid,$fromUid,$giftId){		
		$map['uid'] = $uid;
		$map['tagId'] = $giftId;
		$map['type'] = 2;

		$wish = $this->where($map)->find();

		if(!$wish) {
			$this->notice_uid[] = $uid;
			return false;
		}

		$help = $wish['help'].','.$fromUid;
		$save['help'] = ltrim($help,',');
		$this->where($map)->save($save);

		return $this->notice_back($uid,$wish);		
	}
	
	function _getcontent($content,$type,$mid){
		if($type=='2'){
			$img = $content['img'];
			$info = '<div class="quote"><p><span class="quoteR">'.$content['info'].'</span></p></div>';

			$url = " <a href='".SITE_URL."/apps/gift/index.php?s=/Index/index/uid/".$mid."/id/".$content['id']."'>
			给".getUserWo($mid)."送礼</a>";
			$content = getUserWo($mid)."在礼品中对着 $img 许愿了  $url<br/> ".$info;
		}
		return $content;
	}
	
	function _updateCount($mid){
		//更新分享统计
		$spaceDao	 =	TS_D("Space");
		$spaceDao->changeCount( 'wish',$this->getwishNum($mid) );
	}
	function getwishNum($uid=null){
		if(empty($uid)){
			$list = $this->field('count(*) as num')->find();
		}elseif (is_array($uid)){
			$where = "uid IN (" . join(",", $uid) . ")";
			$list = $this->where($where)->field('count(*) as num')->find();
		}else{
			$where = "uid = '$uid'";
			$list = $this->where($where)->field('count(*) as num')->find();
		}

		return $list["num"];
	}	
	
	function notice_back($uid,$wish){
		$appid = TS_D('App')->getChoiceId('wish');
		$notifyDao = TS_D('Notify');
		$notifyDao->setAppId($appid);
		
		$sexName = getUserWo($uid);
		$content = unserialize($wish['content']);
		$content = str_replace('给'.$sexName.'送礼','',$content);
		$body['content'] = $content;
		
		$url = '{WR}/apps/gift/index.php?s=/Index/receivebox';

		return $notifyDao->send($uid,"wish_back",'',$body,$url);	
	}
	function _sendMessage($content,$type,$mid,$fri_ids,$url){
		$body_content['content'] = $this->_getcontent($content,$type,$mid);
		
		$appid = TS_D('App')->getChoiceId('wish');
		//TS_D('Feed')->publish("wish_".$type,$title_content,$body_content,$appid);

		$notifyDao = TS_D('Notify');
		$notifyDao->setAppId($appid);	
		
		if(empty($url)){
			$url = '{WR}/apps/wish/index.php?s=/Index/friends/uid/'.$mid;
		}
		if(!empty($fri_ids)){			
			$notifyDao->send($fri_ids,"wish_notice",'',$body_content,$url);
		}
	}
	
	function _check($content){
        if(StrLenW($content)>100){
        	return -10;
        }
		return 1;	
	}
	
	function _dealcontent($content){
		if(is_array($content)){
			foreach ($content as $k=>$v){
				$content[$k] = stripcslashes($v);
				$content[$k] = str_replace(SITE_URL,'{WR}',$v);
			}
		}else{
			$content = stripcslashes($content);
			$content = str_replace(SITE_URL,'{WR}',$content);
		}

		return $content;
	}
	
/*	function _addScoure($mid,$content,$type){
		setScore($mid,'add_wish');//发起分享
		
        $uid = $this->_getUid($content,$type);
		if(!empty($uid)){
			setScore($uid,'wishd');//被分享
		}		
	}*/
/*	
	function _getUid($content,$type){
		if(!empty($content['uid'])&&$type['typeId']!=10){
			$uid = $content['uid'];
		}elseif (!empty($content['userId'])){
			$uid = $content['userId'];
		}
		return $uid;		
	}*/
	
}
?>