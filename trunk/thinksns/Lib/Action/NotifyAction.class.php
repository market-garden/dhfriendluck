<?php

class NotifyAction extends BaseAction{


    public  function _initialize(){
        parent::_initialize();

    }


//---------------------------------通知相关------------------------------

    /*
     * 通知
     *
     */
    function index() {
        //好友请求
        if($_GET["t"] == "fri"){
            $notifys = $this->api->notify_get("friend");
        }

        //系统通知
        if($_GET["t"] == "sys"){
            $notifys = $this->api->notify_get("notification");
        }

        if(!$_GET["t"]){
            $notifys = $this->api->notify_get("all");
        }
        
    
        $this->assign("notifys",$notifys["data"]);
        $this->assign("total_page",$notifys["total_page"]);
        $this->display();
    }


    /*
     * 删除某条通知
     *
     */
    function del_notify() {
        $id = intval($_POST["id"]);

        $map['uid'] = $this->mid;
        $map['id'] = $id;

        echo D("Notify")->where($map)->delete();

    }

//---------------------------------信息相关-------------------------------------

    /*
     * 发送信息显示页面
     *
     */
    function write() {
        $uid = intval($_GET["uid"]);

        if($uid){
            $toUserFace = getUserFace($uid);
            $toUserName = getUserName($uid);

            $this->assign("toUserFace",$toUserFace);
            $this->assign("toUserName",$toUserName); 
        }


        $this->display();
    }


	/*
	 * 发送消息
	 *
	 */
    function doSend(){
        
        $toUserIds = explode(",",$_POST["fri_ids"]);
        //unset($_POST["fri_ids"]);

        $dao = D("Msg");
        
		$r = $dao->create();
		if(false === $r) $this->error($dao->getError());

        $dao->cTime = time();
        $dao->fromUserId = $this->mid;
        
        foreach($toUserIds as $uid){
            $dao->toUserId = $uid;
            $r = $dao->add();
    
            //通知
            $cate = "message";
            $this->api->notify_send($uid,$type,$title_data,$body_data,$url,$cate);
        }

       $this->redirect("Notify/send");


    }

	/*
	 * 发件箱
	 *
	 */
    function send(){

        $dao = D("Msg");
        $map["fromUserId"] = $this->mid;
        $map["is_new"] = 1;
		$map["is_del"] = array("NEQ",$this->mid);

        $msgs = $dao->where($map)->order("cTime desc")->findPage(5);
        $this->assign( 'count',$dao->where( $map )->count() );
        //echo $dao->getLastSql();
        //dump($msgs);
        

        $this->assign("msgs",$msgs["data"]);
        $this->assign("page",$msgs["html"]);


        $this->display();
    }

	/*
	 * 收件箱
	 *
	 */
    function inbox(){

        //标记为已读先
        $map_n['cate'] = array("NOT IN",'wall,notification,friend');
        $map_n["new"]  = 1;
        $data_n["new"] = 0;
        D('Notify')->where($map_n)->save($data_n);

        //查询
        $dao = D("Msg");
        $map["toUserId"] = $this->mid;
        $map["is_new"] = 1;
		$map["is_del"] = array("NEQ",$this->mid);

        $msgs = $dao->where($map)->order("cTime desc")->findPage(5);

        $this->assign("msgs",$msgs["data"]);
        $this->assign("page",$msgs["html"]);
        
//        $total = $dao->where($map)->order("cTime desc")->count();
//        $this->assign("total",$total);

        $this->display();
    }


	/*
	 * 消息详细页
	 *
	 */
    function msg(){

        $id = intval($_GET["id"]);

        $dao = D("Msg");

        //主信息
        $map["id"] = $id;
        $msg = $dao->where($map)->find();
        $this->assign("msg",$msg);
        if($_GET["t"] == "inbox"){
            $sql = "UPDATE __TABLE__ SET is_read = 1 WHERE id = ".$id;
            $dao->query($sql);
        }
		       

        //回复
        $map_r["replyMsgId"] = $id;
        $r_msgs = $dao->where($map_r)->findAll();
        $this->assign("r_msgs",$r_msgs);
        if($_GET["t"] == "inbox"){
            $sql = "UPDATE __TABLE__ SET is_read = 1 WHERE replyMsgId = ".$id." AND fromUserId != ".$this->mid;
            $dao->query($sql);
        }

        $lid = $_GET["lid"]?$_GET["lid"]:"reply";
        $this->assign("lid",$lid);
        $this->display();
    }

