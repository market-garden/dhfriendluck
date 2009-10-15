<?php
class HomeAction extends BaseAction {


	/*
	 *  首页
	 *
	 */
    public function index() {
         $this->setJsToken();
    //读取心情配置
    //$bq_config = ts_cache( 'miniconfig' );
        $appconfig	=	D('AppConfig');
        $appconfig->setAppname('mini');
        $bq_config	=	$appconfig->getConfig();

        //从缓存中读出表情,并渲染模板
        $this->__getMini ($bq_config);

        //我的好友id
        $fuids = $this->api->friend_get();

        if( !empty($fuids) ) {
        //获取好友
            $this->__getFriends ( $fuids );
            //获取可能认识的人
            $this->__getMayUser ( $fuids );
        }

        //最近访客
        $this->__getVisitors ();

        //通知数
        $notify_num = $this->api->notify_getNewNum();
        $this->assign('notify_num',$notify_num);
        
        //公告
        $gonggao = $this->opts["gonggao"];
        $gonggao_open = $this->opts["gonggao_open"];
        $this->assign("gonggao",$gonggao);
        $this->assign("gonggao_open",$gonggao_open);

        $this->display();
    }
    private function __getVisitors() {
        $visitor = D('Visitor');
        $visitors     =   $visitor->get(6,$this->mid);
        $visitor_num  =   $visitor->getNum($this->mid);
        $this->assign("visitors",$visitors);
        $this->assign("visitor_num",$visitor_num);
    }

    private function __getMayUser($fuids) {
        $dao = D('User');
        $fuids_str = implode(",",$fuids);
        $map_where = "( `id` NOT IN (".$fuids_str.",".$this->mid.") ) AND ( `active` = 1 )";
        $may_users = $dao->where($map_where)->limit(6)->field("id,name")->order("id desc")->findAll();
        $this->assign("may_users",$may_users);
    }

    private function __getFriends($fuids) {
        $temp_uids = array_slice($fuids, 0, 6);
        $map_fri["id"] = array("IN",$temp_uids);
        $u_fris = D("User")->where($map_fri)->field("id,name")->findAll();
        $this->assign("u_fris",$u_fris);
        $this->assign("fuids",$fuids);
    }

    private function __getMini($config) {
        $bq_emotion = D('Smile')->getSmile($config['smiletype']);

        $this->assign("bq_emotion",$bq_emotion);
        $this->assign( 'smiletype',$config['smiletype'] );
        $this->assign( 'stringcount',$config['stringcount'] );

        //我的心情
        $my_mini = D("Mini")->getOneMini($this->mid,$bq_emotion,$config['smiletype']);
        $this->assign("my_mini",$my_mini);
    }


    public function network() {
        $commenduser = $this->api->user_getCommendUser(18);
        $map['active'] = 1;
        $user = D('User')->where( $map )->limit('0,18')->order('rand()')->findAll();
        if($commenduser){  //加个判断,以免出错 edit By Nonant 09-09-09
	        $map['id'] = array('in',$commenduser);
	        $c_user = D('User')->where( $map )->limit('0,18')->order('rand()')->findAll();
	        $this->assign('commenduser',$c_user);
        }
        $this->assign('user',$user);
        $this->display();
    }


    /*
     * 动态
     *
     */
    function feed() {
    //动态
        $request = $this->__getREQUEST();
        $opts = $this->api->option_get('thinksns');
        $fri_feeds = $this->_get_fri_feeds($request['who'],$request['type'],$request['p'],$opts['home_feed']);
        $this->assign( 'type',$_REQUEST['type'] );
        //dump($fri_feeds);
        $this->assign('fri_feeds',$fri_feeds);

        $this->display();
    }

    /*
     * 根据类型,获取好友动态
	 *
     */
    public function _get_fri_feeds($who,$type,$p,$limit=40) {
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

    public function allFeed() {
    //得到所有好友分组
        $friends_group = D( 'FriendGroup' )->getOneGroup( $this->mid );
        $count = $this->getFeedCount();
        $page = $this->__getPageLimitSecond($count, 20);
        $page = $page>50?50:$page;
        $this->assign( 'allGroup',$friends_group );
        $this->assign( 'group',$_GET['who']?$_GET['who']:"" );

        $this->assign( 'count',$count );
        $this->assign( 'type',$_GET['type']?$_GET['type']:"" );
        $this->assign('page',intval($_GET['p']));
        $this->assign('page_count',$page);
        $this->display();
    }
     public function __getPageLimitSecond($count,$pageLimit) {
        return (int)ceil($count/$pageLimit);
    }

    public function getFeedCount() {
        $request = $this->__getREQUEST();
        $uid = intval($_GET['uid']);
         if(!empty($uid)){
              return $this->api->feed_getCount( $request,$uid);
         }else{
              return $this->api->feed_getCount( $request);
         }
       
    }


    private function __getREQUEST(  ) {
        $request['type'] = $_REQUEST["type"]?$_REQUEST['type']:'all';
        $request['p']	 = $_REQUEST["p"];
        if(isset($_REQUEST['user']) && 'all' == $_REQUEST['user']) {
            $request['who'] = null;
            return $request;
        }

        if( is_numeric( $_REQUEST['user'] ) ) {
            $request['who'] = $_REQUEST['user'];
            return $request;
        }
        if( isset( $_REQUEST['who'] ) && !empty( $_REQUEST['who'] ) &&  'all' !== $_REQUEST['who']  ) {
            $request['who'] = $this->api->friend_getGroupUids( $_REQUEST['who'] );
        }else {
            $request['who'] = 'fri';
        }
        return $request;
    }
}
?>
