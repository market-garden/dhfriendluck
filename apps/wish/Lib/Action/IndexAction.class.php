<?php
    /**
     * IndexAction 
     * Wish的Action.接收和过滤网页传参
     * @uses Action
     * @package 
     * @version $id$
     * @copyright 2009-2011 SamPeng 
     * @author SamPeng <sampeng87@gmail.com> 
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class IndexAction extends Action{
        private $filter;
        private $wish;
        private $lastWish;
        protected $app = null;
        /**
         * __initialize 
         * 初始化
         * @access public
         * @return void
         */
        public function _initialize(){
            //设置愿望Action的数据处理层
            $this->wish = D( 'Wish' );
            $this->wish->setUid( $this->mid );  //设置登录者uid
            
        }
        /**
         * index 
         * 好友的愿望
         * @access public
         * @return void
         */
        public function index(){
            //获取好友列表id
            $gid        = $_GET['gid'];
            $friends    = $this->api->friend_getGroupUids($gid);
            $friends_id = empty( $friends ) ?null: array( "in",$friends);

            //获取配置，是否可以查看全部的愿望
			
            if( $this->wish->getConfig( 'all' ) ){
                $this->assign( 'all','true' );
            }


            //获得登录者最后的愿望.其中包含表情列表
            $lastWish = $this->wish->getLastWish( $this->mid,'content,cTime,id');

            if( !$friends_id ){
                $this->assign( 'ico_list',$lastWish[1] );//表情列表
                $this->assign( $lastWish[0] );//最后一条愿望
                $this->display();
                return ;
            }

            //获得愿望数据集
            $list = isset( $friends_id )?$this->__getWish($friends_id,'*','cTime desc'):false;

            $this->assign( $list );
            $this->assign( 'ico_list',$lastWish[1] );//表情列表
            $this->assign( 'ico_type',$this->wish->getConfig( 'smiletype' ) );
            $this->assign( 'stringcount',$lastWish[2] );
            $this->assign( $lastWish[0] );//最后一条愿望
            $this->assign( 'friend_list',$this->wish->uid );
            $this->display();

        }

        /**
         * friends 
         * 某一个人的愿望页面
         * @access public
         * @return void
         */
        public function friends(){
            $uid = intval($_GET['uid']);

            if( $uid == $this->mid ){
                    $this->redirect('/Index/my/uid/'.$_GET['uid']);
            }
            //检测合法传值id
            if( empty( $uid ) || 0 == $uid ){
                $this->error( L( "error_id" ) );
            }
            $this->setJsToken();
            //获取数据集
            $link = "Index/friends/uid/".$uid;
            $list = $this->internalAssign($uid,$link);

            //取出用户名
            foreach ($list['data'] as $value) {
                $username = $value['name'];
                break;
            }
            
            if(empty($username)){
            	$name = $this->api->user_getInfo($uid,'name');
            	$username = $name['name'];
            }

            $this->assign( 'uid',$uid );
            $this->assign( $list );
            $this->assign('name', $username );  //模板标签 name
            $this->display();
        }

        /**
         * my 
         * 我的愿望
         * @access public
         * @return void
         */
        public function my(){
            //取出愿望数据集合
            if( !isset( $_GET['replay'] ) ){
                $link = "Index/my";
                $list = $this->internalAssign($this->mid,$link); //我的愿望
            }else{
                $link = "Index/my/replay/do";
                $list = $this->internalAssign( $this->mid,$link,true ); //我的回复
            }

            if( $this->wish->getConfig( 'all' ) ){
                $this->assign( 'all','true' );
            }
             $this->setJsToken();
            //获得最后一条wish博客
            $lastWish = $this->wish->getLastWish( $this->mid,'content,cTime,id');

            //获得表情列表
            $this->assign( 'ico_list',$lastWish[1] );
            $this->assign( 'ico_type',$this->wish->getConfig( 'smiletype' ) );
            $this->assign( $lastWish[0] );
            $this->assign( 'stringcount',$lastWish[2] );

            $this->assign( $list );
            $this->display();

        }

        public function all(){
            //检查是否可以查看这个页面
            if( $this->wish->getConfig( 'all' ) ){
                //检查归档是否打开
                if( $this->wish->getConfig( 'fileaway' ) ){
                        if( isset( $_GET['date'] ) ){
                            $this->wish->status = 0;
                            $list = $this->wish->fileAway( $_GET['date'] );
                        }else{
                            $list = $this->wish->getWishList(null,null,'cTime desc');
                        }
                        //获得归档的widget
                        $map = null;
                        $link = 'Index/all';
                        $wiget = $this->_getWiget($link,null);

                        $this->assign( 'file_away',$wiget );
                }else{

                    $list = $this->wish->getWishList();
                }

                    //获得最后一条wish博客和表情
                    $lastWish = $this->wish->getLastWish( $this->mid,'content,cTime,id');

                    //对数据集进行判断。如果是自己发的愿望，可以删除
                    $this->assign( 'ico_list',$lastWish[1] );
                    $this->assign( 'ico_type',$this->wish->getConfig( 'smiletype' ) );
                    $this->assign( $lastWish[0] );
                    $this->assign( 'stringcount',$lastWish[2] );
                    $this->assign( $list );
                    $this->display();

            }else{
                $this->error( L( 'error_all' ) );
            }
        }
        
         public function search(){
         	$idea = t($_POST['search_idea']);
         	$who  = intval($_POST['search_who']);
         	$area = t($_POST['ts_search_area']);
         	$age_from = intval($_POST['search_age_from']);
         	$age_to = intval($_POST['search_age_to']);

            $area = explode(',',$area);
            if(!empty($area[0])){
            	$map['provinceId'] = $area[0];
            }
            if(!empty($area[1])){
            	$map['cityId'] = $area[1];
            }  
            if(!empty($idea)){
            	$map['idea'] = array('like',"%$idea%");
            }
            if(!empty($who)){
            	$map['who'] = $who;
            }                       
            if(!empty($age_from)){
            	$map['age_from'] = array('EGT',$age_from);
            }
            if(!empty($age_to)){
            	$map['age_to'] = array('ELT',$age_to);
            }            
         	$map['uid'] = array('NOT IN',$this->mid);        	

         	
         	//检查归档是否打开
         	if( $this->wish->getConfig( 'fileaway' ) ){
         		if( isset( $_GET['date'] ) ){
         			$this->wish->status = 0;
         			$list = $this->wish->fileAway( $_GET['date'] );
         		}else{
         			$list = $this->wish->getWishList($map,null,'cTime desc');
         			//dump($this->wish->getLastSql());
         		}
         		//获得归档的widget
         		$map = null;
         		$link = 'Index/all';
         		$wiget = $this->_getWiget($link,null);

         		$this->assign( 'file_away',$wiget );
         	}else{

         		$list = $this->wish->getWishList($map);
         		//dump($this->wish->getLastSql());
         	}

         	//获得最后一条wish博客和表情
         	$lastWish = $this->wish->getLastWish( $this->mid,'content,cTime,id');

         	//对数据集进行判断。如果是自己发的愿望，可以删除
         	$this->assign( 'ico_list',$lastWish[1] );
         	$this->assign( 'ico_type',$this->wish->getConfig( 'smiletype' ) );
         	$this->assign( $lastWish[0] );
         	$this->assign( 'stringcount',$lastWish[2] );
         	$this->assign( $list );
         	$this->display();
        }       
        /**
         * doDeleteWish 
         * 删除wish
         * @access public
         * @return void
         */
        public function doDeleteWish(  ){

            $id = intval($_REQUEST['id']);
            if( empty( $id ) || 0 == $id ){
                echo -1;
                exit;
            }

            $this->wish->id = $id; //要删除的id
            $result         = $this->wish->doDeleteWish();
            if( false != $result ){
                //关联删除这一条愿望下面的所有回复
                $comment       = D( 'Comment' );
                $data['appid'] = $id;
                $data['type']  = APP_NAME;

                $comment->deleteComment($data);
                
                //关联删除这一条愿望关联的动态
                $this->wish->deleteFeed( $id );
                echo 1;
            }else{
                echo "-1";
            }

        }

        /**
         * doAddWish 
         * 添加wish
         * @access public
         * @return void
         */
        public function doAddWish(){
            $this->checkJsToken();
            $idea = t($_POST['idea']);
            if( empty($idea) ){
                $this->error('请填写你的愿望！');
            }
            
            $content = '想';
            
            $area = t($_POST['ts_hometown']);
            if(!empty($area)){
            	$areaName = getAreaInfo($area);
            	$area = explode(',',$area);
            	$data['provinceId'] = $area[0];
            	$data['cityId'] = $area[1];
            	
            	 $content .= '在'.$areaName.' ';
            }

            if(!empty($_POST['who'])){
            	$data['who'] = intval($_POST['who']);
            	$data['age_to'] = intval($_POST['age_to']);
            	$data['age_from'] = intval($_POST['age_from']);
            	
            	$content .= getWho($data['who']).','.$data['age_from'].'-'.$data['age_to'].'岁 ';
            }
            
            $data['idea'] = $idea;
            $data['uid'] = $this->mid;
            $data['tell'] = intval($_POST['tell']);
            $data['content'] = $content.$idea;
            
            $add = $this->wish->doAddWish($data);
            if( $add ){
                $this->success('增加成功！');
            }else{
            	$this->error('增加失败！');
            }
        }

        /**
         * getReplay 
         * 获得剩余的Replay;
         * @access public
         * @return void
         */
        public function getReplay(){

            $id   = $_POST['id'];
            $uid  = $_POST['uid'];

            //获取的回复
            if( $result = $this->wish->_internalReplay( $id,null,$uid ) ){
                echo json_encode( array( "OddReplay"=>$result) );
            }else{
                echo -1;
            }
        }
        
        /**
         * doAddReplay 
         * 添加回复
         * @access public
         * @return void
         */
        public function doAddReplay(){
            //获取数据
            $data['comment'] = t( $_POST['content']);
            $data['appid']   = t( $_POST['id'] );
            $data            += $this->wish->getOneName( $this->mid ); //追加发表人的名字
            $data['uid']     = $this->mid;

            //空数据不予以执行
            if( empty($data['comment']) ){
                echo "-1";
                return false;
            }
            
            //插入数据库并返回结果
            if( $result = $this->wish->addReplay($data,$_POST['more'],$_POST['mid']) ){
                $notifyData = $this->__getNotifyData($_POST);
                $this->api->comment_notify('wish',$notifyData,$this->appId);
                echo $result;
            }else{
                echo "-1";
            }
        }

      private function __getNotifyData($data){
        //发送两条消息
        $result['toUid'] = intval($data['toUid'])?intval($data['toUid']):0;
        $need  = $this->wish->where('id='.intval($data['id']))->field('uid,content')->find();
        $result['uids'] = $need['uid'];
        if($result['toUid'] != $this->mid && $this->mid != $need['uid']){
                     $result['toUid'] !=0 && setScore($result['toUid'],'replayed_wish');
                        setScore($result['uids'],'replayed_wish');
                        setScore($this->mid,'replay_wish');
        }

        $result['url'] = sprintf('{SITE_URL}/Index/friends/uid/%s#Fli%s',intval($data['mid']),intval($data['id']));
        $result['title_body']['comment'] = t($data['content']);
        $result['title_data']['title'] = sprintf("<a href='%s'>%s</a>",$result['url'],$need['content']);
        $result['title_data']['type']  = "愿望";
        return $result;
    }
        
        /**
         * doDeleteReplay 
         * 删除回复
         * @access public
         * @return void
         */
        public function doDeleteReplay(){
            $id = intval($_POST['id']);
            $appid = intval($_POST['appid']);

            if( empty( $id ) ){
                echo "-1";
                return false;
            }

            $data['id'] = $id;
            $comment = D( 'Comment' );
            if( $comment->deleteComment($data) ){
                //修改愿望的回复数
                echo $this->wish->changeCount($appid);
            }else{
                echo "-1";
            }
        }

        /**
         * getReplayCount 
         * 获得回复数
         * @access public
         * @return void
         */
        public function getReplayCount(  ){
            $id = $_POST['id'];
            if( empty( $id ) ){
                echo "-1";
                return false;
            }

            $data['id'] = $id;
            if( $count = $this->wish->getReplayCount($data) ){
                echo trim($count)."条回复";
                return true;
            }else{
                echo -1;
                return false;
            }
        }

        /**
         * fileAway 
         * 获取归档查询的数据
         * @param mixed $uid 
         * @access private
         * @return void
         */
        private function fileAway($uid){
            $findTime = intval($_GET['date']); //获得传入的参数

            $this->wish->status = 0;
            if( is_array( $uid ) ){
                $id = array( 'in',$uid );
                $this->wish->id = $id;
            }else{
                $this->wish->uid = $uid;
            }

            $result = $this->wish->fileAway( $findTime ) ;
            if( $result == "error" ){
                $this->error( "错误的时间段" );
                return false;
            }
            return $result;
        }

        /**
         * __getWish 
         * 获得wish列表
         * @param int|array|string $uid uid
         * @access private
         * @return void
         */
        private function __getWish ($uid,$field=null,$order=null,$limit=null){
            //将数字或者数字型字符串转换成整型
            if( is_numeric( $uid ) ){
                $uid = intval( $uid );
            }
            //给wish对象的uid属性赋值
            $this->wish->uid   = $uid;
            return $this->wish->getWishList (null, $field, $order, $limit);
        }

        /**
         * _getWiget 
         * 获得需要传递给widget的数据
         * @param mixed $link 
         * @param mixed $uid 
         * @access private
         * @return void
         */
        private function _getWiget($link,$id){
            if( !is_array($id) ){
                $condition['uid'] = $id;
            }else{
                $condition['id'] = array( 'in',$id );
            }
            if( empty( $id) )
                unset( $condition);
            $map['fileaway']  = L( 'fileaway' );
            $map['link']      = $link;
            $map['condition'] = $condition ;
            $map['tableName'] = 'ts_wish';
            $map['limit']     = $this->wish->getConfig( 'fileawaypage' );
            $map['APP']       = __APP__;
            return $map;
        }

        /**
         * internalAssign 
         * action内部获取愿望数据集
         * @param mixed $uid 
         * @param mixed $link 
         * @access private
         * @return void
         */
        private function internalAssign( $uid,$link,$myreply = false){

            $map['uid']  = $uid;
            $condition =  $myreply? $this->wish->getMyReplyId( $map ) : $uid ;

            //检查配置文件是否打开了归档
            if( $this->wish->getConfig( 'fileaway' ) ){
                //获得归档的widget的参数集合
                $wiget     = $this->_getWiget($link,$condition);
                $this->assign( 'file_away',$wiget );
            }
       
            if( $myreply == true ){
                $list = isset( $_GET['date'] )?
                                                $this->fileAway( $condition )
                                              :
                                                $this->wish->getMyReplyWish( $condition )
                                              ;

            }else{

                $list = isset( $_GET['date'] )?
                                                $this->fileAway( $condition )
                                              :
                                                $this->__getWish( $condition,'',"cTime DESC" )
                                              ;
            }
            return $list;
        }
    }
