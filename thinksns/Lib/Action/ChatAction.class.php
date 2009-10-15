<?php
class ChatAction extends BaseAction {
	
    /*
     * 记录在线状态
     */
    public function refreshOnline(){

		$dao = D("UserOnline");
		$map["uid"] = $this->mid;
		$num = $dao->where($map)->count();
		if($num>0){
			$data["activeTime"] = time();
			$dao->where($map)->save($data);
		}else{
			$data["uid"] = $this->mid;
			$data["uname"] = $this->my_name;
			$data["activeTime"] = time();
			$dao->add($data);
		}
		
    }	


	/*
	 *获取好友列表
	 */
	public function getFriends(){

		//我的所有好友
		$friends = $this->api->friend_get($this->mid);
		//$map["uid"] = array("IN",$friends);
		//$map["activeTime"] = array("GT",time()-15*60);
		$friends_arr = implode(",",$friends);
		$map = "uid IN ($friends_arr) AND activeTime > ".(time()-15*60);

		//如果有离线的
//		$open_df_id = intval($_POST["open_df_id"]);
//		
//		if($open_df_id){
//			$map .= " OR uid = $open_df_id";
//		}

		$daoOnline = D("UserOnline");
		$onlineFris = $daoOnline->where($map)->findAll();
		//echo $daoOnline->getLastSql();

		foreach($onlineFris as $key=>$v){
			$onlineFris[$key]["mini"] = getShortMini(getUserMini($v["uid"]),8);
			$onlineFris[$key]["head"] = getUserFace($v["uid"]);
		}

		echo json_encode($onlineFris);
	}



	/*
	 *获取好友列表
	 *顺便更新在线状态
	 */
	public function getFriends_online(){

		//更新在线状态
		$this->refreshOnline();

		//我的所有好友
		$friends = $this->api->friend_get($this->mid);
		$friends_arr = implode(",",$friends);
		$map = "uid IN ($friends_arr) AND activeTime > ".(time()-15*60);


		$daoOnline = D("UserOnline");
		$onlineFris = $daoOnline->where($map)->findAll();
		//echo $daoOnline->getLastSql();

		foreach($onlineFris as $key=>$v){
			$onlineFris[$key]["mini"] = getShortMini(getUserMini($v["uid"]),8);
			$onlineFris[$key]["head"] = getUserFace($v["uid"]);
		}

		echo json_encode($onlineFris);
	}

	/*
	 *获取好友列表
	 */
	public function getFriends2(){

		//是否需要检查聊天num
		$is_chat_num = intval($_REQUEST["chat_num"]);
		if($is_chat_num>0){
			$sql_f = "select  fromUserId as uid from __TABLE__ where  toUserId=$this->mid and flagNew=1";
			$chats = D("Chat")->query($sql_f);
			foreach($chats as $key=>$v){
				$chat_ids[] = $v["uid"];
			}
			$chat_nums = array_count_values($chat_ids);
		}


		//在线好友
		$friends = $this->api->friend_get($this->mid);
		$map["uid"] = array("IN",$friends);
		$map["activeTime"] = array("GT",time()-15*60);

		$daoOnline = D("UserOnline");
		$onlineFris = $daoOnline->where($map)->findAll();
		//echo $daoOnline->getLastSql();




//不输出离线用户的num
//		if($is_chat_num>0){
//			$onlineFris = $onlineFris?$onlineFris:array();
//			$onlineFris = array_merge($onlineFris,$chats); 
//		}


		foreach($onlineFris as $key=>$v){
			$onlineFris[$key]["mini"] = getShortMini(getUserMini($v["uid"]),8);
			$onlineFris[$key]["head"] = getUserFace($v["uid"]);
			if($is_chat_num>0){
				$onlineFris[$key]["chat_num"] = $chat_nums[$v["uid"]];
				$onlineFris[$key]["uname"] = $onlineFris[$key]["uname"]?$onlineFris[$key]["uname"]:getUserName($v["uid"]);
			}
		}
		//dump($onlineFris);
		echo json_encode($onlineFris);
	}



     /*
	 * 发送信息
	 */
	public function sendMsg(){

        $data["fromUserId"] = $this->mid;
        $data["toUserId"]  = $_POST['df_id'];
        $data["msg"]        = $_POST['msg'];
        $data["disTime"]   = $_POST['msg_time'];
		$data["flagNew"]   = 1;

		echo D("Chat")->add($data);

    }

     /*
	 * 接收信息
	 */
	public function receiveMsg(){

        $userId = $this->mid;
		$msgs = array();
	    $j=0;
		$dao = D("Chat");

	    for($i=0;$i<count($_POST["id"]);$i++){
            $list = '';
	    	$df_id = $_POST["id"][$i];
			$sql_f = "select * from __TABLE__ where fromUserId=$df_id and toUserId=$userId and flagNew=1";
			$list = $dao->query($sql_f);
			

			//如果有消息
			if($list){
				//echo $list['msg'];

				$sql_u = "UPDATE __TABLE__ SET flagNew = 0 WHERE fromUserId=$df_id and toUserId=$userId and flagNew=1";
				$dao->query($sql_u);

				$re_msg = '';
				foreach ($list as $v){
					$re_msg .= stripslashes($v['msg']);
                    $disTime = $v['disTime'];
				}

				$msgs[$j]['id'] = $df_id;
				$msgs[$j]['msg'] = $re_msg;
                $msgs[$j]['dis_time'] = $disTime;
				$j++;
			}
			
		}

		echo json_encode($msgs);

	}

     /*
	 * 接收聊天记录
	 */
	public function receiveRecord(){

        $userId = $this->mid;
        $df_id = $_POST["df_id"];
		$dao = D("Chat");



        $sql = "select * from __TABLE__ where (fromUserId=$df_id and toUserId=$userId) or (fromUserId=$userId and toUserId=$df_id)";
        $list = $dao->query($sql);
		foreach($list as $key=>$v){
			$list[$key]["msg"] = stripslashes($v['msg']);
 		}


		echo json_encode($list);

	}

	/*
	 * 发送聊天记录
	 *
	 */
	function sendRecord() {

		$record = $_POST["record"];

		$subject = "聊天记录--".friendlyDate(time(),"full");

		echo sendMsg($this->mid,$this->mid,$subject,$record);
	}

    /*
	 * 删除聊天记录
	 */
	public function delRecord(){

        $userId = $this->mid;
        $df_id = $_POST["df_id"];


        $sql = "DELETE FROM __TABLE__ where (fromUserId=$df_id and toUserId=$userId) or (fromUserId=$userId and toUserId=$df_id)";
        echo D("Chat")->query($sql);

	}


	/*
	 * 轮询是否有新聊天和信息
	 *
	 */
	function checkChatMsg() {

		//在线好友
		$friends = $this->api->friend_get($this->mid);
		$map["uid"] = array("IN",$friends);
		$map["activeTime"] = array("GT",time()-15*60);
		$daoOnline = D("UserOnline");
		$onlineFris = $daoOnline->where($map)->findAll();
		foreach($onlineFris as $key=>$v){
			$onlineFris_arr[] = $v["uid"];
		}

		$onlineFris_str = implode(",",$onlineFris_arr);

		$sql_f = "select count(id) as num from __TABLE__ where  toUserId=$this->mid AND flagNew=1 AND fromUserId  IN ($onlineFris_str)";
		$list = D("Chat")->query($sql_f);

		echo $list[0]["num"];

	}








	
}
?>
