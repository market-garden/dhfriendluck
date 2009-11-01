<?php

class FriendAction extends BaseAction {
    /*
     * 我的好友
     */
        public function index() {

                $gid = intval($_GET["gid"]);
                $uid = intval($_GET["uid"]);
                if($uid) {
                        $is_me = ($this->mid == $uid);
                }else {
                        $is_me = true;
                }
                $this->assign('is_me',$is_me);
                $this->assign("uid",$uid);

                //带分页的调用API方法,最后一个参数是显示多少条
                $friends = $this->api->friend_getIdName($uid,$gid,10);
                $this->assign("friends",$friends["data"]);
                $this->assign("total_page",$friends["total_page"]);
                $this->display();
        }


    /*
     * 删除好友
     */
        function removeFriend() {
                $fuid = intval($_POST["fuid"]);
                $dao  = D("Friend");

                //从好友表中删除两条记录
                $map = "(uid = $this->mid AND fuid = $fuid) OR (uid = $fuid AND fuid = $this->mid)";
                $dao->where($map)->delete();

                //删除好友分组中2个人的关系
                D("Fg")->where($map)->delete();

                echo 1;

        }

    /*
     * 添加分组
     */
        function addGroup() {

                $dao = D("FriendGroup");
                $dao->create();
                $dao->uid = $this->mid;

                echo $dao->add();
        }



     /*
      * 设置分组，选择页
      */
        function setGroup() {

                $map4 = "id !=1 AND uid = 0 OR uid = ".$this->mid;
                $user_groups = D("FriendGroup")->where($map4)->findAll();
                $this->assign('user_groups',$user_groups);



                //好友关系分组
                $fuid = intval($_GET["fuid"]);
                $map["uid"]  = $this->mid;
                $map["fuid"] = $fuid;
                $gids = D("Fg")->where($map)->field("gid")->findAll();
                foreach($gids as $v) {
                        $fgids[] = $v["gid"];
                }
                $this->assign('fgids',$fgids);

                $this->assign("uid",$fuid);
                $nid = $_GET["nid"]?intval($_GET["nid"]):0;
                $this->assign("nid",$nid);
                $this->display("agree");
        }

     /*
      * 设置分组
      */
        function doSetGroup() {

                $fuid = intval($_POST["fuid"]);
                $daoG = D("Fg");

                //先删除之前的好友关系
                $data_g["uid"]  = $this->mid;
                $data_g["fuid"] = $fuid;
                if($fuid) $daoG->where($data_g)->delete();


                //重新插入
                $gids = explode(",",$_POST["gids"]);
                foreach($gids as $k=>$v) {
                        $data_g["gid"]  = $v;
                        $daoG->add($data_g);
                }

                //处理通知
                $nid = intval($_POST["nid"]);
                if($nid) {
                        $map_n["id"]   = $nid;
                        $data_n["new"] = 2;
                        D("Notify")->where($map_n)->save($data_n);
                }

                echo 1;

        }

     /*
      * 删除分组
      */
        function delGroup() {
                $gid = intval($_POST["id"]);
                $dao = D("FriendGroup");
                $dao2 = D( 'Fg' );
                $condition['gid'] = $gid;
                $map['gid'] = 1;
                $dao2->where( $condition )->save( $map );

                echo $dao->delete($gid);

        }



     /*
     * 好友足迹
     *
     */
        function track() {

                if($_GET["t"] != "visit") {
                        $map["uid"] = $this->mid;
                }else {
                        $map["visitId"] = $this->mid;
                }
                $visitors = D("Visitor")->where($map)->order("cTime desc")->findPage(36);
                $this->assign("visitors",$visitors["data"]);
                $this->assign("page",$visitors["html"]);



                $this->display();
        }


     /*
     * 好友屏蔽
     *
     */
        function ping() {

                $map["uid"] = $this->mid;
                $pings = D("FriendPing")->where($map)->order("id desc")->findPage(10);
                $this->assign("pings",$pings["data"]);
                $this->assign("page",$pings["html"]);



                $this->display();
        }


     /*
     * 添加屏蔽
     *
     */
        function addPing() {
                $pingUserIds = explode(",",$_POST["fri_ids"]);
                $dao = D("FriendPing");
                $data["uid"] = $this->mid;

                foreach($pingUserIds as $k=>$id) {
                        $data["fuid"] = $id;
                        $user = $dao->where($data)->find();
                        if(!$user) {
                                $dao->add($data);
                        }
                }

                $this->redirect("ping");
        }
     /*
     * 解除屏蔽
     *
     */
        function removePing() {
                $map["fuid"] = intval($_POST["fuid"]);
                $map["uid"] = $this->mid;
                echo D("FriendPing")->where($map)->delete();
        }



