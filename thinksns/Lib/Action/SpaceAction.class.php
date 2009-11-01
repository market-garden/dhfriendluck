<?php

class SpaceAction extends BaseAction {

    /*
     * 空间首页
     * SamPeng 2009/9/6/ 重构
     */
    public function index() {
        $user = $this->api->user_getInfo($this->uid,'name,active,sex');
        //用户检查
        $this->__checkUser ( $user );


        //黑名单
        $black = $this->api->privacy_black($this->mid,$this->uid);
        if($black) $this->redirect("black");

        //空间主人的好友
        $fuids = $this->__showUserFriends($this->uid);

        //访客记录
        if( $this->__footPrivacy($this->uid, $this->mid,$fuids)  ) {
            D("Visitor")->foot($this->uid,$this->mid,$this->my_name);
        }

        //用户信息
        $userInfo = $this->__getUserInfo($this->mid,$this->uid);
        $this->assign("userInfo",$userInfo);

        //应用的计数
        $apps_num = $this->api->space_getCount($this->uid);
        $this->assign("apps_num",$apps_num);

        //自动判断他，她还是我
        $show_sex = $this->__getUserSex($this->mid,$this->uid,$user);
        $this->assign( 'show_sex',$show_sex );

        //这个空间的主人是否对你设置隐藏了
        $privacyInfo = $this->__checkPrivacy($this->mid,$this->uid);
        $credit = getCredit($this->uid, $this->api);

        $rank = getUserRank($this->uid);
        $this->assign('rank',$rank);
        $this->assign('credit',$credit);
//判断和主人是否是好友
        if($this->mid != $this->uid) {
             setScore($this->uid, 'user_visited');
             setScore($this->mid, 'visit_space');
            $this->__spaceNotFrends($fuids);
        }

        //将留言板新条数设置为空
        if( $this->uid == $this->mid ) {
            $this->__spaceAreFrends();
        }
//        if(!($privacyInfo['the_mini']['content'] == '什么都没做' || empty($privacyInfo))){
//            $title = $user['name'].'的个人空间'.':'.$privacyInfo['the_mini']['content'];
//        }else{
//            $title = $user['name'].'的个人空间';
//        }
        $title = $user['name'].'的个人空间';
        $this->setTitle($title);
        $this->display();
    }

    private function __spaceAreFrends() {
    //将留言版新条数设置为空
        $condition['uid'] = $this->uid;
        $condition['cate'] = 'wall';
        $map['new'] = 0;
        D( 'Notify' )->where( $condition )->save( $map );


    }

    private function __spaceNotFrends($fuids) {
    //判断是否是好友
        $is_friend = in_array($this->mid,$fuids);
        $this->assign("is_friend",$is_friend);
    }

    private function __checkUser($user) {
        if(!$user) {
            $this->error('没有这个人哦！');
        }else {
            if($user['active'] != 1) {
                $this->error('该用户每激活或者被屏蔽');
            }
        }
    }
    private function __showUserFriends($uid) {
        $fuids = $this->api->friend_get($uid);
        $temp_uids = array_slice($fuids, 0, 6);
        $map_fri["id"] = array("IN",$temp_uids);
        $u_fris = D("User")->where($map_fri)->field("id,name")->findAll();
        $this->assign("u_fris",$u_fris);
        return $fuids;
    }

    private function __footPrivacy($uid,$mid,$fuids) {
    //访客记录
        $foot_privacy = $this->api->privacy_get($uid,"foot");
        $are_friends = in_array($mid,$fuids);
        return !($foot_privacy == 0   &&  !$are_friends) && ($uid != $mid);
    }

    private function __getUserInfo($mid,$uid) {
    //SamPeng修改
        $userSearch = D( 'UserSearch' );
        $info     = $userSearch->getHomeInfo( $mid,$uid );
        $userInfo = $info?$info:array();
        if( !empty( $userInfo ) ) {
            isset( $userInfo['教育信息'] ) && $userInfo['教育信息'] = implode( ' <br> ',$userInfo['教育信息'] );
            isset( $userInfo['工作信息'] ) && $userInfo['工作信息'] = implode( ' <br> ',$userInfo['工作信息'] );
        }
        return $userInfo;
    }

    private function __getUserSex($mid,$uid,$user) {
        $show_sex = $user['sex'] ? "他":"她";
        $mid == $uid && $show_sex = "我";
        return $show_sex;
    }

    private function  __getOneMini($uid) {
        $bq_config = D('MiniConfig')->getConfig('mini');
        $bq_emotion = D('Smile')->getSmile($this->opts['ico_type']);
        return D("Mini")->getOneMini($uid,$bq_emotion,$this->opts['ico_type']);
    }


