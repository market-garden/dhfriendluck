<?php
    /**
     * IndexAction
     * Mini的Action.接收和过滤网页传参
     * @uses Action
     * @package
     * @version $id$
     * @copyright 2009-2011 SamPeng
     * @author SamPeng <sampeng87@gmail.com>
     * @license PHP Version 5.2 {@link www.sampeng.cn}
     */
    class IndexAction extends Action{
        private $filter;
        private $mini;
        private $lastMini;
        protected $app = null;
        /**
         * __initialize
         * 初始化
         * @access public
         * @return void
         */
        public function _initialize(){
            //设置心情Action的数据处理层
            $this->mini = D( 'Mini' );
            $this->mini->setUid( $this->mid );  //设置登录者uid

        }
        /**
         * index
         * 好友的心情
         * @access public
         * @return void
         */
        public function index(){
            //获取好友列表id
            $gid        = intval($_GET['gid']);
            $friends    = $this->api->friend_getGroupUids($gid);

            $friends_id = empty( $friends ) ?null: array( "in",$friends);

            //获取配置，是否可以查看全部的心情
            if( $this->mini->getConfig( 'all' ) ){
                $this->assign( 'all','true' );
            }

            //获得登录者最后的心情.其中包含表情列表
            $lastMini = $this->mini->getLastMini( $this->mid,'content,cTime,id');

            if( !$friends_id ){
                $this->assign( 'ico_list',$lastMini[1] );//表情列表
                $this->assign( $lastMini[0] );//最后一条心情
                $this->display();
                return ;
            }

            //获得心情数据集
            $list = isset( $friends_id )?$this->__getMini($friends_id,'*','cTime desc'):false;


            //获得表情列表
            $this->assign( 'ico_list',$lastMini[1] );
            $this->assign( 'ico_type',$this->mini->getConfig( 'smiletype' ) );
            $this->assign( $lastMini[0] );
            $this->assign( 'stringcount',$lastMini[2] );
			$this->assign( 'friend_list',$this->mini->uid );

            $this->assign( $list );
            $this->display();
        }

        /**
         * friends
         * 某一个人的心情页面
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
         * 我的心情
         * @access public
         * @return void
         */
        public function my(){
            //取出心情数据集合
            if( !isset( $_GET['replay'] ) ){
                $link = "Index/my";
                $list = $this->internalAssign($this->mid,$link); //我的心情
            }else{
                $link = "Index/my/replay/do";
                $list = $this->internalAssign( $this->mid,$link,true ); //我的回复
            }

            if( $this->mini->getConfig( 'all' ) ){
                $this->assign( 'all','true' );
            }
             $this->setJsToken();
            //获得最后一条mini博客
            $lastMini = $this->mini->getLastMini( $this->mid,'content,cTime,id');

            //获得表情列表
            $this->assign( 'ico_list',$lastMini[1] );
            $this->assign( 'ico_type',$this->mini->getConfig( 'smiletype' ) );
            $this->assign( $lastMini[0] );
            $this->assign( 'stringcount',$lastMini[2] );

            $this->assign( $list );
            $this->display();

        }

        public function all(){
            //检查是否可以查看这个页面
            if( $this->mini->getConfig( 'all' ) ){
                //检查归档是否打开
                if( $this->mini->getConfig( 'fileaway' ) ){
                        if( isset( $_GET['date'] ) ){
                            $this->mini->status = 0;
                            $list = $this->mini->fileAway( $_GET['date'] );
                        }else{
                            $list = $this->mini->getMiniList(null,null,'cTime desc');
                        }
                        //获得归档的widget
                        $map = null;
                        $link = 'Index/all';
                        $wiget = $this->_getWiget($link,null);

                        $this->assign( 'file_away',$wiget );
                }else{

                    $list = $this->mini->getMiniList();
                }

                    //获得最后一条mini博客和表情
                    $lastMini = $this->mini->getLastMini( $this->mid,'content,cTime,id');

                    //对数据集进行判断。如果是自己发的心情，可以删除
                    $this->assign( 'ico_list',$lastMini[1] );
                    $this->assign( 'ico_type',$this->mini->getConfig( 'smiletype' ) );
                    $this->assign( $lastMini[0] );
                    $this->assign( 'stringcount',$lastMini[2] );
                    $this->assign( $list );
                    $this->display();

            }else{
                $this->error( L( 'error_all' ) );
            }
        }
        /**
         * doDeleteMini
         * 删除mini
         * @access public
         * @return void
         */
        public function doDeleteMini(  ){

            $id = intval($_REQUEST['id']);
            if( empty( $id ) || 0 == $id ){
                echo -1;
                exit;
            }

            $this->mini->id = $id; //要删除的id
            $result         = $this->mini->doDeleteMini();
            if( false != $result ){
                //关联删除这一条心情下面的所有回复
                $comment       = D( 'Comment' );
                $data['appid'] = $id;
                $data['type']  = APP_NAME;

                $comment->deleteComment($data);

                //关联删除这一条心情关联的动态
                $this->mini->deleteFeed( $id );
                echo 1;
            }else{
                echo "-1";
            }

        }

        /**
         * doAddMini
         * 添加mini
         * @access public
         * @return void
         */
        public function doAddMini(){
            $this->checkJsToken();
            $content = t($_REQUEST['content']);
            if( empty($content) ){
                echo -1;
                return false;
            }




            $data['content'] = $content;
            $data['uid'] = $this->mid;


            $add = $this->mini->doAddMini($data);

            if( $add ){
                echo $add;
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
            if( $result = $this->mini->_internalReplay( $id,null,$uid ) ){
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
            $data            += $this->mini->getOneName( $this->mid ); //追加发表人的名字
            $data['uid']     = $this->mid;

            //空数据不予以执行
            if( empty($data['comment']) ){
                echo "-1";
                return false;
            }

            //插入数据库并返回结果
            if( $result = $this->mini->addReplay($data,$_POST['more'],$_POST['mid']) ){
                $notifyData = $this->__getNotifyData($_POST);
                $this->api->comment_notify('mini',$notifyData,$this->appId);
                echo $result;
            }else{
                echo "-1";
            }
        }

      private function __getNotifyData($data){
        //发送两条消息
        $result['toUid'] = intval($data['toUid'])?intval($data['toUid']):0;
        $need  = $this->mini->where('id='.intval($data['id']))->field('uid,content')->find();
        $result['uids'] = $need['uid'];
        if($result['toUid'] != $this->mid && $this->mid != $need['uid']){
                     $result['toUid'] !=0 && setScore($result['toUid'],'replayed_mini');
                        setScore($result['uids'],'replayed_mini');
                        setScore($this->mid,'replay_mini');
        }

        $result['url'] = sprintf('{SITE_URL}/Index/friends/uid/%s#Fli%s',intval($data['mid']),intval($data['id']));
        $result['title_body']['comment'] = t($data['content']);
        $result['title_data']['title'] = sprintf("<a href='%s'>%s</a>",$result['url'],$need['content']);
        $result['title_data']['type']  = "心情";
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
                //修改心情的回复数
                echo $this->mini->changeCount($appid);
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
            if( $count = $this->mini->getReplayCount($data) ){
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

            $this->mini->status = 0;
            if( is_array( $uid ) ){
                $id = array( 'in',$uid );
                $this->mini->id = $id;
            }else{
                $this->mini->uid = $uid;
            }

            $result = $this->mini->fileAway( $findTime ) ;
            if( $result == "error" ){
                $this->error( "错误的时间段" );
                return false;
            }
            return $result;
        }

        /**
         * __getMini
         * 获得mini列表
         * @param int|array|string $uid uid
         * @access private
         * @return void
         */
        private function __getMini ($uid,$field=null,$order=null,$limit=null){
            //将数字或者数字型字符串转换成整型
            if( is_numeric( $uid ) ){
                $uid = intval( $uid );
            }
            //给mini对象的uid属性赋值
            $this->mini->uid   = $uid;
            return $this->mini->getMiniList (null, $field, $order, $limit);
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
            $map['tableName'] = 'ts_mini';
            $map['limit']     = $this->mini->getConfig( 'fileawaypage' );
            $map['APP']       = __APP__;
            return $map;
        }

        /**
         * internalAssign
         * action内部获取心情数据集
         * @param mixed $uid
         * @param mixed $link
         * @access private
         * @return void
         */
        private function internalAssign( $uid,$link,$myreply = false){

            $map['uid']  = $uid;
            $condition =  $myreply? $this->mini->getMyReplyId( $map ) : $uid ;

            //检查配置文件是否打开了归档
            if( $this->mini->getConfig( 'fileaway' ) ){
                //获得归档的widget的参数集合
                $wiget     = $this->_getWiget($link,$condition);
                $this->assign( 'file_away',$wiget );
            }

            if( $myreply == true ){
                $list = isset( $_GET['date'] )?
                                                $this->fileAway( $condition )
                                              :
                                                $this->mini->getMyReplyMini( $condition )
                                              ;

            }else{

                $list = isset( $_GET['date'] )?
                                                $this->fileAway( $condition )
                                              :
                                                $this->__getMini( $condition,'',"cTime DESC" )
                                              ;
            }
            return $list;
        }
    }