     /*
     * 查找好友
     *
     */
        function lists() {


                $type = t($_GET["type"]);

                //学校
                if($type == "school") {

                        $dao = D("EduSearch");

                        if($_GET["school"])  $map["school"] = $_GET["school"];
                        if($_GET["class"])   $map["class"]  = $_GET["class"];
                        if($_GET["year"])    $map["year"]   = $_GET["year"];
                        if($_GET["name"])    $map["name"]   = $_GET["name"];

                        $data = $dao->field('distinct(uid)')->where($map)->findPage(10);


                }

                //公司
                if($type == "company") {

                        $dao = D("WorkSearch");

                        if($_GET["company"])  $map["company"] = array('like','%'.$_GET["company"].'%');
                        if($_GET["name"])  $map["name"] = array('like','%'.$_GET["name"].'%');
                        $data = $dao->field('distinct(uid) as uid, name')->where($map)->findPage(10);

                }

                //信息
                if($type == "info") {
                        $name = t(trim($_GET['name']));
                        if( empty($name) && (empty($_GET['sex']) && empty($_GET['ts_area']) )  ) {
                                $this->assign("total_num",0);
                                $this->display();
                                exit;
                        }
                        if($_GET["name"])             $map["name"]          =   t(trim($_GET["name"]));
                        if($_GET["ts_area"])     $map["ts_area"]  =  $_GET['ts_area'] ;
                        if(isset($_GET["sex"]))       $map["sex"]           =   intval($_GET["sex"]);
                        $data = $this->__searchByInfo($map);

                }



                //email
                if($type == "email") {
                        $email = t($_GET['email']);
                        $dao = D("User");

                        if($_GET["email"])$data = $dao->findUserByField('email',trim($email));



                }


                //id
                if($type == "id") {
                        $dao = D("User");
                        if($_GET["id"])             $data = $dao->findUserByField('id',intval(trim($_GET["id"])));

                }
                $this->assign("page",$data["html"]);
                $this->assign("data",$data["data"]);
                $this->assign("total_num",$data['count']);

                $this->display();

        }



        //------------------------------------------------以下是选择好友组件相关-----------------------------------
        public function ajax() {
                $name = $_GET["name"];
                $dao = D("Friend");

                $map["uid"]		 =  $this->mid;
                $map["status"]	 =  1;
                $map["fusername"] = array("LIKE",$name."%");

                $friends = $dao->where($map)->findAll();

                foreach($friends as $k=>$v) {
                        $out[$k]["fUid"] = $v["fuid"];
                        $out[$k]["friendUserName"] = $v["fusername"];
                        $out[$k]["friendHeadPic"] = getUserFace($v["fuid"]);
                }

                echo json_encode( $out );
        }

        public function getAllFriends() {

                $gid = intval($_GET["type"]);

                $friends = $this->api->friend_getIdName($this->mid,$gid,intval($_GET["pageSize"]));

                foreach($friends["data"] as $k=>$v) {
                        $out[$k]["fUid"] = $v["fuid"];
                        $out[$k]["friendUserName"] = $v["fusername"];
                        $out[$k]["friendHeadPic"] = getUserFace($v["fuid"]);
                }

                echo json_encode($out);

        }

        public function getFriendType() {
                $map = "uid = 0 or uid = ".$this->mid;
                $friendType = D("FriendGroup")->where($map)->field("id,name")->findAll();
                echo json_encode( $friendType );
        }

        public function getCountUrl() {
                $gid = $_GET["typeId"]?intval($_GET["typeId"]):false;
                echo $this->api->friend_getFriNum($this->mid,$gid);
        }

        //----------------------------------------------组件 end----------------------------------------------






        //以下是加好友相关
        public function isAdd() {
                $fuid = intval($_GET["uid"]);

                //不允许加自己好友
                if($fuid == $this->mid) {
                        $this->__addFrendsError('不允许自己加自己好友');
                }

                //加好友的权限设置
                if($_GET['t'] != "agree") {
                        $this->__checkeFriendPrivacy($fuid);
                }

                //检查好友状态
                $is_add = $this->__checkFriendStatus($this->mid, $fuid);
                if("1" === $is_add) {
                        $this->__addFrendsError('你们已经是好友了');
                }else {
                        if("0" === $is_add) {
                                $this->__addFrendsError("等待验证中");
                        }
                }

                //对方已经发过请求了，直接就加为好友
                if ("0" === $this->__checkFriendStatus($fuid, $this->mid)) {
                        $this->__straightAddFrends($fuid,intval($_GET['nid']));
                }

                $fri_tip = D("FriendTip")->get($fuid);
                $this->assign("fri_tip",$fri_tip);

                $this->display();
                exit();
        }

        private function __addFrendsError($msg) {
                $this->assign("con",$msg);
                $this->display("tip");
                exit();
        }

        private function __countFriendsPrivacy() {
                $my_friends = $this->api->friend_get($this->mid);
                $f_friends = $this->api->friend_get($fuid);
                $num = count(array_intersect($my_friends, $f_friends));
                return $num;
        }