    private function __checkPrivacy($mid,$uid) {
        $is_hide = $this->api->privacy_hide("space",$mid,$uid);

        if($is_hide) {
            $space_privacy = false;
        }else {
        //空间浏览权限和留言权限检测
            $space_privacy = $this->api->privacy_see($mid,$uid,"space");
            $wall_privacy = $this->api->privacy_see($mid,$uid,"wall");
            $this->assign("wall_privacy",$wall_privacy);
        }

        $this->assign("space_privacy",$space_privacy);
        $this->assign("is_hide",$is_hide);
        if($space_privacy) {
            return $this->__spaceNotPrivaty($uid,$mid);
        }
    }
    private function __spaceNotPrivaty($uid,$mid) {
        //空间主人的访客
        $result['visitors']     =   D("Visitor")->get(6,$uid);
        $result['visitor_num']  =   D("Visitor")->getNum($uid);
        //主人的动态
        $result['fri_feeds']  = $this->_get_fri_feeds($uid,"all",1,10);

        $result['the_mini'] = $this->__getOneMini($uid);
        //主人的留言
        $result['my_walls'] = D("Wall")->getWalls($uid,$mid);

        $this->assign($result);
        return $result;
    }

    public function  guestbook(){
            $wall = D('Wall')->getWalls($this->uid,$this->mid);
            $this->assign('my_walls',$wall);
            $this->assign('uid',$this->uid);
            $this->display();
    }

    /*
     * 详细信息
     *
     */
    function detail() {
        $userSearch = D( 'UserSearch' );
        $data = $userSearch->getGroupInfo( $this->mid,$this->uid );
        $this->assign($this->api->user_getInfo($this->uid,'name'));
        $this->assign($data);
        $this->display();
        
    }



    /*
     * 根据类型,获取好友动态
	 *
     */
    public function _get_fri_feeds($who,$type,$p,$limit=10) {
    //好友动态
        $fri_feeds  = $this->api->feed_get($who,$type,$p,$limit);

        //动态的心情评论
        $daoComm = D("Comment");

        foreach($fri_feeds as $k=>$v) {

            preg_match_all('/.*\<input type=\"hidden\" value=\"(.*)\" class=\"mini_id\">.*/i',$v['body'],$mini_id_input);

            $mini_id = $mini_id_input[1][0];

            $feed_comms = null;

            $map["type"] = "mini";
            $map["appid"] = $mini_id;

            $feed_comms_num = $daoComm->where($map)->count();
            if($feed_comms_num) {
                $feed_comms[] = $daoComm->where($map)->find();
                if($feed_comms_num > 1)  $feed_comms[] = $daoComm->where($map)->order("id desc")->find();

                $fri_feeds[$k]["comment"] = $feed_comms;
                $fri_feeds[$k]["comment_num"] = $feed_comms_num;
            }
        }

        return $fri_feeds;
    }

    /*
     * 留言
     *
     */
    public function doWall() {

        $dao = D("Wall");
		$strContent = t($_POST['content']);
        $r = $dao->create();
        if(false === $r) {echo 0;return;}

        $dao->fromUserId = $this->mid;
        $dao->fromUserName = $this->my_name;
        $dao->content      = $strContent;
        $dao->cTime = time();

        $rr = $dao->add();

        if($rr) {
        //通知
            $uid  = intval($_POST["uid"]);
            if($uid != $this->mid) {
                $cate = "wall";
                $title_data = null;
                $body_data['data'] = $strContent;
                exit;
                $url               = '__TS__/space/'.$uid.'#wall';
                $this->api->notify_send($uid,$type,$title_data,$body_data,$url,$cate);
                setScore($this->mid, 'wall');
                setScore($uid,'walled' );

            }
            echo $rr;
        }else {
            echo 0;
        }

    }

    /*
     * 删除留言
     *
     */
    function delWall() {

        $id = intval($_POST["id"]);

        $dao = D("Wall");
        echo $dao->del($id,$this->mid);
    }

    /*
     * 分享用户
     *
     */
    function addShare_check() {

        $result = 1;

        $aimId = $_REQUEST['aimId'];

        //    	if($aimId==$this->mid){
        //    		$result = -1;
        //    	}else{
        $test = $this->api->share_isForbid($this->mid,10,$aimId);
        if($test==-1) {
            $result = -2;
        }
        //    	}

        echo $result;
    }
    function addShare() {
        $aimId = $_REQUEST['aimId'];

        if($aimId==$this->mid) {
            $this->error('请自重,自己不能分享自己.');
        }

        $test = $this->api->share_isForbid($this->mid,10,$aimId);
        if($test==-1) {
            $this->error('你已经分享了该用户,请不要重复分享!');
        }

        $this->assign('aimId',$aimId);

        $this->display();
    }
    function doaddShare() {
        $type['typeId'] = 10;
        $type['typeName'] = '用户';
        $type['alias'] = 'user';

        $info = h($_REQUEST['info']);
        $aimId = intval($_REQUEST['aimId']);

        $data['username'] = getUserName($aimId);
        $data['userface'] = getUserFace($aimId);
        //$data['city'] = getUserCity($aimId);
        //为了兼容其它应用而使用uid
        $data['uid'] = $aimId;

        //主人的心情
        $bq_config = D('MiniConfig')->getConfig('mini');
        $bq_emotion = D('Smile')->getSmile($bq_config['smiletype']);
        $the_mini = D("Mini")->getOneMini($aimId,$bq_emotion,$bq_config['smiletype']);

        $data['mini'] = $the_mini['content'];

        $result = $this->api->share_addShare($type,$aimId,$data,$info);
        echo $result;
    }

    public function test(){
        $map['name'] = "'''''''''''<123dsfs>";
        D('User')->table('ts_test')->add($map);
    }
}
?>
