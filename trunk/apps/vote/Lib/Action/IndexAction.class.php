<?php
    class IndexAction extends Action{
        public $name;

    function _initialize(){
            //参数转义
            new_addslashes($_POST);
            new_addslashes($_GET);

            //设置心情Action的数据处理层
            $this->name = $this->my_name;
	}
    
    /*
     * 发起页
     */
	function addPoll()
	{
        $voteDao = D( 'Vote' );
        $date = date("Y-m-d-H",time()+$voteDao->getConfig( 'defaultTime' ));
        $date_arr = explode("-", $date);

        $datex["year"] = $date_arr[0];
        $datex["month"] = $date_arr[1];
        $datex["day"] = $date_arr[2];
        $datex["hour"] = $date_arr[3];

        $this->assign("date", $datex);

		$this->display(); 
	}

    /*
     * 添加一个投票
     */
    function add(){
     
        $deadline = mktime($_POST["hour"],0,0,$_POST["month"],$_POST["day"],$_POST["year"]);

        if($deadline < time()){
            $this->error("投票截止时间不能早于发起投票的时间！");
        }
        //检测选项是否重复
        $opt_test = array_filter($_POST['opt']);
        $opt_test_count = count(array_unique( $opt_test ));
        if( $opt_test_count < count($opt_test) ) $this->error( '投票不允许有重复项' );

  
        //投票表
        $voteDao = D("Vote");
        $voteUser = D( "VoteUser" );
 
        $voteDao->create();
        $voteDao->uid      =   $this->mid;
        $voteDao->deadline =   $deadline;
        $voteDao->name     = $this->name;
        $voteDao->cTime    = time();


        $vote_id = $voteDao->add();

        $voteUser->create();
        $voteUser->uid      =   $this->mid;
        $voteUser->name     = $this->name;
        $voteUser->vote_id  = $vote_id;
        $voteUser->cTime    = time();
        $voteUser->add();
        



        //选项表
        $feed = array();
        $optDao = D("VoteOpt");
        foreach($_POST["opt"] as $v){
            if(!$v) continue;
            $data["vote_id"]    =    $vote_id;
            $data["name"]       =    $v;
            $feed[] = $v;
            $optDao->add($data);
        }

        //动态
        $str = "";
        $vote_info["title"]  =    "<a href='".'{SITE_URL}'."/Index/pollDetail/id/".$vote_id."'>".t($_POST['title'])."</a>";
        $count = (count( $feed )>=3) ? 3 : count( $feed );
        for ($i = 1; $i < $count+1; $i++) {
            $str .= $i.".".$feed[$i-1]."<br />";
        }
        $vote_body['url'] = "<a href='".'{SITE_URL}'."/Index/pollDetail/id/".$vote_id."'>去投票</a>";
        $vote_body['body']   = $str;
        $result = $this->api->feed_publish("vote_add",$vote_info,$vote_body,$this->appId);


        if( $result ){
            $map['feedId'] = $result;
            $voteDao->where( 'id = '.$vote_id )->save( $map );
        }
        setScore($this->mid, 'creat_vote');

        $count = D('Vote')->where( 'uid='.$this->mid.' AND deadline>'.time() )->count();
        $result = $this->api->space_changeCount( 'vote',$count );

        $this->redirect("Index/pollDetail/id/{$vote_id}");

    }

    /*
     * 我的投票
     */
    function my(){

        $voteUserDao =       D("VoteUser");
        $voteDao     = D( 'Vote' );
              
        $map["uid"]  =       $this->mid;
                                                          
        if( isset( $_GET['action'] ) && 'add' == $_GET['action'] ){
            //我发布的
            $lists = $voteDao->where( $map )->order( 'id DESC' )->findPage($voteDao->getConfig( 'limitpage' ));
        }else if( isset( $_GET['action'] ) && 'in' == $_GET['action'] ){
            //我参与的
            $map = "uid = {$this->mid} AND opts <> ''";
            $temp         = $voteUserDao->where( $map )->field('distinct(vote_id)')->findAll();
            $lists = array();
            foreach( $temp as $value ){
                $lists[] = $value['vote_id'];
            }
            $where['id']   = array( 'in',$lists );
            $lists         = $voteDao->where( $where )->order( 'id DESC' )->findPage( $voteDao->getConfig( 'limitpage' ) );
        }else{
            //包括了我发布的和我参与的
            $temp         = $voteUserDao->where( $map )->field('distinct(vote_id)')->findAll();
            $lists = array();
            foreach( $temp as $value ){
                $lists[] = $value['vote_id'];
            }
            $where['id']   = array( 'in',$lists );
            $lists         = $voteDao->where( $where )->order( 'id DESC' )->findPage( $voteDao->getConfig( 'limitpage' ) );
        }

        $this->assign( $lists );
            
     
        $this->display();
    }

    /**
     * all 
     * 大家的投票
     * @access public
     * @return void
     */
    function all(){
        
        
		$voteDao = D('Vote');

        switch( $_GET['order'] ){
            case 'new':    //最新排行
                $order = 'cTime DESC';
                break;
            case 'vote':    //投票数排行
                $order = 'vote_num DESC';
                $map['cTime'] = self::_orderDate( $voteDao->getConfig( 'allorder' ) );//取得时间
                break;
            case 'comment':   //评论排行
                $order = 'commentCount DESC';
                $map['cTime'] = self::_orderDate( $voteDao->getConfig( 'allorder' ) );//取得时间
                break;
            default:      //默认时间排行
                $order = 'cTime DESC';
        }
        $this->assign( 'order',$_GET['order'] );

        $votes		= $voteDao->where($map)->order( $order )->findPage($voteDao->getConfig( 'limitpage' ));

        //选项
        $optDao = D("VoteOpt");
        foreach($votes['data'] as $k=>$v){
            $opts = $optDao->where("vote_id = {$v['id']}")->order("id asc")->field("*")->limit( '0,2' )->findAll();
            $votes['data'][$k]['opts'] = $opts;
        } 
        $this->assign($votes);

        $this->display();
    }


    /*
     * 好友的投票
     */
    function index(){
        
        $fri_arr = $xxx = $this->api->friend_get();

        //投票
		$voteDao = D('Vote');

		$in_str = $this->_getInArr($fri_arr);
        $time = time();
        if( isset( $_GET['action'] ) && 'add' == $_GET['action'] ){
            //朋友发表的
            $where = " uid In $in_str ";
        }else if( isset( $_GET['action'] ) && 'in' == $_GET['action'] ){
            //他参与的
            $xxx     = $this->api->friend_get();
            $fri_arr =  $_GET["uid"]?array($_GET["uid"]):$xxx;
		    $in_str  = $this->_getInArr($fri_arr);
            $map     =  "uid In $in_str AND opts <> ''";
            $userDao = D( 'VoteUser' );
            $temp    = $userDao->where( $map )->field('distinct(vote_id)')->findAll();
            $void   = array();
            foreach( $temp as $value ){
                $void[] = $value['vote_id'];
            }
            $where['id']   = array('in',$void);
        }else{
            $map = "uid IN $in_str";
            //朋友所有的投票
            $voteUserDao = D( 'VoteUser' );
            $temp        = $voteUserDao->where( $map )->field('distinct(vote_id)')->findAll();
            $lists       = array();
            foreach( $temp as $value ){
                $lists[] = $value['vote_id'];
            }
            $where['id']   = array( 'in',$lists );

        }
        $votes		= $voteDao->where( $where )->order( 'cTime desc' )->findPage($voteDao->getConfig( 'limitpage' ));

        //选项
        $optDao = D("VoteOpt");
        foreach($votes['data'] as $k=>$v){
            $opts = $optDao->where("vote_id = {$v['id']}")->order("id asc")->field("*")->limit( '0,2' )->findAll();
          //  echo $optDao->getLastSql();return;
            $votes['data'][$k]['opts'] = $opts;
        } 

        $this->assign('votes',$votes);

        $this->display();
    }

    /**
     * personal
     * 某个人的投票页
     * @access public
     * @return void
     */
    function personal(){
        
        $xxx = intval( $_GET['uid'] );
        if( empty( $xxx ) || 0 == $xxx ){
            $this->error( "意外错误，无法找到该用户的投票页。" );
            exit;
        }

        $name = $this->api->user_getInfo( $xxx );
        if( false == $name['name'] ){
            $this->error( "被删除或被屏蔽的不存在用户ID" );
            exit;
        }
        $this->assign( 'uid',$xxx );
        $this->assign( $name );

        //投票
		$voteDao = D('Vote');
        $time = time();
        if( isset( $_GET['action'] ) && 'add' == $_GET['action'] ){
            //朋友发表的
            $where = " uid = $xxx ";
        }else if( isset( $_GET['action'] ) && 'in' == $_GET['action'] ){
            //他参与的
            $map     =  "uid = $xxx AND opts <> ''";
            $userDao = D( 'VoteUser' );
            $temp    = $userDao->where( $map )->field('distinct(vote_id)')->findAll();
            $void   = array();
            foreach( $temp as $value ){
                $void[] = $value['vote_id'];
            }
            $where['id']   = array('in',$void);
        }else{
            $map = "uid = $xxx";
            //朋友所有的投票
            $voteUserDao = D( 'VoteUser' );
            $temp        = $voteUserDao->where( $map )->field('distinct(vote_id)')->findAll();
            $lists       = array();
            foreach( $temp as $value ){
                $lists[] = $value['vote_id'];
            }
            $where['id']   = array( 'in',$lists );

        }
        $votes		= $voteDao->where( $where )->order( 'cTime desc' )->findPage($voteDao->getConfig( 'limitpage' ));

        //选项
        $optDao = D("VoteOpt");
        foreach($votes['data'] as $k=>$v){
            $opts = $optDao->where("vote_id = {$v['id']}")->order("id asc")->field("*")->limit( '0,2' )->findAll();
          //  echo $optDao->getLastSql();return;
            $votes['data'][$k]['opts'] = $opts;
        } 

        $this->assign('votes',$votes);

        $this->display();
    }


    /*
     * 某个投票的详情
      */
         function pollDetail(){
             $id = intval($_GET["id"]);
             if( empty( $id ) || 0 == $id ){
                 $this->error( "非法访问投票页面" );
                 exit;
             }

             //投票详情
             $vote = D("Vote")->find($id);
             if( false == $vote ){
                 $this->error( "浏览的投票不存在或者被删除" );
             }
             $this->assign("vote", $vote);
             $this->setTitle($vote['title']);
             $this->assign( "api",$this->api );

             //投票选项
             $vote_opts = D("VoteOpt")->where("vote_id = $id")->order("id asc")->findAll();
             $this->assign("vote_opts", $vote_opts);

             //投票的参与者
             $test = D( 'VoteUser' );
             $vote_users = $test->where("vote_id = $id AND opts<>'' ")->findAll();

             //检查是否已投票和已经好友投票权限
             $has_vote     = false;
             //检查好友投票情况
             $empty_friend = false;
             $temp_uid     = array();
             if( "" == $vote_users[0] ){
                 $empty_friend = true;
             }else{
                 foreach( $vote_users as &$value ){
                     if( $this->api->friend_areFriends( $value['uid'],$this->mid )){
                         $value['isFriend'] = true;
                     }else if( $this->mid == $vote['uid'] || $this->uid == $value['uid'] ){
                         $value['admin'] = true;
                     }else{
                         if( 'friend' === $join = D( 'Vote' )->getConfig( 'join' )){
                            $value['Show'] = false;
                         }else{
                            $value['Show'] = true;
                         }
                     }
                     $notShow[] = ($value['Show'] || $value['admin'] || $value['isFriend']);
                     $temp_uid[] = $value['uid'];
                 }
                 $temp = array_filter( $notShow );
                 if( empty( $temp ) )
                     $empty_friend = true;

                  $has_vote  = ( in_array($this->mid,$temp_uid)) ?true:false;
             }
             $this->assign( 'has_vote',$has_vote );
             $this->assign( "empty_friend",$empty_friend );
             $this->assign("vote_users", $vote_users);
             $this->assign( "vote_join",$join );

             //投票的评论
             $vote_comms = D("VoteComm")->where("vote_id = $id")->order("cTime desc")->findAll();
             $this->assign("vote_comms", $vote_comms);

             //投票的百分比
             foreach($vote_opts as $v){
                 $nums[] = (int)($v['num']);
                 $total += (int)($v['num']);
             }
             foreach($nums as $v){
                 $pers[] = round(((float)$v/(float)$total)*100,0);
             }

             $this->assign('vote_pers',$pers);
             $this->display();
         }

    /*
     * 投票
      */
         function vote(){

             //用户投票信息
             $voteUserDao = D("VoteUser");

             //先看看有无权限投
             $vote_id      = intval($_POST["vote_id"]);
             //检查ID是否合法
             if( empty($vote_id) || 0 == $vote_id ){
                 $this->error( "错误的投票ID" );
                 exit;
             }

             $voteDao      = D( "Vote" );
             $the_vote     = $voteDao->where("id=$vote_id")->find();
             $onlyfriend   = $the_vote['onlyfriend'];
             $vote_user_id = $the_vote['uid'];
             $deadline     = $the_vote['deadline'];
             if( $deadline <= time() ){
                 echo -3;
                 return;
             }

             if($onlyfriend == "1" && $this->mid != $vote_user_id){
                 if( false == $this->api->friend_areFriends( $vote_user_id,$this->mid )){
                     echo -3;
                     return;
                 }

             }

             //再看看投过没
             $vote_id = intval($_POST["vote_id"]);
             $count = $voteUserDao->where( "vote_id=$vote_id AND uid=$this->mid AND opts <>''" )->count();
             if($count>0){
                 echo -1;
                 return;
             }
             //如果没投过，就添加
             $data["vote_id"] = $vote_id;
             $data["uid"] = $this->mid;
             $data["opts"]    = rtrim(t($_POST["opts"]),",");
             $data["cTime"]   = time();
             $data['name']    = $this->name;

             $addid = $voteUserDao->add($data);

             //投票选项信息的num+1
             $dao = D("VoteOpt");

             $opts_ids = rtrim(t($_POST["opts_ids"]),",");
             $opts_ids = explode(",",$opts_ids);

             foreach($opts_ids as $v){
                 $v = intval($v);
                 $dao->setInc("num","id=$v");
             }

             //投票信息的vote_num+1
             D("Vote")->setInc("vote_num","id=$vote_id");


             //动态
             $url = sprintf( '%s/Index/pollDetail/id/%s','{SITE_URL}',$vote_id);
             $vote_info["title"]  =    "<a href='".$url."'>".t($the_vote['title'])."</a>";
             $result = $this->api->feed_publish("vote_in",$vote_info,null,$this->appId);
             if( false != $result ){
                 $map['feedId'] = $result;
                 $voteUserDao->where( 'id ='.$addid )->save( $map );
             }
             setScore($this->mid, 'join_vote');
             setScore( $vote_user_id,'joined_vote');
             //通知
             $notify['opts'] = $data['opts'];
             $this->api->notify_setAppId($this->appId);
             $result = $this->api->notify_send($vote_user_id,'vote_in',$vote_info,$notify,$url);
             echo 1;
         }


    /**
     * deleteVote 
     * 删除投票
     * @access public
     * @return void
      */
         function deleteVote(){
             $id = intval($_POST['id']);
             if( empty( $id ) || 0 == $id ){
                 echo -1;
                 return ;
             }

             if( D( 'Vote' )->doDeleteVote( $id )){
                 echo 1;
                $count = D('Vote')->where( 'uid='.$this->mid )->count();
                $result = $this->api->space_changeCount( 'vote',$count );
             }else{
                 echo -1;
             }
         }

    /**
     * addOpt 
     * 添加候选项
     * @access public
     * @return void
      */
         function addOpt(){
             $id = intval( $_POST['id'] );
             if( empty( $id ) || 0 == $id ){
                 echo -1;
                 return;
             }
             $map['name'] = t( $_POST['name'] );
             $map['vote_id'] = $id;
             //查找这个投票的所有选项
             $voteDao = D( 'VoteOpt' );
             $vote_opt = $voteDao->where( "vote_id = {$id}" )->field()->findAll();
             //没有找到对应的选项。这一个投票项不存在
             if( false == $vote_opt ){
                 echo -2;
                 return;
             }else{
                 //找到后，对比新添加的name,如果相同。。返回错误提示
                 foreach( $vote_opt as $value ){
                     if( $map['name'] == $value['name'] ){
                         echo -3;
                         return;
                     }
                 }

                 //将新的选项添加
                 if( $result = $voteDao->add( $map ) ){
                     echo 1;
                     return;
                 }else{
                     echo 0;
                     return;
                 }
             }


         }

    public function editDate(){
        $id = intval( $_POST['id'] );
        if( empty( $id ) || 0 == $id ){
            echo -1;
            return;
        }
        $map['id'] = $id;
        //查找这个投票的信息
        $voteDao = D( 'Vote' );
        $vote_opt = $voteDao->where( "id = {$id}" )->field()->find();
        //没有找到对应的选项。这一个投票项不存在
        if( false == $vote_opt ){
            echo -2;
            return;
        }else{

            $deadline = mktime($_POST["hour"],0,0,$_POST["month"],$_POST["day"],$_POST["year"]);

            if($deadline < time()){
                echo -3;
                return;
            }
            $save['deadline'] = $deadline;

            //将新的选项添加
            if( $result = $voteDao->where( $map )->save( $save ) ){
                echo 1;
                return;
            }else{
                echo 0;
                return;
            }
        }
    }

    function _getInArr($in_arr){

        $in_str = "(";
        foreach($in_arr as $key=>$v){
            $in_str .= $v.",";
        }
        $in_str = rtrim($in_str,",");
        $in_str .= ")";
        return $in_str;

    }

        /**
         * _orderDate 
         * 解析日志排序时间区段
         * @param mixed $options 
         * @access private
         * @return void
         */
        private function _orderDate( $options ){
            $time = explode('-',date( 'Y-n-j',time() ));
            list( $now_year,$now_month,$now_day ) = $time;
            //定义偏移量
            $month = 0;
            $year = 0;
            $day = 0;
            switch ( $options ) {
                case 'all': //所有日志
                    return array( 'lt',time() );
                    break;
                case 'one': //一个月以内的日志
                    $month = 1;
                    break; 
                case 'three': //3个月以内的日志
                    $month = 3;
                    break;
                case 'half': //6个月以内的日志
                    $month = 6;
                    break;
                case 'year': //一年以内的日志
                    $year  = 1;
                    break;
                case "oneDay":
                    $day = 1; //一天以内的
                    break;
                case "threeDay":
                    $day = 3; //三天以内的
                    break;
                case "oneWeek":
                    $day = 7; //一周以内
                    break;
            }
            //换算时间戳
            $toDate = mktime( 0,0,0,$now_month-$month,$now_day-$day,$now_year-$year );
            //返回数组型数据集
            return array( "between",array( $toDate,time() ) );
        }
        
        function addShare_check(){
        	
        	$result = 1;
        	
		    $aimId = $_REQUEST['aimId'];

//		    $blog = D('Vote')->where("id='$aimId'")->field('uid')->find();
//		    if($blog['uid']==$this->mid){
//		    	$result = -1;
//		    }else{
		    	$test = $this->api->share_isForbid($this->mid,13,$aimId);

		    	if($test==-1){
		    		$result = -2;
		    	}
//		    }
		    
            echo $result;
       
	    }	
        function addShare(){
		    $aimId = $_REQUEST['aimId'];
		    $this->assign('aimId',$aimId);
		    $blog = D('Vote')->where("id='$aimId'")->field('title')->find();
		    
		    $this->assign('title',$blog['title']);
		    $this->assign($blog);
			
		    $this->display();
	    }
	    
	    function doaddShare(){
	    	$type['typeId'] = 13;
	    	$type['typeName'] = '投票';
	    	$type['alias'] = 'vote';
	    	
		    $info = h($_REQUEST['info']);
	    	$aimId = intval($_REQUEST['aimId']);
		    
		    $field = 'name,uid,title';
		    $data = D('Vote')->where("id='$aimId'")->field($field)->find();
		    //$data['title'] = h($_REQUEST['title']);
		    $fids = $_REQUEST['fids'];
	    	
	    	$result = $this->api->share_addShare($type,$aimId,$data,$info,0,$fids); 
	    	echo $result; 	
	    }

        /**
         * commentSuccess 
         * 评论成功回调函数
         * @access public
         * @return void
         */
        public function commentSuccess(){
        	$result = json_decode(stripslashes($_POST['data']));  //json被反解析成了stdClass类型
        	//计数更新
                $voteDao = D( 'vote' );
        	$count = $this->__setCount($result->appid,$voteDao);
        	
        	 //发送两条消息
                $data = $this->__getNotifyData($result, $dao);
                $this->api->comment_notify('vote',$data,$this->appId);
                echo $count;
            }

            public function __getNotifyData($data,$dao) {
                $result = array();
                //发送两条消息
                $result['toUid'] = $data->toUid;
                $need  = $dao->where('id='.$data->appid)->field('uid,title')->find();
                $result['uids'] =$need['uid'];
        	$result['url'] = sprintf('%s/Index/pollDetail/id/%s','{SITE_URL}',$data->appid);
                $result['title_body']['comment'] = $data->comment;
                $result['title_data']['title'] = sprintf("<a href='%s'>%s</a>",$result['url'],$need['title']);
                $result['title_data']['type']  = "投票";
                return $result;
            }

            private function __setCount($id,$dao) {
                $count = $this->api->comment_getCount('vote',$id);
                $dao->setCount($id,$count);
                return $count;
            }
        }
?>
