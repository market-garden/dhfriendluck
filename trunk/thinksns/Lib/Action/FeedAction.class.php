<?php

class FeedAction extends BaseAction{


	public function del() {

        $is_me = $_POST['is_me'];
        if($is_me){
            $map["uid"]    =  $this->mid;
            $map["id"] =  intval($_POST["id"]);
            echo D("Feed")->where($map)->delete();
        }else{
            $dao = D("FeedDel");

            $data["uid"]    =  $this->mid;
            $data["feedId"] =  intval($_POST["id"]);

            echo $dao->add($data);
        }


	}


    public function post_mini(){
        $this->checkJsToken();
        //缓存表情配置
        $bg = D('AppConfig');
        $bg->setAppname ('mini');
        
        $bq_config = $bg->getConfig();
        //从缓存中读出表情
        $bq_emotion = ts_cache('smile_'.$bq_config['smiletype']);
        // 保存添加的数据
		$Dao = D("Mini");
        $Dao->setCount( $bq_config['stringcount'] );
		$vo = $Dao->create();
		if(false === $vo) {
            echo false;
            return;
        }
        $Dao->content = t( $Dao->content );
        $Dao->uid  = $this->mid;
        $Dao->name  = $this->my_name;
        $Dao->status = 0;
        $Dao->cTime = time();
        if($mini_id = $Dao->add()){

            //ajax返回
            $mini_con = tt($Dao->content);
 
            $bq_path = "<img src='".__PUBLIC__."/images/biaoqing/{$bq_config['smiletype']}/";
            foreach($bq_emotion as $v){
                $mini_con =  str_replace($v["emotion"], $bq_path.$v['filename']."'>", $mini_con);
            }


            //添加一条动态
            $title_data["content"] = $mini_con;
            $body_data["id"] = $mini_id;
            $body_data["uid"] = $this->mid;
            $body_data["con"] = $mini_con;
            $feedId = $this->api->feed_publish("mini",$title_data,$body_data,3);


            //再把feedId放入mini表中
            $data_m["feedId"] = $feedId;
            $Dao->where("id=$mini_id")->save($data_m);
           // echo $Dao->getLastSql();
        if( $bq_config['delete'] ){
            $count = $Dao->where( 'uid='.$this->mid.' AND status <> 1' )->count();
        }else{
            $count = $Dao->where( 'uid='.$this->mid)->count();
        }
        $result = $this->api->space_changeCount( 'mini',$count );
                setScore($this->mid,'add_mini');
            echo $mini_con;
        }else{
            echo 0;
        }

    }

    function post_mini_comment(){

        //发表一条评论
        $daoComm = D("Comment");
        $daoComm->appid = $_POST["appid"];
        $daoComm->comment = $_POST["comment"];
        $daoComm->type = "mini";
        $daoComm->uid  = $this->mid;
        $daoComm->name = $this->my_name;
        $daoComm->cTime = time();

        $daoComm->add();

        //mini表count加1
        $dao = D("Mini");
        $dao->setInc("replay_numbel","id=".$_POST['appid']);
   

        $notifydata = $this->__getNotifyData($_POST);
        $this->api->comment_notify('mini',$notifydata,3);

        //给你回复的那个评论人发一条通知
        $comm_uid = intval($_POST["comm_uid"]);

        $appconfig	=	D('AppConfig');
        $appconfig->setAppname('mini');
        $config	=	$appconfig->getConfig();
        echo 1;
    }

    private function __getNotifyData($data){
        //发送两条消息
        $dao = D("Mini");
        $need  = $dao->where('id='.intval($data['appid']))->field('uid,content')->find();
        $result['uids'] = $need['uid'];


        $result['toUid'] = intval($data['comm_uid'])?intval($data['comm_uid']):0;
       if($result['toUid'] != $this->mid && $this->mid != $need['uid']){
                     $result['toUid'] !=0 && setScore($result['toUid'],'replayed_mini');
                        setScore($result['uids'],'replayed_mini');
                        setScore($this->mid,'replay_mini');
        }

        $result['url'] = sprintf('{SITE_URL}/Index/friends/uid/%s#Fli%s',intval($data['mini_uid']),intval($data['appid']));
        $result['title_body']['comment'] = t($data['comment']);
        $result['title_data']['title'] = sprintf("<a href='%s'>%s</a>",$result['url'],$need['content']);
        $result['title_data']['type']  = "心情";
        return $result;
    }


    function post_mini_count(  ){
            $id = $_REQUEST['id'];
            if( empty( $id ) ){
                echo "-1";
                return false;
            }

            $data['id'] = $id;
            if( $count = D( 'Mini' )->getReplayCount($data) ){
                echo trim($count)."条回复";
                return true;
            }else{
                echo -1;
                return false;
            }
    }


    function get_other_comment(){
        $aid = intval($_POST["aid"]);

        $map["appid"] = $aid;
        $map["type"] = "mini";

        $comms = D("Comment")->where($map)->findAll();


        $num = count($comms);

        $comms2 = array_slice($comms, 1, $num-2);

        for($i=0;$i<$num-2;$i++){
            $comms2[$i]["face"] = getUserFace($comms2[$i]["uid"]);
            $comms2[$i]['cTime'] = friendlyDate( $comms2[$i]['cTime'] );
        }

        echo json_encode($comms2);


    }



}
?>
