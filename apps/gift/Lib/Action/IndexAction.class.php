<?php
    /**
     * GiftAction
     * 礼物控制层
     *
     * @uses 
     * @package 
     * @version 
     * @copyright 2009-2011 SamPeng 水上铁
     * @author SamPeng <sampeng87@gmail.com> 水上铁<wxm201411@163.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
class IndexAction extends Action{
	private $gift;             //礼品表模型
	private $gift_category;    //礼品类型表模型
	private $user_gift;        //用户送礼记录表模型
	
	/**
	 * 初始化函数
	 *
	 */	
	function _initialize(){
		//参数转义
        new_addslashes($_POST);
        new_addslashes($_GET);
		
        //整个应用的赋值
        $this->gift = D('Gift');
		$this->gift_category = D('GiftCategory');
		$this->user_gift = D('UserGift');
		
		$this->user_gift->setApi($this->api);
		$this->user_gift->setGift($this->gift);
		$this->user_gift->setCategory($this->gift_category);
		
		$this->gift_category->setGift($this->gift);
        $mid = $this->mid;
        
 		$config = D('AppConfig')->getConfig();
		$this->assign('config',$config);            
	}
	
	/**
	 * 礼物中心
	 *
	 */
	function index() {
		//获取分组好的礼物列表
		$giftList = $this->gift_category->GiftToCategory();
		
		//获取当前用户的积分		
		$money = getCredit($this->mid,$this->api);
		$moneyType = getC('creditName');
		$this->assign('money',$money[$moneyType]);
		
		//把用户姓名和头像赋值给模板
		$this->__assignNameAndFace(intval($_GET['uid']));

		//赋值给模板
		$this->assign('categorys',$giftList);		
		
		$this->display();
	}

	/**
	 * 收到的礼物
	 *
	 */
	function receivebox(){
		//获取收到的礼物列表
		$gift = $this->user_gift->receiveList($this->mid);

		$this->assign('gifts',$gift);
		$this->display();
	}
	/**
	 * 某人的礼物
	 *
	 */	
	function personal(){
		//获取用户ID
		$uid = intval($_GET['uid']);
		if(empty($uid)){
			$this->error('非法操作！');
		}
		$this->assign('uid',$uid);
		//获取收到的礼物列表	
		if(isset($_GET['isSend']))	{
			$gift = $this->user_gift->sendList($uid);
			$this->assign('on2','on');
		}else{
			$gift = $this->user_gift->receiveList($uid);
			$this->assign('on1','on');
		}		

		$this->assign('gifts',$gift);
		$this->display();		
	}
	/**
	 * 送出的礼物
	 *
	 */
	function sendBox(){
		//获取送出的礼物列表
		$gift = $this->user_gift->sendList($this->mid);

		$this->assign('gifts',$gift);
		$this->display();
	}
	/**
	 * “查看所有”（收到的）礼物
	 *
	 */
	function all(){
		$uid = $_REQUEST['uid'];
		if(intval($uid) == 0){
			$this->error('错误的请求');
		}
		$gift = $this->user_gift->receiveList($uid);
		$this->assign('gifts',$gift);
		$this->display();
	}


	/**
	 * 送出礼物
	 *
	 */
	function send(){
		//获取当前用户的ID和姓名
		$fromUid = $this->mid;
		$fromUserName = $this->my_name;
		//获取要发送的好友ID，如有不明可参考'好友选择widget'的说明
		$toUserId = $_POST['fri_ids'];
		if(empty($toUserId)){
			$this->error('你还没有选择好友');
			exit;
		}
		//获取附加信息
		$sendInfo['sendInfo'] = t($_POST['sendInfo']);
		//获取发送方式
		$sendInfo['sendWay']  = t($_POST['sendWay']);
		
		//获取礼品ID并用t函数过滤
		$giftId =  t($_POST['giftId']);
		//查询数据库获取礼品的全部信息
		$giftInfo = $this->gift->where('id='.$giftId)->find();
		//发送礼品
		$result = $this->user_gift->sendGift($toUserId,$fromUid,$fromUserName,$sendInfo,$giftInfo);     
		
		if($result==1){
		    //如果发送成功就跳到‘送出的礼品’页面
			$this->redirect('sendbox');
		}else{
			//如果发送失败就跳转到错误提示页并显示失败原因
			$this->error($result);
		}
		
	}
	
	/**
	 * 把用户姓名和头像赋值给模板
	 *
	 */	
	private function __assignNameAndFace($uid){
		if($uid && $uid != $this->mid){
			$toUserName = getUserName($uid);
			$toUserFace = getUserFace($uid);
			$this->assign('toUserName',$toUserName);
			$this->assign('toUserFace',$toUserFace);
		}
	}	

	function add_wish(){
		$id = $_GET['id'];
		$gift = D('Gift')->where('id='.$id)->find();
		
		$img = $this->getImg($gift);
		$me = "<a href=".TS."/space/$this->mid>".getUserName($this->mid)."</a>";
		$this->assign('me',$me);
		$this->assign('img',$img);
		$this->assign('id',$id);
		$this->display();
	}
	
	function doadd_wish(){
		$id = intval($_POST['id']);
        $gift = D('Gift')->where('id='.$id)->find();
		if($gift){				
			$gift['img'] = $this->getImg($gift);
			$data = $gift;
			
			$data['info'] = h($_POST['info']);
			$fids = $_POST["fids"];

			$result = $this->api->wish_addWish(2,$data,$fids);
            echo $result;
		}else {
			echo -5;
		}
	}
	
	function getImg($gift){
		$img = "<img src='".SITE_URL."/apps/gift/Tpl/default/Public/gift/".$gift['img']."' alt='".$gift['name']."'>";
		return $img;
	}
}