	/*
	 * 回复某条消息
	 *
	 */
    function reply(){
    	$content = trim(t($_POST["content"]));
		if(empty($content)){
			$this->error('内容不能为空');
		}
        $replyMsgId = intval($_POST["replyMsgId"]);
        if(0 == $replyMsgId){
        	$this->error('错误的消息Id');
        }
        
        $type = $_POST["type"];
        unset($_POST["type"]);
        
        $dao = D("Msg");

        //先把这个会话与自己有关的msg的is_new设为0
        $sql1 = "UPDATE __TABLE__ SET `is_new`=0 WHERE fromUserId = ".$this->mid." AND id = ".$replyMsgId;
        $dao->query($sql1);
        $sql2 = "UPDATE __TABLE__ SET `is_new`=0 WHERE fromUserId = ".$this->mid." AND replyMsgId = ".$replyMsgId;
        $dao->query($sql2);


        //算出回复给谁
        $main_msg = $dao->field("fromUserId,toUserId")->find($replyMsgId);
        if($main_msg["fromUserId"] == $this->mid){
            $toUserId = $main_msg["toUserId"];
        }else{
            $toUserId = $main_msg["fromUserId"];
        }


        //然后新建一条
        $dao->create();
        $dao->subject = "回复：".$_POST["subject"];
        $dao->fromUserId = $this->mid;
        $dao->toUserId = $toUserId;
        $dao->cTime   = time();
		$dao->is_read = 0;

        $dao->replyMsgId = $_POST["replyMsgId"];
        $dao->content = t($_POST["content"]);


        $dao->add();

        $url = "Notify/msg/t/".$type."/id/".$replyMsgId."#reply";
        $this->redirect($url);

    }


	/*
	 * 删除某条消息
	 *
	 */
	function delMsg() {

		$id = intval($_POST["id"]);

		$dao = D('Msg');

		$map["toUserId"] =	$this->mid; //为了safe
		$map["id"]       =	$id;

		$map = "(toUserId = ".$this->mid." OR fromUserId = ".$this->mid.") AND id = ".$id;

		$msg = $dao->where($map)->find();

		if($msg){
			if($msg["is_del"] == 0){         
				$dao->is_del = $this->mid;	//如果对方没有删除过
				echo $dao->save();
			}else{
				$dao->is_new = 0;			//如果对方删除过
				echo $dao->save();
			}			
		}

	}


	/*
	 * 删除某些消息
	 *
	 */
	function delSomeMsg() {

		if(!$_POST["ids"]) return;

		$ids = explode(",",$_POST["ids"]);;

		$dao = D('Msg');

		foreach($ids as $id){

			$map = "(toUserId = ".$this->mid." OR fromUserId = ".$this->mid.") AND id = ".$id;

			$msg = $dao->where($map)->find();

			if($msg){
				if($msg["is_del"] == 0){         
					$dao->is_del = $this->mid;	//如果对方没有删除过
					echo $dao->save();
				}else{
					$dao->is_new = 0;			//如果对方删除过
					echo $dao->save();
				}			
			}
		}

		echo 1;

	}





	/*
	 * 标记已读 -- 某条消息
	 *
	 */
	function bjReadMsg() {

		$id		 = intval($_POST["id"]);
		$is_read = intval($_POST["is_read"]);

		$dao = D('Msg');

		$map["toUserId"] =	$this->mid; //为了safe
		$map["id"]       =	$id;

		$msg = $dao->where($map)->find();
		if($msg){      
			$dao->is_read = $is_read;	
			$dao->save();
		}

	}


	/*
	 * 标记已读 -- 某些消息
	 *
	 */
	function bjReadSomeMsg() {

		if(!$_POST["ids"]) return;
		$is_read = intval($_POST["is_read"]);

		$ids = explode(",",$_POST["ids"]);;

		$dao = D('Msg');

		foreach($ids as $id){

			$map["toUserId"] =	$this->mid; //为了safe
			$map["id"]       =	$id;

			$msg = $dao->where($map)->find();
			if($msg){      
				$dao->is_read = $is_read;	
				$dao->save();
			}
		}

		echo 1;

	}

}
?>
