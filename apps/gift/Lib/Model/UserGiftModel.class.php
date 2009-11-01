<?php
    /**
     * UserGiftModel
     * 用户送礼数据模型
     *
     * @uses 
     * @package 
     * @version 
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
class UserGiftModel extends Model{
		private $gift;        //礼品表模型
		private $category;    //礼品类型表模型
		private $api;         //网站API
		
		public function setApi($api){
			$this->api = $api;   //赋值网站API
		}
		public function setGift($gift){
			$this->gift = $gift;  //赋值礼品表模型
		}
		public function setCategory($category){
			$this->category = $category;  //赋值礼品类型表模型
		}	
			
		/**
		 * receiveGift
		 * 获得某个人收取的礼物
		 * @param $uid
		 * @return Gift;
		 */
		public function receiveList($uid){
			$map['toUserId'] = $uid;
			return $this->where($map)->order('id desc')->findPage(15);
		}
		
		/**
		 * sendGift
		 * 获得某个人发送的礼物列表
		 * @param $uid
		 * @return unknown_type
		 */
		public function sendList($uid){
			$map['fromUserId'] = $uid;
			return $this->where($map)->order('id desc')->findPage(15);
		}
		
		/**
		 * sendGift
		 * 发送礼物
		 * 
		 * @param array $toUid  接收礼品人的ID（可以多个，以，分隔）
		 * @param $fromUid  送礼者ID
		 * @param $sendInfo  附加信息和发送方式
		 * @param $giftInfo  礼品信息
		 */
		public function sendGift($toUid,$fromUid,$fromName,array $sendInfo,array $giftInfo){
			//判断参数是否合法.不合法返回false
			if(!is_numeric($fromUid)){				
				return '非法操作！';
			}

			$toUser = explode(',',$toUid);
			$userNum = count($toUser);
	
			//判断是否是自己给自己送礼物
			if(in_array($fromUid,$toUser)){
				return '不能给自己送礼物！';
			}
			//判断是否有足够的礼物数
			if($this->gift->assertNumAreEmpty($giftInfo['id'],$userNum)){
				return '礼物库存不足，发送礼品失败！';
			}	
					
			//扣除相应积分
			$giftPrice = intval($giftInfo['price']);
			$prices = $userNum*$giftPrice;
			$type = getC('creditType');
			$credit[$type] = '-'.$prices;
			if(!$this->__setScore($fromUid,$credit)){
				return '您的'.getC('creditName').'不足，发送礼品失败！';
			}

			$map['giftPrice']    = $giftPrice;
			$map['giftImg']      = t($giftInfo['img']);
			$map['sendInfo']     = t($sendInfo['sendInfo']);
			$map['sendWay']      = intval($sendInfo['sendWay']);
			$map['fromUserId']   = intval($fromUid);
			$map['fromUserName'] = t($fromName);
			$map['cTime']        = time();

			$res = $this->__insertData($toUser,$map);
			
			//如果入库过程成功.则做相应的处理
			if($res){
				//礼物数减1
				$this->gift->setDec('num','id='.$giftInfo['id'],$userNum);
                //获取礼品应用在系统里的注册ID值，系统的动态和通知都需要这个ID进行相关应用信息的获取。
				$appId = $this->api->app_getChoiceId('gift');
				
				if(1 == $sendInfo['sendWay']){
					//发送动态
					$this->__doFeed($sendInfo,$giftInfo,$toUser,$appId);
				}
				
				//给接收人发送通知
				$this->__doNotify($toUser,$sendInfo,$giftInfo,$fromUid,$appId);			    
			    
				//更新个人空间的礼品统计
				$this->_gift_count($toUser);
				
				return 1;
			}else{
				return '发送礼品失败！';
			}	
			
		}
		
		/**
		 * __insertData
		 * 把数据插入数据库
		 * @param $toUser 发送对象ID $map 数据组
		 * @return $add 插入结果集;
		 */		
		private function __insertData($toUser,$map){
			foreach ($toUser as $_touid){
				//组成数据集
				$map['toUserId']     = intval($_touid);
				//将信息入库
				$res = $this->add($map);
			}
			return $res;
		}
		
		/**
		 * __doFeed
		 * 发送动态
		 * @param $sendInfo 附加信息 $giftInfo 礼品信息 $toUser 发送对象ID
		 * @return $feedId 插入结果;
		 */			
		private function __doFeed($sendInfo,$giftInfo,$toUser,$appId){
				$title['user'] = $this->__getUserName($toUser);
				$body['content'] = t($sendInfo['sendInfo']);
				$body['img'] = $this->__realityImage($giftInfo);
				$feedId = $this->api->feed_publish('gift',$title,$body,$appId);
				return $feedId;
		}
		
		/**
		 * __doNotify
		 * 发送系统通知
		 * @param $sendInfo 附加信息 $giftInfo 礼品信息 $toUser 发送对象ID
		 * @return $feedId 插入结果;
		 */			
		private function __doNotify($toUser,$sendInfo,$giftInfo,$fromUid,$appId){
               //根据赠送方式组装数据
				foreach ($toUser as $uid){
					switch ($sendInfo['sendWay']){
						case 1:   //公开
							$user = $this->api->user_getInfo($fromUid,'name');
							$title['user'] = $user['name'];
							$body['sendback']     = '<br/><a href=\"{WR}/apps/gift/index.php?s=/Index/index/uid/'.$fromUid.'\">给'.$user['name'].'回赠礼物</a>';
							break;
						case 2:   //私下
							$user = $this->api->user_getInfo($fromUid,'name');
							$title['user'] = $user['name'];
							$body['sendback']    = '<br/><a href=\"{WR}/apps/gift/index.php?s=/Index/index/uid/'.$fromUid.'\">给'.$user['name'].'回赠礼物</a>';
							break;							
						case 3:   //匿名
							$title['user'] = '神秘人物';
							$this->api->notify_setAnonymous();
							$body['sendback'] = '';
							break;
						default:
							continue;
						}
					//礼品图片
					$body['img']         = $this->__realityImage($giftInfo);
					//附加消息，用文本过滤t函数过滤危险代码
					$body['content']     = t($sendInfo['sendInfo']);
					
					//组装通知里的‘去看看’的网址
		            $url                 = '{WR}/apps/gift/index.php?s=/Index/receivebox';
		            //通过API增加通知到数据库
		            $this->api->notify_setAppId($appid);
		            $notify = $this->api->notify_send( $uid,"gift_send",$title,$body,$url);
				}

		}
		
		/**
		 * __realityImage
		 * 获取礼品图片真实地址
		 * @param  $giftInfo 礼品信息
		 * @return  图片标签;
		 */			
		function __realityImage($giftInfo){
			return sprintf('<img src="{WR}/apps/gift/Tpl/default/Public/gift/%s" alt="%s">',$giftInfo['img'],$giftInfo['name']);
		}

		/**
		 * __getUserName
		 * 获取接收礼品的用户名单
		 * @param  $uid 用户ID（集）,允许多个用户ID，详细请参考user_getInfo的API说明
		 * @return  用户姓名（集）;
		 */			
		private function __getUserName($uid){
			$name = $this->api->user_getInfo($uid,'id,name');
			foreach ($name as &$value){
				$value = sprintf('<a href = "%s/space/%s">%s</a>','{__TS__}',$value['id'],$value['name']);
			}
			$result = implode(',',$name);
			return $result;
		}

		/**
		 * __setScore
		 * 扣除（或增加）用户相应积分
		 * @param  $uid 用户ID，$credit 积分数，为正时表示增加积分，为负时表示扣除积分
		 * @return  void;
		 */			
		private function __setScore($uid,$credit){
			$test['credit'] = $credit;
			$test['action'] = 'send_gift';
			$test['actioncn'] = '发送礼物';
			
			$res = setUserScore($uid, $test);

			return $res;
		}	
		
		/**
		 * _gift_count
		 * 扣除（或增加）用户相应积分
		 * @param  $uids array 礼品接收人ID
		 * @return  void;
		 */			
		private function _gift_count($uids){
			foreach ($uids as $uid){
				$map['toUserId'] = $uid;
				$count = $this->where($map)->count();
				$this->api->space_changeCount( 'gift',$count,$uid);	
			}            
		}
}