        private function __checkFriendStatus($uid,$fuid) {
                $dao = D("Friend");
                //已发过请求，但还未处理
                $map["uid"]    =   $uid;
                $map["fuid"]    =  $fuid;
                $result         =   $dao->where($map)->getField('status');
                // echo $dao->getLastSql();
                return $result;
        }
        private function __checkeFriendPrivacy($fuid) {
                $friend_privacy = $this->api->privacy_get($fuid,"friend");
                if($friend_privacy == 1) {
                        if(0 == $this->__countFriendsPrivacy()) {
                                $this->__addFrendsError('仅好友的好友能加为好友');
                        }
                }
        }
        private function __straightAddFrends($fuid,$getnid) {
                $map4 = "id !=1 AND uid = 0 OR uid = ".$this->mid;
                $user_groups = D("FriendGroup")->where($map4)->findAll();

                //获取通知请求
                $nid = $this->__getNotifyId($getnid, $fuid);

                $this->assign("uid", $fuid);
                $this->assign("nid", $nid);
                $this->assign('user_groups',$user_groups);
                $this->display("agree");
                exit();
        }

        private function __getNotifyId($nid,$fuids) {
                if(!$nid) {
                //从Notify表中查出相应的id
                        $map_n["uid"] = $this->mid;
                        $map_n["type"] = "add_friend";
                        $map_n["authorid"] = $fuids;
                        $notify = D("Notify")->where($map_n)->field("id")->find();
                        $result = $notify["id"];
                        return $result;
                }
                return $nid;
        }



        public function addFriend() {
                $this->checkJsToken();
                //发送请求
                $dao = D("Friend");
                $dao->create();
                $dao->uid       =   $this->mid;
                $dao->fusername =   getUserName(intval($_POST['fuid']));
                $dao->dateline = time();
                echo $dao->add();


                //通知
                $type = "add_friend";
                $body_data["note"] = $_POST["note"];
                $url  = $this->mid;
                $cate = "friend";
                $this->api->notify_send(intval($_POST['fuid']),$type,$title_data,$body_data,$url,$cate);
                exit;
        }

        public function agreeFriend() {
                $this->checkJsToken();
                $fuid	=	intval($_POST["fuid"]);
                $gids	=	t($_POST['gids']);
                $daoG	=	D("Fg");
                $dao	=	D("Friend");

                $fusername	=	getUserName($fuid);
                $has_friend	=	$this->api->friend_areFriends($this->mid,$fuid);

                //好友表
                $dao->agreenRequest($fuid,$this->mid,$fusername);

                $dao->create();
                $dao->addFriend($this->mid,$fusername);

                //------------再插入一条我的------------------
                //1、先在分组关系表中插入我的相关数据
                $daoG = D("Fg");
                $daoG->addAgreeFriendGroup($this->mid,$fuid,$gids);

                //处理通知
                $map_n["id"]   = intval($_POST["nid"]);
                $data_n["new"] = 2;
                D("Notify")->where($map_n)->save($data_n);
                if(false == $has_friend ) {
                //再给对方发一条通知
                        $type = "agree_friend";
                        $cate = "friend";
                        $r = $this->api->notify_send($fuid,$type,$title_data,$body_data,$url,$cate);

                        //发送动态
                        $this->__doFeed($this->mid, $this->my_name, $fuid, $fusername);
                }
                echo 1;
        }

        private function __doFeed($mid,$name,$fuid,$fusername) {
        //双方发送动态
                $friendFeedId1 = $this->__sendFeed($mid, $name, $fuid, $fusername);
                $friendFeedId2= $this->__sendFeed($fuid, $fusername,$mid, $name );
        }

        private function __sendFeed($mid,$name,$fuid,$fusername) {
                $type = "add_friend";
                $user1 = sprintf("<a href='%s/space/%s'>%s</a>",'{__TS__}',$mid,$name);
                $user2 = sprintf("<a href='%s/space/%s'>%s</a>",'{__TS__}',$fuid,$fusername);
                $title['user'] = $user1;
                $title['fuser'] = $user2;

                $setUid = $this->api->feed_setUid($mid);
                return $this->api->feed_publish($type,$title,$body,$this->appId);
        }


        public function ignoreFriend() {
        //处理通知
                $map_n["id"]   = intval($_POST["nid"]);
                $data_n["new"] = 3;
                D("Notify")->where($map_n)->save($data_n);

                //好友数据删除
                $map['uid'] = intval( $_POST['uid'] );
                $map['fuid'] = intval( $this->mid);
                $map['status'] = 0;
                $dao = D( 'Friend' );
                $dao->where( $map )->delete();



                echo 1;

        }

        private function __searchByField($data) {
                return D('User')->findUserByField($data);
        }

        private function __searchByInfo($data) {
                if(isset($data['name'])) {
                        $name = $data['name'];
                        unset($data['name']);
                }
                return D('User')->findUser($name,$data);
        }

        private function __searchByConfrere($data) {
                $uid = D('WorkSearch')->findUser($data);
                $map['uid'] = array('in',$uid);
                return D('User')->where($map)->findPage(10);
        }

}
?>
